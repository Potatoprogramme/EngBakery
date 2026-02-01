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

    public function getRemittanceHistory()
    {
        $remittances = $this->remittanceDetailsModel->getAllRemittances();

        return $this->response->setJSON([
            'success' => true,
            'data' => $remittances
        ]);
    }

    public function getRemittanceDetails($remittanceId)
    {
        $remittanceDetails = $this->remittanceDetailsModel->getRemittanceDetails((int) $remittanceId);
        $remittanceDenominations = $this->remittanceDenominationsModel->getDenominationsBreakdown((int) $remittanceId);


        if ($remittanceDetails === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Remittance not found'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'details' => $remittanceDetails,
                'denominations' => $remittanceDenominations
            ]
        ]);
    }

    public function getTodaysSummary()
    {
        $breadSales = $this->transactionsModel->getTodaysSaleByCategory('bakery');
        $drinksSales = $this->transactionsModel->getTodaysSaleByCategory('drinks');
        $doughSales = $this->transactionsModel->getTodaysSaleByCategory('dough');
        $grocerySales = $this->transactionsModel->getTodaysSaleByCategory('grocery');
        $gCashSales = $this->orderModel->getTotalSalesByOrderType('gcash');
        $mayaSales = $this->orderModel->getTotalSalesByOrderType('maya');
        $creditCardSales = $this->orderModel->getTotalSalesByOrderType('credit_card');
        $debitCardSales = $this->orderModel->getTotalSalesByOrderType('debit_card');
        $todaysTotalOrders = $this->orderModel->getTodaysOrderCount();
        $todaysTotalItemsSold = $this->transactionsModel->getTodaysTotalItemsSold();
        $todaysTransactionIds = $this->transactionsModel->getTodaysTransactionsIds();


        echo json_encode([
            'success' => true,
            'data' => [
                'bread_sales' => $breadSales,
                'drinks_sales' => $drinksSales,
                'dough_sales' => $doughSales,
                'grocery_sales' => $grocerySales,
                'gcash_sales' => $gCashSales,
                'maya_sales' => $mayaSales,
                'credit_card_sales' => $creditCardSales,
                'debit_card_sales' => $debitCardSales,
                'total_orders' => $todaysTotalOrders,
                'total_items_sold' => $todaysTotalItemsSold,
                'transaction_ids' => $todaysTransactionIds
            ]
        ]);
    }

    public function saveRemittance()
    {
        $data = $this->request->getJSON(true);

        // Basic validation: ensure we have payload
        if (empty($data) || !is_array($data)) {
            log_message('warning', 'Invalid or empty remittance payload');
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid remittance data']);
        }

        // Require cashier id and validate it
        $cashierId = $data['cashier_id'] ?? null;
        if (empty($cashierId) || !is_numeric($cashierId)) {
            log_message('warning', 'Missing or invalid cashier_id in remittance payload: ' . json_encode($data));
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Missing or invalid cashier_id']);
        }

        // Verify that cashier exists in users table to avoid FK violation
        $cashierUser = $this->usersModel->find((int) $cashierId);
        if (empty($cashierUser)) {
            log_message('warning', 'Cashier user not found for id: ' . $cashierId);
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Cashier not found']);
        }

        // Prepare remittance details with safe array access
        $variance = $data['variance'] ?? 0;
        $isShort = $variance < 0 ? 1 : 0;
        
        $remittanceDetails = [
            'cashier' => (int) $cashierId,
            'outlet_name' => $data['outlet_name'] ?? '',
            'remittance_date' => $data['date'] ?? date('Y-m-d'),
            'shift_start' => $data['shift_start'] ?? '00:00:00',
            'shift_end' => $data['shift_end'] ?? '00:00:00',
            'amount_enclosed' => $data['amount_enclosed'] ?? 0,
            'total_online_revenue' => $data['total_online_revenue'] ?? 0,
            'cash_out' => $data['cash_out_amount'] ?? 0,
            'cashout_reason' => $data['cash_out_reason'] ?? '',
            'bakery_sales' => $data['bakery_sales'] ?? 0,
            'coffee_sales' => $data['coffee_sales'] ?? 0,
            'grocery_sales' => $data['grocery_sales'] ?? 0,
            'total_sales' => $data['total_sales'] ?? 0,
            'variance_amount' => abs($variance),
            'is_short' => $isShort
        ];

        $remittanceId = $this->remittanceDetailsModel->insert($remittanceDetails);

        if (!$remittanceId) {
            log_message('error', 'Failed to insert remittance details: ' . json_encode($remittanceDetails));
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Failed to save remittance']);
        }

        log_message('info', 'Saving remittance details: ' . json_encode($remittanceDetails));
        log_message('info', 'Remittance saved with ID: ' . $remittanceId);



        log_message('info', json_encode($data['denominations']));
        // Save remittance denominations (if provided)
        foreach ($data['denominations'] as $denom) {
            log_message('info', 'Processing denomination: ' . json_encode($denom) . 'with count ' . ($denom['count'] ?? 0));
            $remittanceDenom = [
                'remittance_id' => $remittanceId,
                'denomination' => $denom['denomination'],
                'quantity' => $denom['quantity'] ?? 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->remittanceDenominationsModel->insert($remittanceDenom);
            log_message('info', 'Remittance denomination saved: ' . json_encode($remittanceDenom));
        }

        log_message('info', json_encode($data['transaction_ids']));
        foreach ($data['transaction_ids'] as $item) {
            
            $remittanceItem = [
                'remittance_id' => $remittanceId,
                'transaction_id' => $item,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->remittanceItemsModel->insert($remittanceItem);
            log_message('info', 'Remittance item saved: ' . json_encode($remittanceItem));
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Remittance saved successfully.']);
    }
}
