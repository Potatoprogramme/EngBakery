<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'total_payment_due',
        'amount_received',
        'amount_change',
        'payment_method',
        'order_type',
        'cashier_name',
        'date_created',
        'time_created'
    ];
    protected $useTimestamps = false;

    public function generateOrderNumber(): string
    {
        $today = date('Y-m-d');
        $dateCode = date('Ymd');

        $todayCount = $this->where('date_created', $today)->countAllResults();
        $sequence = str_pad($todayCount + 1, 3, '0', STR_PAD_LEFT);

        return "ORD-{$dateCode}-{$sequence}";
    }

    public function createOrder(array $data): int|false
    {
        $orderData = [
            'total_payment_due' => floatval($data['total_payment_due']),
            'amount_received' => floatval($data['amount_received']),
            'amount_change' => floatval($data['amount_change']),
            'payment_method' => $data['payment_method'],
            'order_type' => $data['order_type'],
            'cashier_name' => $data['cashier_name'] ?? 'Unknown',
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s')
        ];

        if ($this->insert($orderData)) {
            return $this->insertID();
        }
        return false;
    }

    public function getOrderHistory(string $dateFrom = null, string $dateTo = null): array
    {
        $builder = $this->builder();
        $builder->select('orders.*, 
            CONCAT("ORD-", DATE_FORMAT(orders.date_created, "%Y%m%d"), "-", 
            LPAD((SELECT COUNT(*) FROM orders o2 WHERE o2.date_created = orders.date_created AND o2.order_id <= orders.order_id), 3, "0")) as order_number');

        if ($dateFrom) {
            $builder->where('date_created >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('date_created <=', $dateTo);
        }

        $builder->orderBy('date_created', 'DESC');
        $builder->orderBy('time_created', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getOrderById(int $orderId): array|null
    {
        $order = $this->find($orderId);
        if ($order) {
            $dateCode = date('Ymd', strtotime($order['date_created']));
            $sequence = $this->where('date_created', $order['date_created'])
                ->where('order_id <=', $orderId)
                ->countAllResults();
            $order['order_number'] = "ORD-{$dateCode}-" . str_pad($sequence, 3, '0', STR_PAD_LEFT);
        }
        return $order;
    }

    public function getTodaysSales(): array
    {
        $today = date('Y-m-d');
        return $this->builder()
            ->selectSum('total_payment_due', 'total_sales')
            ->selectCount('order_id', 'total_orders')
            ->where('date_created', $today)
            ->get()
            ->getRowArray();
    }

    public function getTotalSalesByOrderType($orderType)
    {
        $today = date('Y-m-d');

        // Query orders table directly for sales by payment method
        return $this->builder()
            ->select('orders.payment_method, SUM(orders.total_payment_due) AS total_revenue')
            ->where('orders.date_created', $today)
            ->where('LOWER(orders.payment_method)', strtolower($orderType))
            ->groupBy('orders.payment_method')
            ->get()
            ->getRowArray();
    }

    public function getTodaysOrderCount(): int
    {
        $today = date('Y-m-d');
        return $this->where('date_created', $today)->countAllResults();
    }

    /**
     * Get today's total sales by payment method
     */
    public function getTodaysSalesByPaymentMethod(string $paymentMethod): float
    {
        $today = date('Y-m-d');

        $result = $this->builder()
            ->selectSum('total_payment_due', 'total')
            ->where('date_created', $today)
            ->where('LOWER(payment_method)', strtolower($paymentMethod))
            ->get()
            ->getRowArray();

        return floatval($result['total'] ?? 0);
    }

    /**
     * Process a complete order with items and stock updates
     * Returns order data on success, throws exception on failure
     */
    public function processCompleteOrder(array $orderData, array $items, $dailyStockItemsModel, $transactionsModel, int $dailyStockId): array
    {
        $orderId = $this->createOrder($orderData);

        if (!$orderId) {
            throw new \Exception('Failed to create order.');
        }

        // Get the order item model
        $orderItemModel = new \App\Models\OrderItemModel();

        if (!$orderItemModel->addOrderItems($orderId, $items)) {
            throw new \Exception('Failed to add order items.');
        }

        // Update stock and record sales for each item
        foreach ($items as $item) {
            $stockItem = $dailyStockItemsModel->getStockItemByProduct($dailyStockId, intval($item['product_id']));

            if ($stockItem) {
                // Deduct from ending stock
                $dailyStockItemsModel->deductStock($stockItem['item_id'], intval($item['quantity']));

                // Record the sale in transactions table
                $transactionsModel->recordSale(
                    $stockItem['item_id'],
                    intval($item['quantity']),
                    floatval($item['total'])
                );
            } else {
                // Product not in inventory - auto-add it with 0 beginning stock
                $newItemId = $dailyStockItemsModel->addProductToInventory(
                    $dailyStockId,
                    intval($item['product_id']),
                    0 // beginning_stock = 0
                );

                if ($newItemId) {
                    // Now deduct and record the sale
                    $dailyStockItemsModel->deductStock($newItemId, intval($item['quantity']));
                    $transactionsModel->recordSale(
                        $newItemId,
                        intval($item['quantity']),
                        floatval($item['total'])
                    );
                }
            }
        }

        return [
            'order_id' => $orderId,
            'order_number' => $this->generateOrderNumber(),
            'order' => $this->getOrderById($orderId),
            'items' => $orderItemModel->getOrderItems($orderId)
        ];
    }

    /**
     * Void an order and restore stock
     */
    public function voidOrderWithRestore(int $orderId, $orderItemModel, $dailyStockItemsModel, ?int $dailyStockId = null): bool
    {
        $order = $this->find($orderId);
        if (!$order) {
            throw new \Exception('Order not found.');
        }

        $orderItems = $orderItemModel->getOrderItems($orderId);

        // Restore stock if we have today's inventory
        if ($dailyStockId) {
            foreach ($orderItems as $item) {
                $stockItem = $dailyStockItemsModel->getStockItemByProduct($dailyStockId, $item['product_id']);
                if ($stockItem) {
                    $dailyStockItemsModel->restoreStock($stockItem['item_id'], intval($item['amout']));
                }
            }
        }

        // Delete order items and order
        $orderItemModel->where('order_id', $orderId)->delete();
        return $this->delete($orderId);
    }

    public function getTotalSalesByDateRange($dateFrom, $dateTo)
    {
        $result = $this->selectSum('total_payment_due', 'total_amount')
            ->where('DATE(date_created) >=', $dateFrom)
            ->where('DATE(date_created) <=', $dateTo)
            ->get()
            ->getRowArray();

        return $result['total_amount'] ?? 0;
    }

    /**
     * Get order count by date range
     */
    public function getOrderCountByDateRange($dateFrom, $dateTo)
    {
        return $this->where('DATE(date_created) >=', $dateFrom)
            ->where('DATE(date_created) <=', $dateTo)
            ->countAllResults();
    }

    /**
     * Get sales by payment method for date range
     */
    public function getSalesByPaymentMethod($paymentMethod, $dateFrom, $dateTo)
    {
        $result = $this->selectSum('total_payment_due', 'total_amount')
            ->where('payment_method', $paymentMethod)
            ->where('DATE(date_created) >=', $dateFrom)
            ->where('DATE(date_created) <=', $dateTo)
            ->get()
            ->getRowArray();

        return $result['total_amount'] ?? 0;
    }

    // Get Sales by Category for date range
    public function getSalesByCategory($category, $dateFrom, $dateTo)
    {
        $result = $this->selectSum('orders.total_payment_due', 'total_revenue')
            ->where('payment_method', $category)
            ->where('DATE(orders.date_created) <=', $dateTo)
            ->where('DATE(orders.date_created) >=', $dateFrom)
            ->get()
            ->getRowArray();
        return $result['total_revenue'] ?? 0;
    }
}
