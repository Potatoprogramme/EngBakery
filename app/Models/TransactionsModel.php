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
        'order_id',
        'quantity_sold',
        'total_sales',
        'date_created',
        'time_created'
    ];
    protected $useTimestamps = false;

    public function recordSale(int $itemId, int $quantity, float $total, ?int $orderId = null): bool
    {
        $data = [
            'item_id' => $itemId,
            'order_id' => $orderId,
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
     * Get aggregated sales data for all items on a given date/time window.
     */
    public function getSalesDataByDateAndTimeRange(string $date, string $startTime, string $endTime): array
    {
        return $this->builder()
            ->select('item_id, SUM(total_sales) as total_sales, SUM(quantity_sold) as quantity_sold')
            ->where('date_created', $date)
            ->where('time_created >=', $startTime)
            ->where('time_created <=', $endTime)
            ->groupBy('item_id')
            ->get()
            ->getResultArray();
    }

    /** 
     * GetSales Properly
     */
    public function getSalesByCategory($category, $dateFrom, $dateTo)
    {
        $result = $this->builder()
            ->select('SUM(transactions.total_sales) as total_revenue')
            ->join('daily_stock_items', 'daily_stock_items.item_id = transactions.item_id', 'left')
            ->join('products', 'products.product_id = daily_stock_items.product_id', 'left')
            ->where('products.category', $category)
            ->where('transactions.date_created >=', $dateFrom)
            ->where('transactions.date_created <=', $dateTo)
            ->get()
            ->getRowArray();

        return $result['total_revenue'] ?? 0;
    }

    public function getSalesHistoryByDateRange($dateFrom, $dateTo)
    {
        $db = \Config\Database::connect();
        return $db->table('orders')
            ->select('orders.order_id, 
                  orders.order_type,
                  orders.payment_method,
                  orders.distributed_note,
                  orders.cashier_name,
                  orders.total_payment_due,
                  MIN(transactions.sale_id) as sale_id,
                  GROUP_CONCAT(DISTINCT products.product_name SEPARATOR ", ") as product_name,
                  COALESCE(SUM(transactions.quantity_sold), 0) as quantity_sold, 
                  COALESCE(SUM(transactions.total_sales), 0) as total_sales, 
                  orders.date_created, 
                  orders.time_created')
            ->join('transactions', 'transactions.order_id = orders.order_id', 'left')
            ->join('daily_stock_items', 'daily_stock_items.item_id = transactions.item_id', 'left')
            ->join('products', 'products.product_id = daily_stock_items.product_id', 'left')
            ->where('orders.date_created >=', $dateFrom)
            ->where('orders.date_created <=', $dateTo)
            ->groupBy('orders.order_id')
            ->orderBy('orders.date_created', 'DESC')
            ->orderBy('orders.time_created', 'DESC')
            ->get()
            ->getResultArray();
    }
    public function getOrderCountByDateRange($dateFrom, $dateTo)
    {
        return $this->where('DATE(date_created) >=', $dateFrom)
            ->where('DATE(date_created) <=', $dateTo)
            ->groupBy('order_id')
            ->countAllResults();
    }
}
