<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function dashboard()
    {
        $sessionData = $this->getSessionData();
        $data = $sessionData;

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

    public function getDashboardData()
    {
        $today = date('Y-m-d');

        // ✅ FIX 1: Separate queries — the JOIN between orders & transactions
        // has no direct relationship column, so combining them inflates counts
        $salesSummary = $this->db->query("
        SELECT 
            COUNT(DISTINCT order_id) as order_count,
            COALESCE(SUM(total_payment_due), 0) as total_sales
        FROM orders
        WHERE DATE(date_created) = ?
    ", [$today])->getRowArray();

        $itemsSoldRow = $this->db->query("
        SELECT COALESCE(SUM(quantity_sold), 0) as items_sold
        FROM transactions
        WHERE date_created = ?
    ", [$today])->getRowArray();

        // ✅ 2. Sales by Category
        $salesByCategory = $this->db->query("
        SELECT 
            p.category,
            COALESCE(SUM(t.total_sales), 0) as total_revenue
        FROM transactions t
        JOIN daily_stock_items dsi ON t.item_id = dsi.item_id
        JOIN products p ON dsi.product_id = p.product_id
        WHERE t.date_created = ?
        GROUP BY p.category
    ", [$today])->getResultArray();

        $categoryMap = array_column($salesByCategory, 'total_revenue', 'category');

        // ✅ 3. Payment Methods
        $salesByPayment = $this->db->query("
        SELECT 
            payment_method,
            order_type,
            COALESCE(SUM(total_payment_due), 0) as total_revenue
        FROM orders
        WHERE DATE(date_created) = ?
        GROUP BY payment_method, order_type
    ", [$today])->getResultArray();

        $cashSales = 0;
        $gcashSales = 0;
        $foodpandaSales = 0;
        foreach ($salesByPayment as $row) {
            if ($row['payment_method'] === 'cash')
                $cashSales = $row['total_revenue'];
            if ($row['payment_method'] === 'gcash')
                $gcashSales = $row['total_revenue'];
            if ($row['order_type'] === 'foodpanda')
                $foodpandaSales = $row['total_revenue'];
        }

        // ✅ 4. Inventory
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
                if (intval($item['ending_stock']) > 0 && intval($item['ending_stock']) <= 5) {
                    $lowStockProducts[] = $item;
                }
            }
        }

        // ✅ 5. Product counts
        $productStats = $this->db->query("
        SELECT category, COUNT(*) as count
        FROM products
        WHERE deleted_at IS NULL
        GROUP BY category
    ")->getResultArray();

        $totalProducts = array_sum(array_column($productStats, 'count'));
        $totalRawMaterials = $this->rawMaterialsModel->countAll();

        // ✅ 6. Recent Orders
        $recentOrders = $this->orderModel->getOrderHistory(null, null, null, 5);

        // ✅ 7. Best Sellers
        $bestSellers = $this->db->query("
        SELECT 
            p.product_name, 
            p.category, 
            SUM(t.quantity_sold) as total_sold, 
            SUM(t.total_sales)   as revenue
        FROM transactions t
        JOIN daily_stock_items dsi ON t.item_id = dsi.item_id
        JOIN products p ON dsi.product_id = p.product_id
        WHERE t.date_created = ?
        GROUP BY p.product_id, p.product_name, p.category
        ORDER BY total_sold DESC
        LIMIT 5
    ", [$today])->getResultArray();

        // ✅ 8. Sales Trends — cached
        $cacheKey = "sales_trend_{$today}";
        $cache = \Config\Services::cache();
        $salesTrend = $cache->get($cacheKey);

        if ($salesTrend === null) {
            $salesTrend = [
                'daily' => $this->getDailySalesTrend(14),
                'weekly' => $this->getWeeklySalesTrend(8),
                'monthly' => $this->getMonthlySalesTrend(12),
            ];
            $cache->save($cacheKey, $salesTrend, 300);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'todaysSales' => floatval($salesSummary['total_sales'] ?? 0),
            'todaysOrderCount' => intval($salesSummary['order_count'] ?? 0),
            'todaysItemsSold' => intval($itemsSoldRow['items_sold'] ?? 0),
            'bakerySales' => floatval($categoryMap['bakery'] ?? 0),
            'drinksSales' => floatval($categoryMap['drinks'] ?? 0),
            'grocerySales' => floatval($categoryMap['grocery'] ?? 0),
            'cashSales' => floatval($cashSales),
            'gcashSales' => floatval($gcashSales),
            'foodpandaSales' => floatval($foodpandaSales),
            'inventoryExists' => $inventoryToday !== null,
            'inventoryData' => $inventoryToday,
            'totalBeginningStock' => $totalBeginningStock,
            'totalEndingStock' => $totalEndingStock,
            'lowStockProducts' => $lowStockProducts,
            'totalProducts' => $totalProducts,
            'totalRawMaterials' => $totalRawMaterials,
            'productsByCategory' => $productStats,
            'recentOrders' => $recentOrders,
            'bestSellers' => $bestSellers,
            'salesTrend' => $salesTrend,
            'currentDate' => date('F j, Y'),
            'currentTime' => date('g:i A'),
        ]);
    }

    private function getDailySalesTrend(int $days = 14): array
    {
        return $this->db->query("
        WITH RECURSIVE date_series AS (
            SELECT CURDATE() - INTERVAL (? - 1) DAY AS dt
            UNION ALL
            SELECT dt + INTERVAL 1 DAY FROM date_series WHERE dt < CURDATE()
        )
        SELECT
            DATE_FORMAT(ds.dt, '%b %e') AS label,
            COALESCE(SUM(o.total_payment_due), 0) AS value
        FROM date_series ds
        LEFT JOIN orders o
            ON DATE(o.date_created) = ds.dt
            AND o.voided_at IS NULL
        GROUP BY ds.dt
        ORDER BY ds.dt ASC
    ", [$days])->getResultArray();
    }

    private function getWeeklySalesTrend(int $weeks = 8): array
    {
        return $this->db->query("
        WITH RECURSIVE week_series AS (
            SELECT DATE(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY))
                   - INTERVAL (? - 1) * 7 DAY AS week_start
            UNION ALL
            SELECT week_start + INTERVAL 7 DAY
            FROM week_series
            WHERE week_start + INTERVAL 7 DAY <=
                  DATE(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY))
        )
        SELECT
            CONCAT(DATE_FORMAT(ws.week_start, '%b %e'), ' - ',
                   DATE_FORMAT(ws.week_start + INTERVAL 6 DAY, '%b %e')) AS label,
            COALESCE(SUM(o.total_payment_due), 0) AS value
        FROM week_series ws
        LEFT JOIN orders o
            ON DATE(o.date_created) >= ws.week_start
            AND DATE(o.date_created) <= ws.week_start + INTERVAL 6 DAY
            AND o.voided_at IS NULL
        GROUP BY ws.week_start
        ORDER BY ws.week_start ASC
    ", [$weeks])->getResultArray();
    }

    private function getMonthlySalesTrend(int $months = 12): array
    {
        return $this->db->query("
        WITH RECURSIVE month_series AS (
            SELECT DATE_FORMAT(CURDATE() - INTERVAL (? - 1) MONTH, '%Y-%m-01') AS month_start
            UNION ALL
            SELECT DATE_FORMAT(month_start + INTERVAL 1 MONTH, '%Y-%m-01')
            FROM month_series
            WHERE month_start + INTERVAL 1 MONTH <= CURDATE()
        )
        SELECT
            DATE_FORMAT(ms.month_start, '%b %Y') AS label,
            COALESCE(SUM(o.total_payment_due), 0) AS value
        FROM month_series ms
        LEFT JOIN orders o
            ON DATE_FORMAT(o.date_created, '%Y-%m') = DATE_FORMAT(ms.month_start, '%Y-%m')
            AND o.voided_at IS NULL
        GROUP BY ms.month_start
        ORDER BY ms.month_start ASC
    ", [$months])->getResultArray();
    }
}