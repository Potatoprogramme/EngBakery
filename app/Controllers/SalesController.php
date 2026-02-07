<?php

namespace App\Controllers;

class SalesController extends BaseController
{
    public function index()
    {
        $data = $this->getSessionData();
        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Template/notification', $data) .
            view('Sales/Sales', $data) .
            view('Template/Footer', $data);
    }

    public function history()
    {
        $data = $this->getSessionData();
        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Template/notification', $data) .
            view('Sales/SalesHistory', $data) .
            view('Template/Footer', $data);
    }

    public function remittanceHistory()
    {
        $data = $this->getSessionData();

        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Template/notification', $data) .
            view('Sales/RemittanceHistory', $data) .
            view('Template/Footer', $data);
    }

    public function getRemittanceHistory()
    {
        $employeeType = $this->getSessionData()['employee_type'];
        $userId = $this->getSessionData()['user_id'];

        log_message("info", "Fetching remittance history for user ID: " . $userId . " with role: " . $employeeType);
        
        // If user is staff, filter remittances to show only their own
        if ($employeeType === 'staff' && $userId) {
            $remittances = $this->remittanceDetailsModel->getAllRemittancesById((int) $userId);
        } else {
            // Admin/Owner can see all remittances
            $remittances = $this->remittanceDetailsModel->getAllRemittances();
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $remittances,
            'employeeType' => $employeeType,
            'userId' => $userId
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

    /**
     * Delete a remittance record
     * Only accessible by Admin and Owner
     */
    public function deleteRemittance($remittanceId)
    {
        // Check if user is admin or owner
        $employeeType = $this->getSessionData()['employee_type'];
        
        if (!in_array($employeeType, ['admin', 'owner'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'You do not have permission to delete remittances'
            ]);
        }

        // Check if remittance exists
        $remittance = $this->remittanceDetailsModel->find((int) $remittanceId);
        if (!$remittance) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Remittance not found'
            ]);
        }

        // Delete related records first (denominations and items)
        $this->remittanceDenominationsModel->where('remittance_id', $remittanceId)->delete();
        $this->remittanceItemsModel->where('remittance_id', $remittanceId)->delete();

        // Delete the remittance
        $deleted = $this->remittanceDetailsModel->deleteRemittance((int) $remittanceId);

        if ($deleted) {
            log_message('info', 'Remittance ID ' . $remittanceId . ' deleted by user ' . session()->get('id') . ' (' . $employeeType . ')');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Remittance deleted successfully'
            ]);
        }

        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'message' => 'Failed to delete remittance'
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
        $creditCardSales = $this->orderModel->getTotalSalesByOrderType('credit card');
        $debitCardSales = $this->orderModel->getTotalSalesByOrderType('debit card');
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

    /**
     * Check if a remittance already exists for the given date and shift
     * Called via AJAX to validate before allowing remittance creation
     */
    public function checkExistingRemittance()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $shiftStart = $this->request->getGet('shift_start') ?? '07:00:00';
        $shiftEnd = $this->request->getGet('shift_end') ?? '16:00:00';
        $outletName = $this->request->getGet('outlet_name') ?? '';

        // Ensure time format includes seconds
        if (strlen($shiftStart) === 5) {
            $shiftStart .= ':00';
        }
        if (strlen($shiftEnd) === 5) {
            $shiftEnd .= ':00';
        }

        $existingRemittance = $this->remittanceDetailsModel->getExistingRemittanceByDateAndShift(
            $date,
            $shiftStart,
            $shiftEnd,
            !empty($outletName) ? $outletName : null
        );

        if ($existingRemittance) {
            $cashierName = $existingRemittance['cashier_name'] ?? 'Unknown';
            $existingTime = date('h:i A', strtotime($existingRemittance['remittance_date']));
            
            return $this->response->setJSON([
                'success' => true,
                'exists' => true,
                'existing_remittance' => [
                    'id' => $existingRemittance['remittance_id'],
                    'cashier_name' => $cashierName,
                    'submitted_at' => $existingTime,
                    'date' => $date,
                    'shift' => date('h:i A', strtotime($shiftStart)) . ' - ' . date('h:i A', strtotime($shiftEnd)),
                    'total_sales' => $existingRemittance['total_sales'] ?? 0
                ]
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'exists' => false
        ]);
    }

    /**
     * Get all remittances for a specific date to determine available time slots
     * Called via AJAX to populate shift dropdowns
     */
    public function getRemittancesForDate()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $outletName = $this->request->getGet('outlet_name') ?? '';

        $remittances = $this->remittanceDetailsModel->getRemittancesByDate(
            $date,
            !empty($outletName) ? $outletName : null
        );

        // Extract just the shift times
        $occupiedSlots = [];
        foreach ($remittances as $remittance) {
            $occupiedSlots[] = [
                'start' => substr($remittance['shift_start'], 0, 5), // HH:MM format
                'end' => substr($remittance['shift_end'], 0, 5),
                'cashier_name' => $remittance['cashier_name'] ?? 'Unknown'
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'occupied_slots' => $occupiedSlots
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

        // Extract date and shift information for duplicate check
        $remittanceDate = $data['date'] ?? date('Y-m-d H:i:s');
        $dateOnly = date('Y-m-d', strtotime($remittanceDate));
        $shiftStart = $data['shift_start'] ?? '00:00:00';
        $shiftEnd = $data['shift_end'] ?? '00:00:00';
        $outletName = $data['outlet_name'] ?? '';

        // Check for existing remittance with same date, shift, and outlet
        $existingRemittance = $this->remittanceDetailsModel->getExistingRemittanceByDateAndShift(
            $dateOnly,
            $shiftStart,
            $shiftEnd,
            $outletName
        );

        if ($existingRemittance) {
            $cashierName = $existingRemittance['cashier_name'] ?? 'Unknown';
            $existingTime = date('h:i A', strtotime($existingRemittance['remittance_date']));
            log_message('info', 'Duplicate remittance attempt blocked for date: ' . $dateOnly . ', shift: ' . $shiftStart . ' - ' . $shiftEnd);
            return $this->response->setStatusCode(409)->setJSON([
                'success' => false,
                'message' => 'A remittance for this date and shift already exists.',
                'existing_remittance' => [
                    'id' => $existingRemittance['remittance_id'],
                    'cashier_name' => $cashierName,
                    'submitted_at' => $existingTime,
                    'date' => $dateOnly,
                    'shift' => date('h:i A', strtotime($shiftStart)) . ' - ' . date('h:i A', strtotime($shiftEnd))
                ]
            ]);
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
     * Get sales history from order items (actual sales records)
     * BLAME -> Julius Caesar
     */
    public function getSalesHistory()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        if (empty($dateFrom) || empty($dateTo)) {
            $salesData = $this->transactionsModel->getSalesHistory();
        } else {
            $salesData = $this->transactionsModel->getSalesHistoryByDateRange($dateFrom, $dateTo);
        }

        if (empty($salesData)) {
            return $this->response->setJSON([
                'success' => true,
                'data' => [],
                'message' => 'No sales found for the selected date range'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $salesData
        ]);
    }


    /**
     * Get Sales Details for summary cards
     * Blame -> Julius Caesar
     */
    public function getSummaryDetails()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        if (empty($dateFrom) || empty($dateTo)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Date range is required'
            ]);
        }

        // Get total sales and order count
        $totalSales = $this->orderModel->getTotalSalesByDateRange($dateFrom, $dateTo);
        $totalOrders = $this->orderModel->getOrderCountByDateRange($dateFrom, $dateTo);

        // Get sales by payment method
        $cashSales = $this->orderModel->getSalesByPaymentMethod('cash', $dateFrom, $dateTo);
        $gcashSales = $this->orderModel->getSalesByPaymentMethod('gcash', $dateFrom, $dateTo);

        // Get sales by category
        $bakerySales = $this->orderModel->getSalesByCategory('bakery', $dateFrom, $dateTo);
        $coffeeSales = $this->orderModel->getSalesByCategory('drinks', $dateFrom, $dateTo);
        $grocerySales = $this->orderModel->getSalesByCategory('grocery', $dateFrom, $dateTo);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'total_sales' => floatval($totalSales),
                'total_orders' => intval($totalOrders),
                'cash_sales' => floatval($cashSales),
                'gcash_sales' => floatval($gcashSales),
                'bakery_sales' => floatval($bakerySales),
                'coffee_sales' => floatval($coffeeSales),
                'grocery_sales' => floatval($grocerySales)
            ]
        ]);
    }

    /** 
     * Get Sales Details for Order Id
     * Blame -> Julius Caesar
     */
    public function getTransactionDetails()
    {
        $data = $this->request->getJSON(true);

        $orderId = $data['order_id'] ?? null;

        $transac_details = $this->orderModel->getTransactionDetailsByOrderId($orderId);

        if (empty($transac_details)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }
        $orderItems = $this->orderItemModel->getOrderItems($orderId);

        // Calculate category totals
        $bakerySales = 0;
        $coffeeSales = 0;
        $grocerySales = 0;

        foreach ($orderItems as &$item) {
            // Calculate item total if not already present
            if (!isset($item['total_cost_of_item'])) {
                $item['total_cost_of_item'] = floatval($item['quantity']) * floatval($item['price']);
            }

            // Sum up category totals based on product category
            switch (strtolower($item['category'])) {
                case 'bakery':
                    $bakerySales += floatval($item['total_cost_of_item']);
                    break;
                case 'drinks':
                    $coffeeSales += floatval($item['total_cost_of_item']);
                    break;
                case 'grocery':
                    $grocerySales += floatval($item['total_cost_of_item']);
                    break;
            }
        }

        // Add order items and category totals to transaction details
        $transac_details['order_items'] = $orderItems;
        $transac_details['bakery_sales'] = $bakerySales;
        $transac_details['coffee_sales'] = $coffeeSales;
        $transac_details['grocery_sales'] = $grocerySales;

        return $this->response->setJSON([
            'success' => true,
            'data' => $transac_details
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