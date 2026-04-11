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

        $salesSummary = $this->db->query("\n        SELECT \n            COUNT(DISTINCT order_id) as order_count,\n            COALESCE(SUM(total_payment_due), 0) as total_sales\n        FROM orders\n        WHERE DATE(date_created) = ?\n    ", [$today])->getRowArray();

        $itemsSoldRow = $this->db->query("\n        SELECT COALESCE(SUM(quantity_sold), 0) as items_sold\n        FROM transactions\n        WHERE date_created = ?\n    ", [$today])->getRowArray();

        $salesByCategory = $this->db->query("\n        SELECT \n            p.category,\n            COALESCE(SUM(t.total_sales), 0) as total_revenue\n        FROM transactions t\n        JOIN daily_stock_items dsi ON t.item_id = dsi.item_id\n        JOIN products p ON dsi.product_id = p.product_id\n        WHERE t.date_created = ?\n        GROUP BY p.category\n    ", [$today])->getResultArray();

        $categoryMap = array_column($salesByCategory, 'total_revenue', 'category');

        $salesByPayment = $this->db->query("\n        SELECT \n            payment_method,\n            order_type,\n            COALESCE(SUM(total_payment_due), 0) as total_revenue\n        FROM orders\n        WHERE DATE(date_created) = ?\n        GROUP BY payment_method, order_type\n    ", [$today])->getResultArray();

        $cashSales = 0;
        $gcashSales = 0;
        $foodpandaSales = 0;
        foreach ($salesByPayment as $row) {
            if ($row['payment_method'] === 'cash') {
                $cashSales = $row['total_revenue'];
            }
            if ($row['payment_method'] === 'gcash') {
                $gcashSales = $row['total_revenue'];
            }
            if ($row['order_type'] === 'foodpanda') {
                $foodpandaSales = $row['total_revenue'];
            }
        }

        $inventoryToday = $this->dailyStockModel->checkInventoryExists($today);
        $inventoryItems = [];
        $totalBeginningStock = 0;
        $totalEndingStock = 0;
        $lowStockProducts = [];
        $discrepancyBestSellerMap = [];

        if ($inventoryToday) {
            $inventoryItems = $this->dailyStockItemsModel->fetchAllStockItems($inventoryToday['daily_stock_id']);
            $salesByItem = $this->transactionsModel->getSalesDataByDate($today);
            $salesByItemMap = [];
            foreach ($salesByItem as $row) {
                $salesByItemMap[intval($row['item_id'])] = [
                    'quantity_sold' => intval($row['quantity_sold'] ?? 0),
                    'total_sales' => floatval($row['total_sales'] ?? 0),
                ];
            }

            $discrepancyRevenueByCategory = [
                'bakery' => 0.0,
                'drinks' => 0.0,
                'dough' => 0.0,
                'grocery' => 0.0,
            ];
            $discrepancyItemsSold = 0;

            foreach ($inventoryItems as $item) {
                $totalBeginningStock += intval($item['beginning_stock'] ?? 0);
                $totalEndingStock += intval($item['ending_stock'] ?? 0);

                if (intval($item['ending_stock'] ?? 0) > 0 && intval($item['ending_stock'] ?? 0) <= 5) {
                    $lowStockProducts[] = $item;
                }

                $category = strtolower(trim((string) ($item['category'] ?? '')));
                if (!array_key_exists($category, $discrepancyRevenueByCategory)) {
                    continue;
                }

                $itemId = intval($item['item_id'] ?? 0);
                $dbQtySold = intval($salesByItemMap[$itemId]['quantity_sold'] ?? 0);
                $beginningStock = intval($item['beginning_stock'] ?? 0);
                $pullOutQty = intval($item['pull_out_quantity'] ?? 0);
                $endingStock = intval($item['ending_stock'] ?? 0);
                $inventoryQtySold = max(0, $beginningStock - $pullOutQty - $endingStock);
                $discrepancyQty = max(0, $inventoryQtySold - $dbQtySold);

                if ($discrepancyQty <= 0) {
                    continue;
                }

                $price = floatval(($item['selling_price_per_piece'] ?? 0) > 0
                    ? ($item['selling_price_per_piece'] ?? 0)
                    : ($item['selling_price'] ?? 0));

                $discrepancyRevenue = $discrepancyQty * $price;
                $discrepancyItemsSold += $discrepancyQty;
                $discrepancyRevenueByCategory[$category] += $discrepancyRevenue;

                $productId = intval($item['product_id'] ?? 0);
                if ($productId > 0) {
                    if (!isset($discrepancyBestSellerMap[$productId])) {
                        $discrepancyBestSellerMap[$productId] = [
                            'product_id' => $productId,
                            'product_name' => $item['product_name'] ?? '',
                            'category' => $item['category'] ?? '',
                            'total_sold' => $discrepancyQty,
                            'revenue' => $discrepancyRevenue,
                        ];
                    } else {
                        $discrepancyBestSellerMap[$productId]['total_sold'] += $discrepancyQty;
                        $discrepancyBestSellerMap[$productId]['revenue'] += $discrepancyRevenue;
                    }
                }
            }

            $categoryMap['bakery'] = floatval($categoryMap['bakery'] ?? 0) + $discrepancyRevenueByCategory['bakery'];
            $categoryMap['drinks'] = floatval($categoryMap['drinks'] ?? 0) + $discrepancyRevenueByCategory['drinks'];
            $categoryMap['grocery'] = floatval($categoryMap['grocery'] ?? 0) + $discrepancyRevenueByCategory['grocery'];

            $itemsSoldRow['items_sold'] = intval($itemsSoldRow['items_sold'] ?? 0) + $discrepancyItemsSold;
            $salesSummary['total_sales'] = floatval($salesSummary['total_sales'] ?? 0)
                + $discrepancyRevenueByCategory['bakery']
                + $discrepancyRevenueByCategory['drinks']
                + $discrepancyRevenueByCategory['dough']
                + $discrepancyRevenueByCategory['grocery'];
        }

        $productStats = $this->db->query("\n        SELECT category, COUNT(*) as count\n        FROM products\n        WHERE deleted_at IS NULL\n        GROUP BY category\n    ")->getResultArray();

        $totalProducts = array_sum(array_column($productStats, 'count'));
        $totalRawMaterials = $this->rawMaterialsModel->countAll();
        $recentOrders = $this->orderModel->getOrderHistory(null, null, null, 5);

        $bestSellers = $this->db->query("\n        SELECT \n            p.product_id,\n            p.product_name, \n            p.category, \n            SUM(t.quantity_sold) as total_sold, \n            SUM(t.total_sales) as revenue\n        FROM transactions t\n        JOIN daily_stock_items dsi ON t.item_id = dsi.item_id\n        JOIN products p ON dsi.product_id = p.product_id\n        WHERE t.date_created = ?\n        GROUP BY p.product_id, p.product_name, p.category\n        ORDER BY total_sold DESC\n        LIMIT 5\n    ", [$today])->getResultArray();

        if (!empty($discrepancyBestSellerMap)) {
            $bestSellerMap = [];
            foreach ($bestSellers as $row) {
                $productId = intval($row['product_id'] ?? 0);
                if ($productId <= 0) {
                    continue;
                }

                $bestSellerMap[$productId] = [
                    'product_id' => $productId,
                    'product_name' => $row['product_name'] ?? '',
                    'category' => $row['category'] ?? '',
                    'total_sold' => intval($row['total_sold'] ?? 0),
                    'revenue' => floatval($row['revenue'] ?? 0),
                ];
            }

            foreach ($discrepancyBestSellerMap as $productId => $row) {
                if (isset($bestSellerMap[$productId])) {
                    $bestSellerMap[$productId]['total_sold'] += intval($row['total_sold'] ?? 0);
                    $bestSellerMap[$productId]['revenue'] += floatval($row['revenue'] ?? 0);
                } else {
                    $bestSellerMap[$productId] = $row;
                }
            }

            $bestSellers = array_values($bestSellerMap);
            usort($bestSellers, static function (array $left, array $right): int {
                $soldCompare = intval($right['total_sold'] ?? 0) <=> intval($left['total_sold'] ?? 0);
                if ($soldCompare !== 0) {
                    return $soldCompare;
                }

                return floatval($right['revenue'] ?? 0) <=> floatval($left['revenue'] ?? 0);
            });

            $bestSellers = array_slice($bestSellers, 0, 5);
        }

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
        return $this->db->query("\n        WITH RECURSIVE date_series AS (\n            SELECT CURDATE() - INTERVAL (? - 1) DAY AS dt\n            UNION ALL\n            SELECT dt + INTERVAL 1 DAY FROM date_series WHERE dt < CURDATE()\n        )\n        SELECT\n            DATE_FORMAT(ds.dt, '%b %e') AS label,\n            (\n                COALESCE((\n                    SELECT SUM(o.total_payment_due)\n                    FROM orders o\n                    WHERE DATE(o.date_created) = ds.dt\n                      AND o.voided_at IS NULL\n                ), 0)\n                +\n                COALESCE((\n                    SELECT SUM(\n                        GREATEST(0, (dsi.beginning_stock - dsi.pull_out_quantity - dsi.ending_stock) - COALESCE(tx.qty, 0))\n                        *\n                        (CASE\n                            WHEN COALESCE(pc.selling_price_per_piece, 0) > 0 THEN pc.selling_price_per_piece\n                            ELSE COALESCE(pc.selling_price, 0)\n                        END)\n                    )\n                    FROM daily_stock ds2\n                    JOIN daily_stock_items dsi ON dsi.daily_stock_id = ds2.daily_stock_id\n                    LEFT JOIN (\n                        SELECT item_id, SUM(quantity_sold) AS qty\n                        FROM transactions\n                        WHERE deleted_at IS NULL\n                        GROUP BY item_id\n                    ) tx ON tx.item_id = dsi.item_id\n                    LEFT JOIN (\n                        SELECT pcc.product_id, pcc.selling_price_per_piece, pcc.selling_price\n                        FROM product_costs pcc\n                        INNER JOIN (\n                            SELECT product_id, MAX(product_cost_id) AS latest_cost_id\n                            FROM product_costs\n                            GROUP BY product_id\n                        ) latest ON latest.product_id = pcc.product_id AND latest.latest_cost_id = pcc.product_cost_id\n                    ) pc ON pc.product_id = dsi.product_id\n                    WHERE ds2.inventory_date = ds.dt\n                ), 0)\n            ) AS value\n        FROM date_series ds\n        ORDER BY ds.dt ASC\n    ", [$days])->getResultArray();
    }

    private function getWeeklySalesTrend(int $weeks = 8): array
    {
        return $this->db->query("\n        WITH RECURSIVE week_series AS (\n            SELECT DATE(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY))\n                   - INTERVAL (? - 1) * 7 DAY AS week_start\n            UNION ALL\n            SELECT week_start + INTERVAL 7 DAY\n            FROM week_series\n            WHERE week_start + INTERVAL 7 DAY <=\n                  DATE(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY))\n        )\n        SELECT\n            CONCAT(DATE_FORMAT(ws.week_start, '%b %e'), ' - ',\n                   DATE_FORMAT(ws.week_start + INTERVAL 6 DAY, '%b %e')) AS label,\n            (\n                COALESCE((\n                    SELECT SUM(o.total_payment_due)\n                    FROM orders o\n                    WHERE DATE(o.date_created) >= ws.week_start\n                      AND DATE(o.date_created) <= ws.week_start + INTERVAL 6 DAY\n                      AND o.voided_at IS NULL\n                ), 0)\n                +\n                COALESCE((\n                    SELECT SUM(\n                        GREATEST(0, (dsi.beginning_stock - dsi.pull_out_quantity - dsi.ending_stock) - COALESCE(tx.qty, 0))\n                        *\n                        (CASE\n                            WHEN COALESCE(pc.selling_price_per_piece, 0) > 0 THEN pc.selling_price_per_piece\n                            ELSE COALESCE(pc.selling_price, 0)\n                        END)\n                    )\n                    FROM daily_stock ds2\n                    JOIN daily_stock_items dsi ON dsi.daily_stock_id = ds2.daily_stock_id\n                    LEFT JOIN (\n                        SELECT item_id, SUM(quantity_sold) AS qty\n                        FROM transactions\n                        WHERE deleted_at IS NULL\n                        GROUP BY item_id\n                    ) tx ON tx.item_id = dsi.item_id\n                    LEFT JOIN (\n                        SELECT pcc.product_id, pcc.selling_price_per_piece, pcc.selling_price\n                        FROM product_costs pcc\n                        INNER JOIN (\n                            SELECT product_id, MAX(product_cost_id) AS latest_cost_id\n                            FROM product_costs\n                            GROUP BY product_id\n                        ) latest ON latest.product_id = pcc.product_id AND latest.latest_cost_id = pcc.product_cost_id\n                    ) pc ON pc.product_id = dsi.product_id\n                    WHERE ds2.inventory_date >= ws.week_start\n                      AND ds2.inventory_date <= ws.week_start + INTERVAL 6 DAY\n                ), 0)\n            ) AS value\n        FROM week_series ws\n        ORDER BY ws.week_start ASC\n    ", [$weeks])->getResultArray();
    }

    private function getMonthlySalesTrend(int $months = 12): array
    {
        return $this->db->query("\n        WITH RECURSIVE month_series AS (\n            SELECT DATE_FORMAT(CURDATE() - INTERVAL (? - 1) MONTH, '%Y-%m-01') AS month_start\n            UNION ALL\n            SELECT DATE_FORMAT(month_start + INTERVAL 1 MONTH, '%Y-%m-01')\n            FROM month_series\n            WHERE month_start + INTERVAL 1 MONTH <= CURDATE()\n        )\n        SELECT\n            DATE_FORMAT(ms.month_start, '%b %Y') AS label,\n            (\n                COALESCE((\n                    SELECT SUM(o.total_payment_due)\n                    FROM orders o\n                    WHERE DATE_FORMAT(o.date_created, '%Y-%m') = DATE_FORMAT(ms.month_start, '%Y-%m')\n                      AND o.voided_at IS NULL\n                ), 0)\n                +\n                COALESCE((\n                    SELECT SUM(\n                        GREATEST(0, (dsi.beginning_stock - dsi.pull_out_quantity - dsi.ending_stock) - COALESCE(tx.qty, 0))\n                        *\n                        (CASE\n                            WHEN COALESCE(pc.selling_price_per_piece, 0) > 0 THEN pc.selling_price_per_piece\n                            ELSE COALESCE(pc.selling_price, 0)\n                        END)\n                    )\n                    FROM daily_stock ds2\n                    JOIN daily_stock_items dsi ON dsi.daily_stock_id = ds2.daily_stock_id\n                    LEFT JOIN (\n                        SELECT item_id, SUM(quantity_sold) AS qty\n                        FROM transactions\n                        WHERE deleted_at IS NULL\n                        GROUP BY item_id\n                    ) tx ON tx.item_id = dsi.item_id\n                    LEFT JOIN (\n                        SELECT pcc.product_id, pcc.selling_price_per_piece, pcc.selling_price\n                        FROM product_costs pcc\n                        INNER JOIN (\n                            SELECT product_id, MAX(product_cost_id) AS latest_cost_id\n                            FROM product_costs\n                            GROUP BY product_id\n                        ) latest ON latest.product_id = pcc.product_id AND latest.latest_cost_id = pcc.product_cost_id\n                    ) pc ON pc.product_id = dsi.product_id\n                    WHERE DATE_FORMAT(ds2.inventory_date, '%Y-%m') = DATE_FORMAT(ms.month_start, '%Y-%m')\n                ), 0)\n            ) AS value\n        FROM month_series ms\n        ORDER BY ms.month_start ASC\n    ", [$months])->getResultArray();
    }
}