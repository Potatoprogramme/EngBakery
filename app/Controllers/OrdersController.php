<?php

namespace App\Controllers;

class OrdersController extends BaseController
{
    public function order(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('Template/notification') .
                view('Orders/Order') .
                view('Template/Footer');
    }

    public function orderHistory(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('Template/notification') .
                view('Orders/OrderHistory') .
                view('Template/Footer');
    }

    public function getProducts()
    {
        $products = $this->db->query("
            SELECT p.product_id, p.category, p.product_name, p.product_description,
                   CASE 
                       WHEN p.category = 'bread' AND pc.selling_price_per_piece > 0 THEN pc.selling_price_per_piece
                       ELSE pc.selling_price 
                   END as price,
                   pc.selling_price, pc.selling_price_per_piece, pc.selling_price_per_tray,
                   pc.pieces_per_yield, pc.trays_per_yield
            FROM products p
            LEFT JOIN product_costs pc ON p.product_id = pc.product_id
            ORDER BY p.category, p.product_name
        ")->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $products
        ]);
    }

    public function processPayment()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['items']) || !is_array($data['items'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No items in order.'
            ]);
        }

        if (!isset($data['total_payment_due']) || !isset($data['amount_received'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment details required.'
            ]);
        }

        if (floatval($data['amount_received']) < floatval($data['total_payment_due'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Insufficient payment amount.'
            ]);
        }

        $today = date('Y-m-d');
        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();

        if (!$dailyStock) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No inventory created for today. Please create inventory first.'
            ]);
        }

        $this->db->transStart();

        try {
            $orderNumber = $this->orderModel->generateOrderNumber();

            $orderId = $this->orderModel->createOrder([
                'total_payment_due' => $data['total_payment_due'],
                'amount_received' => $data['amount_received'],
                'amount_change' => floatval($data['amount_received']) - floatval($data['total_payment_due']),
                'payment_method' => $data['payment_method'] ?? 'cash',
                'order_type' => $data['order_type'] ?? 'walk-in'
            ]);

            if (!$orderId) {
                throw new \Exception('Failed to create order.');
            }

            if (!$this->orderItemModel->addOrderItems($orderId, $data['items'])) {
                throw new \Exception('Failed to add order items.');
            }

            foreach ($data['items'] as $item) {
                $stockItem = $this->dailyStockItemsModel
                    ->where('daily_stock_id', $dailyStock['daily_stock_id'])
                    ->where('product_id', $item['product_id'])
                    ->first();

                if ($stockItem) {
                    $newEndingStock = intval($stockItem['ending_stock']) - intval($item['quantity']);
                    if ($newEndingStock < 0) $newEndingStock = 0;

                    $this->dailyStockItemsModel->update($stockItem['item_id'], [
                        'ending_stock' => $newEndingStock
                    ]);

                    $this->dailySalesModel->recordSale(
                        $stockItem['item_id'],
                        intval($item['quantity']),
                        floatval($item['total'])
                    );
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed.');
            }

            $orderDetails = $this->orderModel->getOrderById($orderId);
            $orderItems = $this->orderItemModel->getOrderItems($orderId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Payment processed successfully.',
                'data' => [
                    'order_id' => $orderId,
                    'order_number' => $orderNumber,
                    'order' => $orderDetails,
                    'items' => $orderItems
                ]
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getOrderHistory()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $orders = $this->orderModel->getOrderHistory($dateFrom, $dateTo);

        return $this->response->setJSON([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function getOrderDetails($orderId = null)
    {
        if (!$orderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID required.'
            ]);
        }

        $order = $this->orderModel->getOrderById($orderId);

        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found.'
            ]);
        }

        $items = $this->orderItemModel->getOrderItems($orderId);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'order' => $order,
                'items' => $items
            ]
        ]);
    }

    public function getTodaysSales()
    {
        $orderSales = $this->orderModel->getTodaysSales();
        $itemSales = $this->dailySalesModel->getTodaysSummary();

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'total_orders' => intval($orderSales['total_orders'] ?? 0),
                'total_revenue' => floatval($orderSales['total_sales'] ?? 0),
                'total_items_sold' => intval($itemSales['total_items_sold'] ?? 0)
            ]
        ]);
    }

    public function voidOrder($orderId = null)
    {
        if (!$orderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID required.'
            ]);
        }

        $order = $this->orderModel->find($orderId);

        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found.'
            ]);
        }

        $this->db->transStart();

        try {
            $orderItems = $this->orderItemModel->getOrderItems($orderId);
            $today = date('Y-m-d');
            $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();

            if ($dailyStock) {
                foreach ($orderItems as $item) {
                    $stockItem = $this->dailyStockItemsModel
                        ->where('daily_stock_id', $dailyStock['daily_stock_id'])
                        ->where('product_id', $item['product_id'])
                        ->first();

                    if ($stockItem) {
                        $this->dailyStockItemsModel->update($stockItem['item_id'], [
                            'ending_stock' => intval($stockItem['ending_stock']) + intval($item['amout'])
                        ]);
                    }
                }
            }

            $this->orderItemModel->where('order_id', $orderId)->delete();
            $this->orderModel->delete($orderId);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Failed to void order.');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Order voided successfully.'
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}