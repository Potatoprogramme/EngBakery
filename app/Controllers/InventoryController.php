<?php

namespace App\Controllers;

class InventoryController extends BaseController
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

    public function inventory(): string
    {
        $data = $this->getSessionData();
        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Template/Notification', $data) .
            view('Inventory/Inventory', $data) .
            view('Template/Footer', $data);
    }

    public function addInventory(): string
    {
        $data = $this->getSessionData();
        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Inventory/AddInventory', $data) .
            view('Template/Footer', $data);
    }

    public function fetchTodaysInventory()
    {
        $today = date('Y-m-d');
        $daily_stock = $this->dailyStockModel->where('inventory_date', $today)->first();

        // Check if daily_stock exists before accessing it
        if (!$daily_stock) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'data' => [],
                'message' => 'No inventory found for today.',
            ]);
        }

        $daily_stock_items = $this->dailyStockItemsModel->fetchAllStockItems($daily_stock['daily_stock_id']);

        // Get all sales data in a single batch query instead of N+1 queries
        $salesDataMap = [];
        $salesData = $this->transactionsModel->getSalesDataByDate($today);
        foreach ($salesData as $sale) {
            $salesDataMap[$sale['item_id']] = $sale;
        }

        // Enrich stock items with sales data
        foreach ($daily_stock_items as &$item) {
            $item['total_sales'] = $salesDataMap[$item['item_id']]['total_sales'] ?? 0;
            $item['quantity_sold'] = $salesDataMap[$item['item_id']]['quantity_sold'] ?? 0;
        }

        if ($daily_stock_items) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'data' => $daily_stock_items,
                'message' => 'Inventory fetched successfully.'
            ]);
        } else {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => false,
                'message' => 'No inventory items found.',
                'data' => []
            ]);
        }
    }

    public function checkInventoryToday()
    {
        $today = date('Y-m-d');
        $inventory = $this->dailyStockModel->checkInventoryToday($today);

        if ($inventory) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Inventory exists for today.',
                'data' => $inventory
            ]);
        } else {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => false,
                'message' => 'No Inventory found for today.'
            ]);
        }
    }

    public function addTodaysInventory()
    {
        // Implementation for adding today's inventory
        $data = $this->request->getJSON(true);
        $today = date('Y-m-d');
        $insertData = [
            'inventory_date' => $today,
            'time_start' => $data['time_start'],
            'time_end' => $data['time_end'],
        ];


        if ($this->dailyStockModel->checkInventoryExists($today)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Inventory already exists for today.'
            ]);
        }

        if ($this->dailyStockModel->addTodaysInventory($insertData)) {
            $lastInsertId = $this->dailyStockModel->getInsertID();

            // fetch ALL products for inventory tracking
            $productIds = $this->productModel->findColumn("product_id");

            // insert all products into daily stock items model
            if ($productIds && $this->dailyStockItemsModel->insertDailyStockItems($lastInsertId, $productIds)) {
                return $this->response->setStatusCode(201)->setJSON([
                    'success' => true,
                    'message' => 'Today\'s inventory added successfully.'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to add daily stock items.',
                    'error' => $this->dailyStockItemsModel->errors(),
                ]);
            }
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to add today\'s inventory.'
            ]);
        }
    }

    /**
     * Get products not yet in today's inventory (for adding mid-day)
     */
    public function getAvailableProducts()
    {
        $today = date('Y-m-d');
        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();

        if (!$dailyStock) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No inventory exists for today. Create inventory first.',
                'data' => []
            ]);
        }

        $products = $this->dailyStockItemsModel->getProductsNotInInventory($dailyStock['daily_stock_id']);

        return $this->response->setJSON([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Add a product to today's existing inventory
     */
    public function addProductToInventory()
    {
        $json = $this->request->getJSON();

        if (!$json || !isset($json->product_id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Product ID is required'
            ]);
        }

        $today = date('Y-m-d');
        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();

        if (!$dailyStock) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'No inventory exists for today. Create inventory first.'
            ]);
        }

        $beginningStock = isset($json->beginning_stock) ? intval($json->beginning_stock) : 0;

        $result = $this->dailyStockItemsModel->addProductToInventory(
            $dailyStock['daily_stock_id'],
            intval($json->product_id),
            $beginningStock
        );

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Product added to inventory successfully',
                'item_id' => $result
            ]);
        } else {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Product already exists in inventory or failed to add'
            ]);
        }
    }

    public function deleteTodaysInventory()
    {
        $today = date('Y-m-d');
        if ($this->dailyStockModel->deleteInventoryByDate($today)) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Today\'s inventory deleted successfully.'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to delete today\'s inventory.'
            ]);
        }
    }

    public function updateStockItem($item_id)
    {
        $json = $this->request->getJSON();

        // Validate input
        if (!$json || !isset($json->beginning_stock) || !isset($json->pull_out_quantity)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid input data'
            ]);
        }

        // Validate that values are non-negative
        if ($json->beginning_stock < 0 || $json->pull_out_quantity < 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Values cannot be negative'
            ]);
        }

        // Get the item to verify it exists
        $item = $this->dailyStockItemsModel->find($item_id);

        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory item not found'
            ]);
        }

        // Calculate how many were sold (old beginning - old pull_out - old ending)
        $oldBeginning = intval($item['beginning_stock']);
        $oldPullOut = intval($item['pull_out_quantity']);
        $oldEnding = intval($item['ending_stock']);
        $quantitySold = $oldBeginning - $oldPullOut - $oldEnding;
        if ($quantitySold < 0)
            $quantitySold = 0;

        // Calculate new ending stock = new beginning - new pull_out - quantity already sold
        $newBeginning = intval($json->beginning_stock);
        $newPullOut = intval($json->pull_out_quantity);
        $newEndingStock = $newBeginning - $newPullOut - $quantitySold;
        if ($newEndingStock < 0)
            $newEndingStock = 0;

        // Prepare update data
        $updateData = [
            'beginning_stock' => $newBeginning,
            'pull_out_quantity' => $newPullOut,
            'ending_stock' => $newEndingStock
        ];

        // Update the item
        if ($this->dailyStockItemsModel->update($item_id, $updateData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inventory item updated successfully',
                'data' => $updateData
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to update inventory item',
                'errors' => $this->dailyStockItemsModel->errors()
            ]);
        }
    }

    /**
     * Delete a single inventory item
     */
    public function deleteStockItem($item_id)
    {
        // Get the item to verify it exists
        $item = $this->dailyStockItemsModel->find($item_id);

        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory item not found'
            ]);
        }

        // Delete the item
        if ($this->dailyStockItemsModel->delete($item_id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inventory item deleted successfully'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to delete inventory item'
            ]);
        }
    }

    /**
     * Inventory History Page
     */
    public function inventoryHistory(): string
    {
        $data = $this->getSessionData();

        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Template/Notification', $data) .
            view('Inventory/InventoryHistory', $data) .
            view('Template/Footer', $data);
    }

    /**
     * Fetch inventory history with optional date filters
     */
    public function fetchInventoryHistory()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $inventoryHistory = $this->dailyStockModel->getInventoryHistory($dateFrom, $dateTo);

        // Enrich each inventory record with summary data
        foreach ($inventoryHistory as &$inventory) {
            $stockItems = $this->dailyStockItemsModel->fetchAllStockItems($inventory['daily_stock_id']);

            // Get sales data for this specific date from transactions table
            $salesData = $this->transactionsModel->getSalesDataByDate($inventory['inventory_date']);
            $salesDataMap = [];
            foreach ($salesData as $sale) {
                $salesDataMap[$sale['item_id']] = $sale;
            }

            $totalItems = count($stockItems);
            $totalBeginning = 0;
            $totalEnding = 0;
            $totalPullOut = 0;
            $totalSales = 0;

            foreach ($stockItems as $item) {
                $totalBeginning += intval($item['beginning_stock'] ?? 0);
                $totalEnding += intval($item['ending_stock'] ?? 0);
                $totalPullOut += intval($item['pull_out_quantity'] ?? 0);
                // Get sales from transactions table for this item
                $totalSales += floatval($salesDataMap[$item['item_id']]['total_sales'] ?? 0);
            }

            $inventory['total_items'] = $totalItems;
            $inventory['total_beginning'] = $totalBeginning;
            $inventory['total_ending'] = $totalEnding;
            $inventory['total_pull_out'] = $totalPullOut;
            $inventory['total_sold'] = $totalBeginning - $totalEnding - $totalPullOut;
            $inventory['total_sales'] = $totalSales;
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $inventoryHistory
        ]);
    }

    /**
     * Fetch inventory details for a specific date
     */
    public function fetchInventoryByDate()
    {
        $date = $this->request->getGet('date');

        if (!$date) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Date is required'
            ]);
        }

        $dailyStock = $this->dailyStockModel->where('inventory_date', $date)->first();

        if (!$dailyStock) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No inventory found for this date.',
                'data' => []
            ]);
        }

        $stockItems = $this->dailyStockItemsModel->fetchAllStockItems($dailyStock['daily_stock_id']);

        // Get sales data for that date
        $salesData = $this->transactionsModel->getSalesDataByDate($date);
        $salesMap = [];
        foreach ($salesData as $sale) {
            $salesMap[$sale['item_id']] = $sale;
        }

        // Enrich stock items with sales data
        foreach ($stockItems as &$item) {
            $item['total_sales'] = $salesMap[$item['item_id']]['total_sales'] ?? 0;
            $item['quantity_sold'] = $salesMap[$item['item_id']]['quantity_sold'] ?? 0;
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'inventory' => $dailyStock,
                'items' => $stockItems
            ]
        ]);
    }
}
