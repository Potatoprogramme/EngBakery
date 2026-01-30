<?php
namespace App\Controllers;

class SalesController extends BaseController
{
    public function index()
    {
        return view('Template/Header') .
            view('Template/SideNav') .
            view('Template/notification') .
            view('Sales/Sales') .
            view('Template/Footer');
    }

    public function history()
    {
        return view('Template/Header') .
            view('Template/SideNav') .
            view('Template/notification') .
            view('Sales/SalesHistory') .
            view('Template/Footer');
    }

    public function remittanceHistory()
    {
        return view('Template/Header') .
            view('Template/SideNav') .
            view('Template/notification') .
            view('Sales/RemittanceHistory') .
            view('Template/Footer');
    }

    public function getTodaysSummary()
    {
        $breadSales = $this->transactionsModel->getTodaysSaleByCategory('Bread');
        $drinksSales = $this->transactionsModel->getTodaysSaleByCategory('Drinks');
        $doughSales = $this->transactionsModel->getTodaysSaleByCategory('Dough');
        $grocerySales = $this->transactionsModel->getTodaysSaleByCategory('Grocery');
        $gCashSales = $this->orderModel->getTotalSalesByOrderType('gcash');
        $todaysTotalOrders = $this->orderModel->getTodaysOrderCount();
        $todaysTotalItemsSold = $this->transactionsModel->getTodaysTotalItemsSold();


        echo json_encode([
            'success' => true,
            'data' => [
                'bread_sales' => $breadSales,
                'drinks_sales' => $drinksSales,
                'dough_sales' => $doughSales,
                'grocery_sales' => $grocerySales,
                'gcash_sales' => $gCashSales,
                'total_orders' => $todaysTotalOrders,
                'total_items_sold' => $todaysTotalItemsSold
            ]
        ]);
    }
}
