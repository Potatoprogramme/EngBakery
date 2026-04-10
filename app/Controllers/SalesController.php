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
            view('Template/Notification', $data) .
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
            view('Template/Notification', $data) .
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
            view('Template/Notification', $data) .
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

            // Remove remittance email flag for the date, so email can be resent if needed
            $remittanceDate = $remittance['remittance_date'] ?? null;
            if ($remittanceDate) {
                $flagFile = WRITEPATH . 'remittance_email_sent_' . date('Y-m-d', strtotime($remittanceDate)) . '.flag';
                if (file_exists($flagFile)) {
                    @unlink($flagFile);
                    log_message('info', 'Deleted remittance email flag file: ' . $flagFile);
                }
            }

            // Immediate notification: remittance deleted
            $deleterName = session()->get('name') ?? 'Unknown';
            $this->notify('notifyRemittanceDeleted', (int) $remittanceId, $deleterName);

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

    public function getTotalSalesByMonth($date = null)
    {
        $date = $date ?? date('Y-m-d');
        $dateFrom = date('Y-m-01', strtotime($date));
        $dateTo = date('Y-m-t', strtotime($date));
        $totalSales = $this->orderModel->getTotalSalesByDateRange($dateFrom, $dateTo);
        return $this->response->setJSON([
            'success' => true,
            'total_sales' => floatval($totalSales)
        ]);
    }

    public function getTodaysSummary()
    {
        $dailyStockId = (int) ($this->request->getGet('daily_stock_id') ?? $this->request->getGet('inventory_id') ?? 0);

        if ($dailyStockId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'daily_stock_id is required.'
            ]);
        }

        $dailyStock = $this->dailyStockModel->getInventoryById($dailyStockId);
        if (empty($dailyStock)) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory not found.'
            ]);
        }

        if (intval($dailyStock['is_closed'] ?? 0) !== 1 || intval($dailyStock['report_sent'] ?? 0) !== 1) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Inventory must be closed and report-sent before remittance totals can be loaded.'
            ]);
        }

        $breadSales = $this->transactionsModel->getSalesByCategoryForInventory('bakery', $dailyStockId);
        $drinksSales = $this->transactionsModel->getSalesByCategoryForInventory('drinks', $dailyStockId);
        $doughSales = $this->transactionsModel->getSalesByCategoryForInventory('dough', $dailyStockId);
        $grocerySales = $this->transactionsModel->getSalesByCategoryForInventory('grocery', $dailyStockId);

        $gCashSales = $this->orderModel->getSalesByPaymentMethodForInventory('gcash', $dailyStockId);
        $mayaSales = $this->orderModel->getSalesByPaymentMethodForInventory('maya', $dailyStockId);
        $creditCardSales = $this->orderModel->getSalesByPaymentMethodForInventory('credit card', $dailyStockId);
        $debitCardSales = $this->orderModel->getSalesByPaymentMethodForInventory('debit card', $dailyStockId);
        $pandaSales = $this->orderModel->getSalesByPaymentMethodForInventory('panda', $dailyStockId);
        $todaysTotalOrders = $this->orderModel->getOrderCountForInventory($dailyStockId);
        $todaysTotalItemsSold = $this->transactionsModel->getTotalItemsSoldForInventory($dailyStockId);
        $todaysTransactionIds = $this->transactionsModel->getTransactionIdsForInventory($dailyStockId);

        // Compute inventory-vs-DB discrepancy and fold it into category sales.
        $stockItems = $this->dailyStockItemsModel->fetchAllStockItems($dailyStockId);
        $salesByItem = $this->transactionsModel->getSalesDataByInventory($dailyStockId);
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

        foreach ($stockItems as $item) {
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

            $discrepancyItemsSold += $discrepancyQty;

            $price = floatval(($item['selling_price_per_piece'] ?? 0) > 0
                ? ($item['selling_price_per_piece'] ?? 0)
                : ($item['selling_price'] ?? 0));

            $discrepancyRevenueByCategory[$category] += ($discrepancyQty * $price);
        }

        $todaysTotalItemsSold += $discrepancyItemsSold;

        $breadSales['total_revenue'] = round(floatval($breadSales['total_revenue'] ?? 0) + $discrepancyRevenueByCategory['bakery'], 2);
        $drinksSales['total_revenue'] = round(floatval($drinksSales['total_revenue'] ?? 0) + $discrepancyRevenueByCategory['drinks'], 2);
        $doughSales['total_revenue'] = round(floatval($doughSales['total_revenue'] ?? 0) + $discrepancyRevenueByCategory['dough'], 2);
        $grocerySales['total_revenue'] = round(floatval($grocerySales['total_revenue'] ?? 0) + $discrepancyRevenueByCategory['grocery'], 2);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'inventory' => $dailyStock,
                'bread_sales' => $breadSales,
                'drinks_sales' => $drinksSales,
                'dough_sales' => $doughSales,
                'grocery_sales' => $grocerySales,
                'gcash_sales' => ['total_revenue' => $gCashSales],
                'maya_sales' => ['total_revenue' => $mayaSales],
                'credit_card_sales' => ['total_revenue' => $creditCardSales],
                'debit_card_sales' => ['total_revenue' => $debitCardSales],
                'panda_sales' => ['total_revenue' => $pandaSales],
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
        $dailyStockId = (int) ($this->request->getGet('daily_stock_id') ?? $this->request->getGet('inventory_id') ?? 0);
        $outletName = $this->request->getGet('outlet_name') ?? '';

        if ($dailyStockId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'exists' => false,
                'message' => 'daily_stock_id is required.'
            ]);
        }

        $dailyStock = $this->dailyStockModel->getInventoryById($dailyStockId);
        $existingRemittance = $this->remittanceDetailsModel->getExistingRemittanceByInventory(
            $dailyStockId,
            !empty($outletName) ? $outletName : null
        );

        if ($existingRemittance) {
            $cashierName = $existingRemittance['cashier_name'] ?? 'Unknown';
            $existingTime = date('h:i A', strtotime($existingRemittance['remittance_date']));
            $inventoryDate = $dailyStock['inventory_date'] ?? date('Y-m-d', strtotime($existingRemittance['remittance_date']));

            return $this->response->setJSON([
                'success' => true,
                'exists' => true,
                'existing_remittance' => [
                    'id' => $existingRemittance['remittance_id'],
                    'cashier_name' => $cashierName,
                    'submitted_at' => $existingTime,
                    'inventory_id' => $dailyStockId,
                    'date' => $inventoryDate,
                    'shift' => trim((string) ($dailyStock['time_start'] ?? '')) . ' - ' . trim((string) ($dailyStock['time_end'] ?? '')),
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
        $inventories = $this->dailyStockModel->getInventoriesByDate($date);
        $eligibleInventories = $this->dailyStockModel->getRemittanceEligibleInventories($date);

        return $this->response->setJSON([
            'success' => true,
            'inventories' => $inventories,
            'eligible_inventories' => $eligibleInventories,
            'required_slots' => array_map(static function (array $inventory): array {
                return [
                    'daily_stock_id' => $inventory['daily_stock_id'],
                    'inventory_date' => $inventory['inventory_date'],
                    'time_start' => $inventory['time_start'] ?? null,
                    'time_end' => $inventory['time_end'] ?? null,
                    'is_closed' => intval($inventory['is_closed'] ?? 0),
                    'report_sent' => intval($inventory['report_sent'] ?? 0),
                    'is_remitted' => intval($inventory['is_remitted'] ?? 0),
                ];
            }, $inventories)
        ]);
    }

    /**
     * Return the shift configuration for the requested date.
     * Called via AJAX so the front-end can render the correct shift buttons.
     */
    public function getShiftConfig()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $dayOfWeek = date('l', strtotime($date)); // 'Sunday', 'Monday', etc.
        $inventories = $this->dailyStockModel->getInventoriesByDate($date);

        return $this->response->setJSON([
            'success' => true,
            'day' => $dayOfWeek,
            'date' => $date,
            'inventories' => $inventories,
            'eligible_inventories' => $this->dailyStockModel->getRemittanceEligibleInventories($date),
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

        $dailyStockId = (int) ($data['daily_stock_id'] ?? $data['inventory_id'] ?? 0);
        $outletName = $data['outlet_name'] ?? '';

        if ($dailyStockId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'daily_stock_id is required.'
            ]);
        }

        $dailyStock = $this->dailyStockModel->getInventoryById($dailyStockId);
        if (empty($dailyStock)) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory not found.'
            ]);
        }

        if (intval($dailyStock['is_closed'] ?? 0) !== 1) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Inventory must be closed before remittance can be saved.'
            ]);
        }

        if (intval($dailyStock['report_sent'] ?? 0) !== 1) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Inventory report must be sent before remittance can be saved.'
            ]);
        }

        if (intval($dailyStock['is_remitted'] ?? 0) === 1) {
            return $this->response->setStatusCode(409)->setJSON([
                'success' => false,
                'message' => 'This inventory has already been remitted.'
            ]);
        }

        // Check for existing remittance with same inventory and outlet
        $existingRemittance = $this->remittanceDetailsModel->getExistingRemittanceByInventory(
            $dailyStockId,
            $outletName
        );

        if ($existingRemittance) {
            $cashierName = $existingRemittance['cashier_name'] ?? 'Unknown';
            $existingTime = date('h:i A', strtotime($existingRemittance['remittance_date']));
            log_message('info', 'Duplicate remittance attempt blocked for inventory: ' . $dailyStockId);
            return $this->response->setStatusCode(409)->setJSON([
                'success' => false,
                'message' => 'A remittance for this inventory already exists.',
                'existing_remittance' => [
                    'id' => $existingRemittance['remittance_id'],
                    'cashier_name' => $cashierName,
                    'submitted_at' => $existingTime,
                    'inventory_id' => $dailyStockId,
                    'date' => $dailyStock['inventory_date'] ?? date('Y-m-d'),
                    'shift' => trim((string) ($dailyStock['time_start'] ?? '')) . ' - ' . trim((string) ($dailyStock['time_end'] ?? ''))
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
            'daily_stock_id' => $dailyStockId,
            'cashier' => (int) $cashierId,
            'outlet_name' => $data['outlet_name'] ?? '',
            'remittance_date' => date('Y-m-d H:i:s'),
            'shift_start' => $dailyStock['time_start'] ?? '00:00:00',
            'shift_end' => $dailyStock['time_end'] ?? '00:00:00',
            'amount_enclosed' => $data['amount_enclosed'] ?? 0,
            'total_online_revenue' => $data['total_online_revenue'] ?? 0,
            'foodpanda_revenue' => $data['foodpanda_revenue'] ?? 0,
            'cash_out' => $data['cash_out_amount'] ?? 0,
            'cashout_reason' => $data['cash_out_reason'] ?? '',
            'bakery_sales' => $data['bakery_sales'] ?? 0,
            'coffee_sales' => $data['coffee_sales'] ?? 0,
            'grocery_sales' => $data['grocery_sales'] ?? 0,
            'total_sales' => $data['total_sales'] ?? 0,
            'variance_amount' => abs($variance),
            'is_short' => $isShort
        ];

        $this->db->transBegin();

        $remittanceId = $this->remittanceDetailsModel->insert($remittanceDetails);

        if (!$remittanceId) {
            $this->db->transRollback();
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
                    if (!$this->remittanceDenominationsModel->insert($remittanceDenom)) {
                        $this->db->transRollback();
                        return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Failed to save remittance denominations']);
                    }
                    log_message('info', 'Remittance denomination saved: ' . json_encode($remittanceDenom));
                }
            }
        }

        // Recompute authoritative transaction IDs from selected inventory.
        // Do not trust client-submitted transaction_ids to avoid cross-inventory leakage.
        $serverTransactionIds = $this->transactionsModel->getTransactionIdsForInventory($dailyStockId);
        $clientTransactionIds = $data['transaction_ids'] ?? [];

        if (is_array($clientTransactionIds) && $clientTransactionIds !== $serverTransactionIds) {
            log_message('warning', 'Client transaction IDs differ from authoritative inventory scope. Using server list. daily_stock_id=' . $dailyStockId . ' client=' . json_encode($clientTransactionIds) . ' server=' . json_encode($serverTransactionIds));
        }

        log_message('info', 'Authoritative transaction IDs: ' . json_encode($serverTransactionIds));

        $reportedTotalSales = floatval($data['total_sales'] ?? 0);

        // Allow save if total sales is > 0 (covers both DB transactions and discrepancies folded into categories).
        // Reject only if no transactions exist AND reported total sales is 0 (no data).
        if ($reportedTotalSales <= 0 && count($serverTransactionIds) === 0) {
            $this->db->transRollback();
            return $this->response->setStatusCode(409)->setJSON([
                'success' => false,
                'message' => 'No sales or discrepancies found for the selected inventory. Please reload the page and select the inventory again.'
            ]);
        }

        if (count($serverTransactionIds) > 0) {
            foreach ($serverTransactionIds as $item) {
                if (!empty($item)) {
                    $remittanceItem = [
                        'remittance_id' => $remittanceId,
                        'transaction_id' => $item,
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    if (!$this->remittanceItemsModel->insert($remittanceItem)) {
                        $this->db->transRollback();
                        return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Failed to save remittance items']);
                    }
                    log_message('info', 'Remittance item saved: ' . json_encode($remittanceItem));
                }
            }
        }

        if (!$this->dailyStockModel->update($dailyStockId, ['is_remitted' => 1])) {
            $this->db->transRollback();
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Failed to mark inventory as remitted']);
        }

        $this->db->transCommit();

        // Send remittance report email for this inventory.
        // The mailer uses an inventory-scoped throttle so new inventories still email once.
        \App\Libraries\DailyRemittanceReport::sendReport(null, false, $dailyStockId);

        // Generate in-app notification if remittance is short
        if ($isShort) {
            $cashierUser = $this->usersModel->find((int) $cashierId);
            $cashierName = $cashierUser ? trim($cashierUser['firstname'] . ' ' . ($cashierUser['middlename'] ?? '') . ' ' . $cashierUser['lastname']) : 'Unknown';
            $this->notify('notifyShortRemittance', (int) $remittanceId, -abs($variance), $cashierName, $dailyStock['inventory_date'] ?? date('Y-m-d'));
        }

        // Immediate notification: remittance filed
        $cashierUser = $cashierUser ?? $this->usersModel->find((int) $cashierId);
        $cashierDisplayName = $cashierUser ? trim($cashierUser['firstname'] . ' ' . $cashierUser['lastname']) : 'Unknown';
        $totalSales = floatval($remittanceDetails['total_sales'] ?? 0);
        $this->notify('notifyRemittanceFiled', (int) $remittanceId, $cashierDisplayName, $totalSales, $dailyStock['inventory_date'] ?? date('Y-m-d'));

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
        $totalTransactions = $this->transactionsModel->getOrderCountByDateRange($dateFrom, $dateTo);

        // Get sales by payment method
        $cashSales = $this->orderModel->getSalesByPaymentMethod('cash', $dateFrom, $dateTo);
        $gcashSales = $this->orderModel->getSalesByPaymentMethod('gcash', $dateFrom, $dateTo);
        $pandaSales = $this->orderModel->getSalesByPaymentMethod('panda', $dateFrom, $dateTo);

        // Get sales by category
        $bakerySales = $this->orderModel->getSalesByCategory('bakery', $dateFrom, $dateTo);
        $coffeeSales = $this->orderModel->getSalesByCategory('drinks', $dateFrom, $dateTo);
        $grocerySales = $this->orderModel->getSalesByCategory('grocery', $dateFrom, $dateTo);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'total_sales' => floatval($totalSales),
                'total_transactions' => intval($totalTransactions),
                'cash_sales' => floatval($cashSales),
                'gcash_sales' => floatval($gcashSales),
                'panda_sales' => floatval($pandaSales),
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
        $foodpandaSales = $this->orderModel->getTodaysSalesByPaymentMethod('panda');

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