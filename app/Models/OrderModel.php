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
}
