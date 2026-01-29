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

    public function getTodaysSales()
    {
        $sales = $this->transactionsModel->getTodaysSummary();

        echo json_encode([
            'success' => true,
            'data' => $sales
        ]);
    }
}
