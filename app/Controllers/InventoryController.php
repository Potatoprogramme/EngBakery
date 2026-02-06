<?php

namespace App\Controllers;

class InventoryController extends BaseController
{
    public function inventory()
    {
        $data = $this->getSessionData();
        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }
        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Template/Notification', $data) .
            view('Inventory/Inventory', $data) .
            view('Template/Footer', $data);
    }

    public function addInventory()
    {
        $data = $this->getSessionData();
        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }
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
            $productIds = $this->productModel->where('category !=', 'dough')->where('is_disabled', 0)->findColumn("product_id");

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
            // Auto-deduct raw materials if beginning stock > 0
            $deductionResult = null;
            if ($beginningStock > 0) {
                $deductionResult = $this->rawMaterialStockModel->deductForProduction(
                    intval($json->product_id),
                    $beginningStock
                );
            }

            $responseData = [
                'success' => true,
                'message' => 'Product added to inventory successfully',
                'item_id' => $result
            ];
            if ($deductionResult) {
                $responseData['raw_material_deduction'] = $deductionResult;
            }
            return $this->response->setJSON($responseData);
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

        // Check if daily stock exists
        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();

        if (!$dailyStock) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'No inventory found for today.'
            ]);
        }

        // Check if there's a remittance for today
        $remittance = $this->remittanceDetailsModel
            ->where('DATE(remittance_date)', $today)
            ->get()
            ->getRow();

        if ($remittance) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Cannot delete inventory. A remittance has already been created for today.'
            ]);
        }

        // Check if there are any transactions for today
        $hasTransactions = $this->transactionsModel
            ->where('DATE(date_created)', $today)
            ->countAllResults() > 0;

        if ($hasTransactions) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Cannot delete inventory. Sales transactions exist for today. Please delete transactions first.'
            ]);
        }

        // Safe to delete - no remittance and no transactions
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

        // Auto-deduct raw materials if beginning stock increased
        $stockIncrease = $newBeginning - $oldBeginning;
        $deductionResult = null;
        if ($stockIncrease > 0) {
            $deductionResult = $this->rawMaterialStockModel->deductForProduction(
                intval($item['product_id']),
                $stockIncrease
            );
        }

        // Prepare update data
        $updateData = [
            'beginning_stock' => $newBeginning,
            'pull_out_quantity' => $newPullOut,
            'ending_stock' => $newEndingStock
        ];

        // Update the item
        if ($this->dailyStockItemsModel->update($item_id, $updateData)) {
            $responseData = [
                'success' => true,
                'message' => 'Inventory item updated successfully',
                'data' => $updateData
            ];
            if ($deductionResult) {
                $responseData['raw_material_deduction'] = $deductionResult;
            }
            return $this->response->setJSON($responseData);
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

        // Get the daily stock to check the date
        $dailyStock = $this->dailyStockModel->find($item['daily_stock_id']);

        if (!$dailyStock) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory record not found'
            ]);
        }

        $inventoryDate = $dailyStock['inventory_date'];

        // Check if there's a remittance for this date
        $remittance = $this->remittanceDetailsModel
            ->where('DATE(remittance_date)', $inventoryDate)
            ->get()
            ->getRow();

        if ($remittance) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Cannot delete item. A remittance has already been created for this inventory.'
            ]);
        }

        // Check if there are any transactions for this item on this date
        $hasTransactions = $this->transactionsModel
            ->where('item_id', $item['item_id'])
            ->where('DATE(date_created)', $inventoryDate)
            ->countAllResults() > 0;

        if ($hasTransactions) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Cannot delete item. Sales transactions exist for this product.'
            ]);
        }

        // Safe to delete - no remittance and no transactions
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
     * Preview raw material deductions for a given product + quantity.
     * Used by the frontend to show a before/after breakdown.
     */
    public function previewDeduction()
    {
        $productId = intval($this->request->getGet('product_id'));
        $quantity  = intval($this->request->getGet('quantity'));

        if ($productId <= 0 || $quantity <= 0) {
            return $this->response->setJSON([
                'success' => true,
                'data'    => []
            ]);
        }

        $db = \Config\Database::connect();

        // Get product cost info
        $productCost = $db->query("SELECT pieces_per_yield, yield_grams FROM product_costs WHERE product_id = ?", [$productId])->getRowArray();
        $piecesPerYield = intval($productCost['pieces_per_yield'] ?? 0);

        $deductions = []; // materialId => deduction amount

        // 1. Direct recipe ingredients
        $recipes = $db->query("SELECT material_id, quantity_needed FROM product_recipe WHERE product_id = ?", [$productId])->getResultArray();

        foreach ($recipes as $recipe) {
            $materialId = intval($recipe['material_id']);
            $qtyNeeded  = floatval($recipe['quantity_needed']);
            $perPiece   = ($piecesPerYield > 0) ? ($qtyNeeded / $piecesPerYield) : $qtyNeeded;
            $deductions[$materialId] = ($deductions[$materialId] ?? 0) + ($perPiece * $quantity);
        }

        // 2. Combined recipe ingredients (dough)
        $combinedRecipes = $db->query("SELECT source_product_id, grams_per_piece FROM product_combined_recipes WHERE product_id = ?", [$productId])->getResultArray();

        foreach ($combinedRecipes as $combined) {
            $sourceProductId = intval($combined['source_product_id']);
            $gramsPerPiece   = floatval($combined['grams_per_piece']);
            $totalGrams      = $gramsPerPiece * $quantity;

            $sourceCost = $db->query("SELECT yield_grams FROM product_costs WHERE product_id = ?", [$sourceProductId])->getRowArray();
            $sourceYield = floatval($sourceCost['yield_grams'] ?? 0);
            if ($sourceYield <= 0) continue;

            $batches = $totalGrams / $sourceYield;
            $sourceRecipes = $db->query("SELECT material_id, quantity_needed FROM product_recipe WHERE product_id = ?", [$sourceProductId])->getResultArray();

            foreach ($sourceRecipes as $srcRecipe) {
                $materialId = intval($srcRecipe['material_id']);
                $deductions[$materialId] = ($deductions[$materialId] ?? 0) + (floatval($srcRecipe['quantity_needed']) * $batches);
            }
        }

        if (empty($deductions)) {
            return $this->response->setJSON([
                'success' => true,
                'data'    => [],
                'message' => 'No recipe found for this product'
            ]);
        }

        // Get current stock + material names for each
        $materialIds = array_keys($deductions);
        $placeholders = implode(',', array_fill(0, count($materialIds), '?'));

        $materials = $db->query("
            SELECT rm.material_id, rm.material_name, rm.unit, 
                   COALESCE(rms.current_quantity, 0) as current_quantity
            FROM raw_materials rm
            LEFT JOIN raw_material_stock rms ON rm.material_id = rms.material_id
            WHERE rm.material_id IN ({$placeholders})
        ", $materialIds)->getResultArray();

        $result = [];
        foreach ($materials as $mat) {
            $mid       = intval($mat['material_id']);
            $deductAmt = round($deductions[$mid], 2);
            $before    = round(floatval($mat['current_quantity']), 2);
            $after     = round(max(0, $before - $deductAmt), 2);

            $result[] = [
                'material_name' => $mat['material_name'],
                'unit'          => $mat['unit'],
                'before'        => $before,
                'deduction'     => $deductAmt,
                'after'         => $after,
            ];
        }

        // Sort by deduction amount descending (most impacted first)
        usort($result, function($a, $b) {
            return $b['deduction'] <=> $a['deduction'];
        });

        return $this->response->setJSON([
            'success' => true,
            'data'    => $result
        ]);
    }

    /**
     * Inventory History Page
     */
    public function inventoryHistory()
    {
        $data = $this->getSessionData();
        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

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