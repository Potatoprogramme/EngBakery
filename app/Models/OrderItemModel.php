<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'product_id',
        'order_id',
        'amount',
        'cost_per_item',
        'total_cost_of_item',
        'date_created',
        'time_created'
    ];
    protected $useTimestamps = false;

    public function addOrderItems(int $orderId, array $items): bool
    {
        $insertData = [];
        $now = date('Y-m-d');
        $time = date('H:i:s');

        foreach ($items as $item) {
            $insertData[] = [
                'order_id' => $orderId,
                'product_id' => intval($item['product_id']),
                'amount' => intval($item['quantity']),
                'cost_per_item' => floatval($item['price']),
                'total_cost_of_item' => floatval($item['total']),
                'date_created' => $now,
                'time_created' => $time
            ];
        }

        return $this->insertBatch($insertData) !== false;
    }

    public function deleteByOrderId(int $orderId): bool
    {
        return $this->where('order_id', $orderId)->delete() > 0;
    }

    public function getOrderItems(int $orderId): array
    {
        return $this->builder()
            ->select('order_items.*, products.product_name, products.category')
            ->join('products', 'products.product_id = order_items.product_id', 'left')
            ->where('order_id', $orderId)
            ->get()
            ->getResultArray();
    }

    /**
     * Get all sales (order items) with product and order info
     */
    public function getSalesHistory(): array
    {
        return $this->builder()
            ->select('order_items.order_item_id, order_items.order_id, order_items.amount as quantity, order_items.cost_per_item as price, order_items.total_cost_of_item as total, order_items.date_created, order_items.time_created, products.product_name, products.category, orders.payment_method, orders.order_type')
            ->join('products', 'products.product_id = order_items.product_id', 'left')
            ->join('orders', 'orders.order_id = order_items.order_id', 'left')
            ->orderBy('order_items.date_created', 'DESC')
            ->orderBy('order_items.time_created', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get sales (order items) by date range
     */
    public function getSalesHistoryByDateRange(string $dateFrom, string $dateTo): array
    {
        return $this->builder()
            ->select('order_items.order_item_id, order_items.order_id, order_items.amount as quantity, order_items.cost_per_item as price, order_items.total_cost_of_item as total, order_items.date_created, order_items.time_created, products.product_name, products.category, orders.payment_method, orders.order_type')
            ->join('products', 'products.product_id = order_items.product_id', 'left')
            ->join('orders', 'orders.order_id = order_items.order_id', 'left')
            ->where('order_items.date_created >=', $dateFrom)
            ->where('order_items.date_created <=', $dateTo)
            ->orderBy('order_items.date_created', 'DESC')
            ->orderBy('order_items.time_created', 'DESC')
            ->get()
            ->getResultArray();
    }
}
