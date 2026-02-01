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

    public function getTodaysTransactionsIds(): array
    {
        $today = date('Y-m-d');
        $results = $this->builder()
            ->select('sale_id')
            ->where('date_created', $today)
            ->get()
            ->getResultArray();

        return array_column($results, 'sale_id');
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

    /**
     * Get aggregated sales data for all items on a given date
     * This eliminates N+1 query problem by fetching all sales in one query
     */
    public function getSalesDataByDate(string $date): array
    {
        return $this->builder()
            ->select('item_id, SUM(total_sales) as total_sales, SUM(quantity_sold) as quantity_sold')
            ->where('date_created', $date)
            ->groupBy('item_id')
            ->get()
            ->getResultArray();
    }

    /** 
     * GetSales Properly
     */
    public function getSalesHistory()
    {
        return $this->builder()
            ->select('transactions.*, daily_stock_items.product_id, products.product_name')
            ->join('daily_stock_items', 'daily_stock_items.item_id = transactions.item_id', 'left')
            ->join('products', 'products.product_id = daily_stock_items.product_id', 'left')
            ->orderBy('transactions.date_created', 'DESC')
            ->orderBy('transactions.time_created', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getSalesHistoryByDateRange($dateFrom, $dateTo)
    {
        return $this->builder()
            ->select('transactions.*, daily_stock_items.product_id, products.product_name')
            ->join('daily_stock_items', 'daily_stock_items.item_id = transactions.item_id', 'left')
            ->join('products', 'products.product_id = daily_stock_items.product_id', 'left')
            ->where('transactions.date_created >=', $dateFrom)
            ->where('transactions.date_created <=', $dateTo)
            ->orderBy('transactions.date_created', 'DESC')
            ->orderBy('transactions.time_created', 'DESC')
            ->get()
            ->getResultArray();
    }

}
