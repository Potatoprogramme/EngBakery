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
        'amout',
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
                'amout' => intval($item['quantity']),
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
            ->select('order_item_id.*, products.product_name, products.category')
            ->join('products', 'products.product_id = order_item_id.product_id', 'left')
            ->where('order_id', $orderId)
            ->get()
            ->getResultArray();
    }
}
