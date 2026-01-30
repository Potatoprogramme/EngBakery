<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionsModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'sale_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'item_id',
        'quantity_sold',
        'total_sales',
        'date_created',
        'time_created'
    ];
    protected $useTimestamps = false;

    public function recordSale(int $itemId, int $quantity, float $total): bool
    {
        $data = [
            'item_id' => $itemId,
            'quantity_sold' => $quantity,
            'total_sales' => $total,
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s')
        ];

        return $this->insert($data) !== false;
    }

    public function getSalesByDate(string $date): array
    {
        return $this->builder()
            ->select('transactions.*, daily_stock_items.product_id, products.product_name')
            ->join('daily_stock_items', 'daily_stock_items.item_id = transactions.item_id', 'left')
            ->join('products', 'products.product_id = daily_stock_items.product_id', 'left')
            ->where('transactions.date_created', $date)
            ->get()
            ->getResultArray();
    }

    public function getTodaysSummary(): array
    {
        $today = date('Y-m-d');
        return $this->builder()
            ->selectSum('quantity_sold', 'total_items_sold')
            ->selectSum('total_sales', 'total_revenue')
            ->where('date_created', $today)
            ->get()
            ->getRowArray();
    }

    public function getTodaysSaleByCategory($category)
    {
        $today = date('Y-m-d');
        return $this->builder()
            ->select('products.category, SUM(transactions.quantity_sold) AS total_items_sold, SUM(transactions.total_sales) AS total_revenue')
            ->join('daily_stock_items', 'daily_stock_items.item_id = transactions.item_id', 'left')
            ->join('products', 'products.product_id = daily_stock_items.product_id', 'left')
            ->where('transactions.date_created', $today)
            ->where('products.category', $category)
            ->groupBy('products.category')
            ->get()
            ->getRowArray();
    }

    public function getTodaysTotalItemsSold(): int
    {
        $today = date('Y-m-d');
        $result = $this->builder()
            ->selectSum('quantity_sold', 'total_items_sold')
            ->where('date_created', $today)
            ->get()
            ->getRowArray();

        return intval($result['total_items_sold'] ?? 0);
    }
}
