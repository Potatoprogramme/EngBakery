<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\TransactionsModel;
use App\Models\ProductModel;
use App\Models\RawMaterialsModel;
use App\Models\DailyStockModel;
use App\Models\DailyStockItemsModel;

class DashboardController extends BaseController
{
    protected $orderModel;
    protected $transactionsModel;
    protected $productModel;
    protected $rawMaterialsModel;
    protected $dailyStockModel;
    protected $dailyStockItemsModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->transactionsModel = new TransactionsModel();
        $this->productModel = new ProductModel();
        $this->rawMaterialsModel = new RawMaterialsModel();
        $this->dailyStockModel = new DailyStockModel();
        $this->dailyStockItemsModel = new DailyStockItemsModel();
    }

    public function dashboard()
    {
        $sessionData = $this->getSessionData();
        $data = array_merge($sessionData, $this->getDashboardData());

        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

        if ($redirect = $this->redirectIfNotOwnerAndAdmin()) {
            return $redirect;
        }

        // Automatically check for low stock and notify owners on dashboard load
        try {
            \App\Libraries\LowStockNotifier::checkAndNotify();
        } catch (\Exception $e) {
            log_message('error', 'LowStockNotifier error on dashboard: ' . $e->getMessage());
        }

        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Dashboard', $data) .
            view('Template/Footer');
    }

    private function getDashboardData(): array
    {
        $today = date('Y-m-d');

        // Today's Sales Summary
        $todaysSales = $this->orderModel->getTodaysSales();
        $todaysOrderCount = $this->orderModel->getTodaysOrderCount();
        $todaysItemsSold = $this->transactionsModel->getTodaysTotalItemsSold();

        // Sales by Category
        $bakerySales = $this->transactionsModel->getTodaysSaleByCategory('bakery');
        $drinksSales = $this->transactionsModel->getTodaysSaleByCategory('drinks');
        $grocerySales = $this->transactionsModel->getTodaysSaleByCategory('grocery');

        // Payment Methods
        $cashSales = $this->orderModel->getTotalSalesByPaymentMethod('cash');
        $gcashSales = $this->orderModel->getTotalSalesByPaymentMethod('gcash');
        $foodpandaSales = $this->orderModel->getTotalSalesByOrderType('foodpanda');

        // Inventory Status
        $inventoryToday = $this->dailyStockModel->checkInventoryExists($today);
        $inventoryItems = [];
        $totalBeginningStock = 0;
        $totalEndingStock = 0;
        $lowStockProducts = [];

        if ($inventoryToday) {
            $inventoryItems = $this->dailyStockItemsModel->fetchAllStockItems($inventoryToday['daily_stock_id']);
            foreach ($inventoryItems as $item) {
                $totalBeginningStock += intval($item['beginning_stock']);
                $totalEndingStock += intval($item['ending_stock']);
                // Low stock alert (less than 5 items remaining)
                if (intval($item['ending_stock']) > 0 && intval($item['ending_stock']) <= 5) {
                    $lowStockProducts[] = $item;
                }
            }
        }

        // Total counts
        $totalProducts = $this->productModel->countAll();
        $totalRawMaterials = $this->rawMaterialsModel->countAll();

        // Product counts by category
        $productsByCategory = $this->db->query("
            SELECT category, COUNT(*) as count 
            FROM products 
            GROUP BY category
        ")->getResultArray();

        // Recent orders (last 5)
        $recentOrders = $this->orderModel->getOrderHistory(null, null);
        $recentOrders = array_slice($recentOrders, 0, 5);

        // Best selling products today
        $bestSellers = $this->db->query("
            SELECT p.product_name, p.category, SUM(t.quantity_sold) as total_sold, SUM(t.total_sales) as revenue
            FROM transactions t
            JOIN daily_stock_items dsi ON t.item_id = dsi.item_id
            JOIN products p ON dsi.product_id = p.product_id
            WHERE t.date_created = ?
            GROUP BY p.product_id, p.product_name, p.category
            ORDER BY total_sold DESC
            LIMIT 5
        ", [$today])->getResultArray();

            // Sales trends for dashboard line graph section
            $dailyTrend = $this->getDailySalesTrend(14);
            $weeklyTrend = $this->getWeeklySalesTrend(8);
            $monthlyTrend = $this->getMonthlySalesTrend(12);

        return [
            'todaysSales' => floatval($todaysSales['total_sales'] ?? 0),
            'todaysOrderCount' => $todaysOrderCount,
            'todaysItemsSold' => $todaysItemsSold,
            'bakerySales' => floatval($bakerySales['total_revenue'] ?? 0),
            'drinksSales' => floatval($drinksSales['total_revenue'] ?? 0),
            'grocerySales' => floatval($grocerySales['total_revenue'] ?? 0),
            'cashSales' => floatval($cashSales['total_revenue'] ?? 0),
            'gcashSales' => floatval($gcashSales['total_revenue'] ?? 0),
            'foodpandaSales' => floatval($foodpandaSales['total_revenue'] ?? 0),
            'inventoryExists' => $inventoryToday !== null,
            'inventoryData' => $inventoryToday,
            'totalBeginningStock' => $totalBeginningStock,
            'totalEndingStock' => $totalEndingStock,
            'lowStockProducts' => $lowStockProducts,
            'totalProducts' => $totalProducts,
            'totalRawMaterials' => $totalRawMaterials,
            'productsByCategory' => $productsByCategory,
            'recentOrders' => $recentOrders,
            'bestSellers' => $bestSellers,
                'salesTrend' => [
                    'daily' => $dailyTrend,
                    'weekly' => $weeklyTrend,
                    'monthly' => $monthlyTrend,
                ],
            'currentDate' => date('F j, Y'),
            'currentTime' => date('g:i A'),
        ];
    }

    private function getDailySalesTrend(int $days = 14): array
    {
        $days = max(1, $days);
        $end = new \DateTimeImmutable(date('Y-m-d'));
        $start = $end->sub(new \DateInterval('P' . ($days - 1) . 'D'));

        $rows = $this->db->query(
            "SELECT date_created AS day, SUM(total_payment_due) AS total
             FROM orders
             WHERE date_created BETWEEN ? AND ?
               AND voided_at IS NULL
             GROUP BY date_created",
            [$start->format('Y-m-d'), $end->format('Y-m-d')]
        )->getResultArray();

        $totalsByDate = [];
        foreach ($rows as $row) {
            $totalsByDate[$row['day']] = floatval($row['total'] ?? 0);
        }

        $trend = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $start->add(new \DateInterval('P' . $i . 'D'));
            $key = $date->format('Y-m-d');
            $trend[] = [
                'label' => $date->format('M j'),
                'value' => floatval($totalsByDate[$key] ?? 0),
            ];
        }

        return $trend;
    }

    private function getWeeklySalesTrend(int $weeks = 8): array
    {
        $weeks = max(1, $weeks);
        $today = new \DateTimeImmutable(date('Y-m-d'));
        $currentWeekStart = $today->modify('monday this week');
        $startWeek = $currentWeekStart->sub(new \DateInterval('P' . ($weeks - 1) . 'W'));

        $rows = $this->db->query(
            "SELECT DATE_SUB(date_created, INTERVAL WEEKDAY(date_created) DAY) AS week_start,
                    SUM(total_payment_due) AS total
             FROM orders
             WHERE date_created BETWEEN ? AND ?
               AND voided_at IS NULL
             GROUP BY week_start",
            [$startWeek->format('Y-m-d'), $today->format('Y-m-d')]
        )->getResultArray();

        $totalsByWeek = [];
        foreach ($rows as $row) {
            $totalsByWeek[$row['week_start']] = floatval($row['total'] ?? 0);
        }

        $trend = [];
        for ($i = 0; $i < $weeks; $i++) {
            $weekStart = $startWeek->add(new \DateInterval('P' . $i . 'W'));
            $key = $weekStart->format('Y-m-d');
            $trend[] = [
                'label' => 'Wk of ' . $weekStart->format('M j'),
                'value' => floatval($totalsByWeek[$key] ?? 0),
            ];
        }

        return $trend;
    }

    private function getMonthlySalesTrend(int $months = 12): array
    {
        $months = max(1, $months);
        $monthStart = new \DateTimeImmutable(date('Y-m-01'));
        $startMonth = $monthStart->sub(new \DateInterval('P' . ($months - 1) . 'M'));
        $endDate = (new \DateTimeImmutable(date('Y-m-d')))->format('Y-m-d');

        $rows = $this->db->query(
            "SELECT DATE_FORMAT(date_created, '%Y-%m-01') AS month_start,
                    SUM(total_payment_due) AS total
             FROM orders
             WHERE date_created BETWEEN ? AND ?
               AND voided_at IS NULL
             GROUP BY month_start",
            [$startMonth->format('Y-m-d'), $endDate]
        )->getResultArray();

        $totalsByMonth = [];
        foreach ($rows as $row) {
            $totalsByMonth[$row['month_start']] = floatval($row['total'] ?? 0);
        }

        $trend = [];
        for ($i = 0; $i < $months; $i++) {
            $month = $startMonth->add(new \DateInterval('P' . $i . 'M'));
            $key = $month->format('Y-m-01');
            $trend[] = [
                'label' => $month->format('M Y'),
                'value' => floatval($totalsByMonth[$key] ?? 0),
            ];
        }

        return $trend;
    }
}