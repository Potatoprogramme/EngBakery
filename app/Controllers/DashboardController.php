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

    private function getSessionData()
    {
        $session = session();
        return [
            'user_id' => $session->get('id'),
            'email' => $session->get('email'),
            'username' => $session->get('username'),
            'employee_type' => $session->get('employee_type'),
            'name' => $session->get('name'),
            'is_logged_in' => $session->get('is_logged_in'),
        ];
    }

    public function dashboard()
    {
        $sessionData = $this->getSessionData();
        $data = array_merge($sessionData, $this->getDashboardData());

        if (!isset($data['is_logged_in']) || !$data['is_logged_in']) {
            return redirect()->to(base_url('login'))->with('error_message', 'Please log in to access the dashboard.');
        }

        return view('Template/Header') .
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
        $bakerySales = $this->transactionsModel->getTodaysSaleByCategory('bread');
        $drinksSales = $this->transactionsModel->getTodaysSaleByCategory('drinks');
        $grocerySales = $this->transactionsModel->getTodaysSaleByCategory('grocery');

        // Payment Methods
        $cashSales = $this->orderModel->getTotalSalesByOrderType('cash');
        $gcashSales = $this->orderModel->getTotalSalesByOrderType('gcash');
        $mayaSales = $this->orderModel->getTotalSalesByOrderType('maya');

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

        // Weekly sales trend (last 7 days)
        $weeklyTrend = $this->db->query("
            SELECT date_created, 
                   SUM(total_payment_due) as daily_total,
                   COUNT(*) as order_count
            FROM orders
            WHERE date_created >= DATE_SUB(?, INTERVAL 6 DAY)
            GROUP BY date_created
            ORDER BY date_created ASC
        ", [$today])->getResultArray();

        return [
            'todaysSales' => floatval($todaysSales['total_sales'] ?? 0),
            'todaysOrderCount' => $todaysOrderCount,
            'todaysItemsSold' => $todaysItemsSold,
            'bakerySales' => floatval($bakerySales['total_revenue'] ?? 0),
            'drinksSales' => floatval($drinksSales['total_revenue'] ?? 0),
            'grocerySales' => floatval($grocerySales['total_revenue'] ?? 0),
            'cashSales' => floatval($cashSales['total_revenue'] ?? 0),
            'gcashSales' => floatval($gcashSales['total_revenue'] ?? 0),
            'mayaSales' => floatval($mayaSales['total_revenue'] ?? 0),
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
            'weeklyTrend' => $weeklyTrend,
            'currentDate' => date('F j, Y'),
            'currentTime' => date('g:i A'),
        ];
    }
}
