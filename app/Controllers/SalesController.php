<?php

namespace App\Controllers;

class SalesController extends BaseController
{
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
    
    public function index()
    {
        $data = $this->getSessionData();
        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Template/notification', $data) .
            view('Sales/Sales', $data) .
            view('Template/Footer', $data);
    }

    public function history()
    {
        $data = $this->getSessionData();
        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Template/notification', $data) .
            view('Sales/SalesHistory', $data) .
            view('Template/Footer', $data);
    }

    public function remittanceHistory()
    {
        $data = $this->getSessionData();
        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Template/notification', $data) .
            view('Sales/RemittanceHistory', $data) .
            view('Template/Footer', $data);
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

    public function printRemittance()
    {
        // Return only the print layout without header/sidebar
        return view('Sales/RemittancePrint');
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

        // Get cashier id - use session if available, otherwise use provided value or default to 1
        $cashierId = session()->get('id') ?? ($data['cashier_id'] ?? 1);
        
        // Verify that cashier exists in users table
        $cashierUser = $this->usersModel->find((int) $cashierId);
        if (empty($cashierUser)) {
            // In development, create a default user if none exists
            log_message('warning', 'Cashier user not found for id: ' . $cashierId . '. Creating default user.');
            
            // Check if ANY user exists
            $anyUser = $this->usersModel->first();
            if (empty($anyUser)) {
                // Create a default user for development
                $defaultUser = [
                    'email' => 'admin@engbakery.com',
                    'firstname' => 'Admin',
                    'middlename' => '',
                    'lastname' => 'User',
                    'employee_type' => 'admin',
                    'username' => 'admin',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'gender' => 'male',
                    'birthdate' => '1990-01-01',
                    'phone_number' => '',
                    'approved' => 1,
                ];
                $cashierId = $this->usersModel->insert($defaultUser);
                log_message('info', 'Created default admin user with ID: ' . $cashierId);
            } else {
                // Use the first existing user
                $cashierId = $anyUser['user_id'];
                log_message('info', 'Using existing user with ID: ' . $cashierId);
            }
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



        log_message('info', 'Denominations data: ' . json_encode($data['denominations'] ?? []));
        // Save remittance denominations (if provided)
        // denominations comes as an object {"1000": {count: 2, denomination: 1000}, ...}
        $denominations = $data['denominations'] ?? [];
        if (is_array($denominations) || is_object($denominations)) {
            foreach ($denominations as $key => $denom) {
                // Handle both object and array formats
                $denomValue = is_array($denom) ? ($denom['denomination'] ?? 0) : (isset($denom->denomination) ? $denom->denomination : 0);
                $countValue = is_array($denom) ? ($denom['count'] ?? $denom['quantity'] ?? 0) : (isset($denom->count) ? $denom->count : (isset($denom->quantity) ? $denom->quantity : 0));
                
                if ($countValue > 0) {
                    log_message('info', 'Processing denomination: ' . $denomValue . ' with count ' . $countValue);
                    $remittanceDenom = [
                        'remittance_id' => $remittanceId,
                        'denomination' => $denomValue,
                        'quantity' => $countValue,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->remittanceDenominationsModel->insert($remittanceDenom);
                    log_message('info', 'Remittance denomination saved: ' . json_encode($remittanceDenom));
                }
            }
        }

        // Save transaction IDs linked to this remittance
        $transactionIds = $data['transaction_ids'] ?? [];
        log_message('info', 'Transaction IDs: ' . json_encode($transactionIds));
        
        if (is_array($transactionIds) && count($transactionIds) > 0) {
            foreach ($transactionIds as $item) {
                if (!empty($item)) {
                    $remittanceItem = [
                        'remittance_id' => $remittanceId,
                        'transaction_id' => $item,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $this->remittanceItemsModel->insert($remittanceItem);
                    log_message('info', 'Remittance item saved: ' . json_encode($remittanceItem));
                }
            }
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Remittance saved successfully.']);
    }

    /**
     * Get sales history from remittance records
     */
    public function getSalesHistory()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        // Default to last 30 days if no dates provided
        if (!$dateTo) {
            $dateTo = date('Y-m-d');
        }
        if (!$dateFrom) {
            $dateFrom = date('Y-m-d', strtotime('-30 days'));
        }

        // Fetch remittance records with cashier info
        $salesHistory = $this->db->query("
            SELECT 
                rd.remittance_id,
                rd.remittance_date as date,
                rd.shift_start,
                rd.shift_end,
                rd.bakery_sales,
                rd.coffee_sales,
                rd.total_sales,
                rd.amount_enclosed as cash_total,
                0 as gcash_total,
                rd.overage_shortage as variance,
                rd.cash_out,
                rd.cashout_reason,
                u.firstname,
                u.lastname,
                CONCAT(u.firstname, ' ', u.lastname) as cashier_name,
                'E n'' G Bakery' as outlet_name
            FROM remittance_details rd
            LEFT JOIN users u ON rd.cashier = u.user_id
            WHERE rd.remittance_date BETWEEN ? AND ?
            ORDER BY rd.remittance_date DESC, rd.shift_start DESC
        ", [$dateFrom, $dateTo])->getResultArray();

        // Calculate order count and grocery sales for each record
        foreach ($salesHistory as &$sale) {
            // Get order count for that date
            $orderCount = $this->db->query("
                SELECT COUNT(*) as count FROM orders 
                WHERE date_created = ?
            ", [$sale['date']])->getRow();
            $sale['order_count'] = $orderCount ? $orderCount->count : 0;

            // Calculate grocery sales (total - bakery - coffee)
            $sale['grocery_sales'] = max(0, ($sale['total_sales'] ?? 0) - ($sale['bakery_sales'] ?? 0) - ($sale['coffee_sales'] ?? 0));
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $salesHistory
        ]);
    }

    /**
     * Get today's sales summary (for Sales History showing today's sales before remittance)
     */
    public function getTodaysSales()
    {
        $today = date('Y-m-d');
        
        // Get sales by category from transactions (returns array with total_revenue)
        $bakeryResult = $this->transactionsModel->getTodaysSaleByCategory('bakery');
        $drinksResult = $this->transactionsModel->getTodaysSaleByCategory('drinks');
        $groceryResult = $this->transactionsModel->getTodaysSaleByCategory('grocery');
        
        // Extract numeric values
        $bakerySales = floatval($bakeryResult['total_revenue'] ?? 0);
        $drinksSales = floatval($drinksResult['total_revenue'] ?? 0);
        $grocerySales = floatval($groceryResult['total_revenue'] ?? 0);
        
        // Get payment method totals
        $cashSales = $this->orderModel->getTodaysSalesByPaymentMethod('cash');
        $gcashSales = $this->orderModel->getTodaysSalesByPaymentMethod('gcash');
        
        // Get order stats
        $totalOrders = $this->orderModel->getTodaysOrderCount();
        
        // Calculate total sales
        $totalSales = $bakerySales + $drinksSales + $grocerySales;

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'date' => $today,
                'bakery_sales' => $bakerySales,
                'coffee_sales' => $drinksSales,
                'grocery_sales' => $grocerySales,
                'total_sales' => $totalSales,
                'cash_total' => $cashSales,
                'gcash_total' => $gcashSales,
                'order_count' => $totalOrders,
                'has_remittance' => $this->checkTodaysRemittance()
            ]
        ]);
    }

    /**
     * Check if today has a remittance already
     */
    private function checkTodaysRemittance()
    {
        $today = date('Y-m-d');
        $remittance = $this->remittanceDetailsModel->where('remittance_date', $today)->first();
        return !empty($remittance);
    }
}