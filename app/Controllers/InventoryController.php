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
     * Add today's inventory using distribution data.
     * Only products from the distributions table for today will be added,
     * with product_qnty as the beginning stock.
     */
    public function addInventoryFromDistribution()
    {
        $data = $this->request->getJSON(true);
        $today = date('Y-m-d');

        log_message('info', 'INVENTORY CREATE: Starting inventory creation from distribution for date: {date}', [
            'date' => $today
        ]);

        // Validate time inputs
        if (empty($data['time_start']) || empty($data['time_end'])) {
            log_message('error', 'INVENTORY CREATE: Missing time inputs');
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Start time and end time are required.'
            ]);
        }

        // Check if inventory already exists for today
        if ($this->dailyStockModel->checkInventoryExists($today)) {
            log_message('warning', 'INVENTORY CREATE: Inventory already exists for {date}', ['date' => $today]);
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Inventory already exists for today.'
            ]);
        }

        // Fetch distribution records for today
        log_message('info', 'INVENTORY CREATE: Fetching distribution records for {date}', ['date' => $today]);
        $distributionItems = $this->distributionModel->getDistributionByDate($today);

        // If no distribution records, fall back to adding all products
        if (!$distributionItems || count($distributionItems) === 0) {
            log_message('warning', 'INVENTORY CREATE: No distribution records found, using fallback mode');
            
            // Create the daily stock record
            $insertData = [
                'inventory_date' => $today,
                'time_start' => $data['time_start'],
                'time_end' => $data['time_end'],
            ];

            if ($this->dailyStockModel->addTodaysInventory($insertData)) {
                $lastInsertId = $this->dailyStockModel->getInsertID();
                log_message('info', 'INVENTORY CREATE: Daily stock record created - ID: {id}', ['id' => $lastInsertId]);

                // Fetch ALL products for inventory tracking (fallback mode)
                $productIds = $this->productModel->where('category !=', 'dough')->where('is_disabled', 0)->findColumn("product_id");
                log_message('info', 'INVENTORY CREATE: Adding {count} products (fallback mode)', [
                    'count' => count($productIds)
                ]);

                // Insert all products into daily stock items model
                if ($productIds && $this->dailyStockItemsModel->insertDailyStockItems($lastInsertId, $productIds)) {
                    log_message('info', 'INVENTORY CREATE: Completed successfully (fallback mode)');
                    return $this->response->setStatusCode(201)->setJSON([
                        'success' => true,
                        'message' => 'Today\'s inventory added successfully (no distribution data found, added all products).',
                        'fallback_mode' => true
                    ]);
                } else {
                    // Rollback: delete the daily stock record since items failed
                    log_message('error', 'INVENTORY CREATE: Failed to insert stock items, rolling back');
                    $this->dailyStockModel->delete($lastInsertId);
                    return $this->response->setStatusCode(500)->setJSON([
                        'success' => false,
                        'message' => 'Failed to add daily stock items.',
                        'error' => $this->dailyStockItemsModel->errors(),
                    ]);
                }
            } else {
                log_message('error', 'INVENTORY CREATE: Failed to create daily stock record');
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to create today\'s inventory.'
                ]);
            }
        }

        log_message('info', 'INVENTORY CREATE: Found {count} distribution items', [
            'count' => count($distributionItems)
        ]);

        // Raw materials are already deducted at distribution time — no pre-check needed here
        log_message('info', 'INVENTORY CREATE: Raw materials already deducted at distribution time, no deduction needed');

        // Create the daily stock record
        $insertData = [
            'inventory_date' => $today,
            'time_start' => $data['time_start'],
            'time_end' => $data['time_end'],
        ];

        if ($this->dailyStockModel->addTodaysInventory($insertData)) {
            $lastInsertId = $this->dailyStockModel->getInsertID();
            log_message('info', 'INVENTORY CREATE: Daily stock record created - ID: {id}', ['id' => $lastInsertId]);

            // Insert only products from distribution with their quantities as beginning stock
            if ($this->dailyStockItemsModel->insertDailyStockItemsFromDistribution($lastInsertId, $distributionItems)) {

                // Raw materials are already deducted at distribution time — no deduction here
                log_message('info', 'INVENTORY CREATE: Stock items inserted from distribution successfully');

                $responseData = [
                    'success' => true,
                    'message' => 'Today\'s inventory created from distribution data successfully.',
                    'items_count' => count($distributionItems),
                ];

                log_message('info', 'INVENTORY CREATE: Completed successfully with {count} items', [
                    'count' => count($distributionItems)
                ]);

                return $this->response->setStatusCode(201)->setJSON($responseData);
            } else {
                // Rollback: delete the daily stock record since items failed
                log_message('error', 'INVENTORY CREATE: Failed to insert stock items from distribution, rolling back');
                $this->dailyStockModel->delete($lastInsertId);
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to add stock items from distribution.',
                    'error' => $this->dailyStockItemsModel->errors(),
                ]);
            }
        } else {
            log_message('error', 'INVENTORY CREATE: Failed to create daily stock record');
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to create today\'s inventory.'
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

        log_message('info', 'INVENTORY ADD PRODUCT: Starting');

        if (!$json || !isset($json->product_id)) {
            log_message('error', 'INVENTORY ADD PRODUCT: Product ID is missing');
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Product ID is required'
            ]);
        }

        $today = date('Y-m-d');
        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();

        if (!$dailyStock) {
            log_message('warning', 'INVENTORY ADD PRODUCT: No inventory exists for {date}', ['date' => $today]);
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'No inventory exists for today. Create inventory first.'
            ]);
        }

        $beginningStock = isset($json->beginning_stock) ? intval($json->beginning_stock) : 0;

        log_message('info', 'INVENTORY ADD PRODUCT: Product {product}, Beginning Stock: {stock}', [
            'product' => $json->product_id,
            'stock' => $beginningStock
        ]);

        // ── Pre-check: block if raw materials are insufficient ──
        if ($beginningStock > 0) {
            log_message('info', 'INVENTORY ADD PRODUCT: Checking raw materials for {stock} pieces', [
                'stock' => $beginningStock
            ]);
            $preview = $this->rawMaterialStockModel->deductForProduction(
                intval($json->product_id),
                $beginningStock,
                true // preview only
            );

            if (!empty($preview['has_insufficient'])) {
                $shortMaterials = array_filter($preview['deductions'], fn($d) => $d['insufficient']);
                $shortNames = array_map(fn($d) => $d['material_name'] . ' (need ' . $d['deduct_amount'] . ' ' . $d['unit'] . ', have ' . $d['before'] . ')', $shortMaterials);

                log_message('error', 'INVENTORY ADD PRODUCT: Insufficient raw materials - {materials}', [
                    'materials' => json_encode(array_values($shortNames))
                ]);

                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Cannot add product — insufficient raw material stock.',
                    'insufficient_materials' => array_values($shortNames),
                    'preview' => $preview,
                ]);
            }
        }

        $result = $this->dailyStockItemsModel->addProductToInventory(
            $dailyStock['daily_stock_id'],
            intval($json->product_id),
            $beginningStock
        );

        if ($result) {
            log_message('info', 'INVENTORY ADD PRODUCT: Product added - Item ID: {id}', ['id' => $result]);
            
            $deductionResult = null;

            // All checks passed — actually deduct raw materials
            if ($beginningStock > 0) {
                log_message('info', 'INVENTORY ADD PRODUCT: Deducting {stock} pieces from raw materials', [
                    'stock' => $beginningStock
                ]);
                $deductionResult = $this->rawMaterialStockModel->deductForProduction(
                    intval($json->product_id),
                    $beginningStock
                );
                log_message('info', 'INVENTORY ADD PRODUCT: Raw materials deducted - {result}', [
                    'result' => json_encode($deductionResult)
                ]);
            }

            log_message('info', 'INVENTORY ADD PRODUCT: Completed successfully');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Product added to inventory successfully',
                'item_id' => $result,
                'deduction' => $deductionResult
            ]);
        } else {
            log_message('error', 'INVENTORY ADD PRODUCT: Failed to add product (duplicate or error)');
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Product already exists in inventory or failed to add'
            ]);
        }
    }

    public function deleteTodaysInventory()
    {
        $today = date('Y-m-d');

        log_message('info', 'INVENTORY DELETE: Starting inventory deletion for date: {date}', ['date' => $today]);

        // Check if daily stock exists
        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();

        if (!$dailyStock) {
            log_message('warning', 'INVENTORY DELETE: No inventory found for {date}', ['date' => $today]);
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'No inventory found for today.'
            ]);
        }

        log_message('info', 'INVENTORY DELETE: Found inventory - ID: {id}', ['id' => $dailyStock['daily_stock_id']]);

        // Check if there's a remittance for today
        $remittance = $this->remittanceDetailsModel
            ->where('DATE(remittance_date)', $today)
            ->get()
            ->getRow();

        if ($remittance) {
            log_message('warning', 'INVENTORY DELETE: Blocked - Remittance exists for {date}', ['date' => $today]);
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
            log_message('warning', 'INVENTORY DELETE: Blocked - Transactions exist for {date}', ['date' => $today]);
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Cannot delete inventory. Sales transactions exist for today. Please delete transactions first.'
            ]);
        }

        // Restore raw materials for remaining inventory items before deleting
        // NOTE: We restore based on ACTUAL inventory items (not distribution data)
        // because individual items may have been added, removed, or updated independently.
        log_message('info', 'INVENTORY DELETE: Fetching remaining inventory items to restore raw materials');
        $stockItems = $this->dailyStockItemsModel->where('daily_stock_id', $dailyStock['daily_stock_id'])->findAll();
        if (!empty($stockItems)) {
            log_message('info', 'INVENTORY DELETE: Restoring raw materials for {count} remaining inventory items', [
                'count' => count($stockItems)
            ]);
            foreach ($stockItems as $stockItem) {
                $beginningStock = intval($stockItem['beginning_stock'] ?? 0);
                $productId = intval($stockItem['product_id']);
                if ($beginningStock > 0 && $productId > 0) {
                    log_message('info', 'INVENTORY DELETE: Restoring {pieces} pieces for product {product}', [
                        'pieces' => $beginningStock,
                        'product' => $productId
                    ]);
                    $restoreResult = $this->rawMaterialStockModel->restoreForProduction($productId, $beginningStock);
                    log_message('info', 'INVENTORY DELETE: Raw materials restored - {result}', [
                        'result' => json_encode($restoreResult)
                    ]);
                }
            }
        } else {
            log_message('info', 'INVENTORY DELETE: No remaining inventory items found, no raw materials to restore');
        }

        // Safe to delete - no remittance and no transactions
        if ($this->dailyStockModel->deleteInventoryByDate($today)) {
            log_message('info', 'INVENTORY DELETE: Inventory deleted successfully for {date}', ['date' => $today]);
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Today\'s inventory deleted successfully.'
            ]);
        } else {
            log_message('error', 'INVENTORY DELETE: Database deletion failed for {date}', ['date' => $today]);
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to delete today\'s inventory.'
            ]);
        }
    }

    public function updateStockItem($item_id)
    {
        $json = $this->request->getJSON();

        log_message('info', 'INVENTORY UPDATE ITEM: Starting update for item ID: {id}', ['id' => $item_id]);

        // Validate input
        if (!$json || !isset($json->beginning_stock) || !isset($json->pull_out_quantity)) {
            log_message('error', 'INVENTORY UPDATE ITEM: Invalid input data for item {id}', ['id' => $item_id]);
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid input data'
            ]);
        }

        // Validate that values are non-negative
        if ($json->beginning_stock < 0 || $json->pull_out_quantity < 0) {
            log_message('error', 'INVENTORY UPDATE ITEM: Negative values provided for item {id}', ['id' => $item_id]);
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Values cannot be negative'
            ]);
        }

        // Get the item to verify it exists
        $item = $this->dailyStockItemsModel->find($item_id);

        if (!$item) {
            log_message('error', 'INVENTORY UPDATE ITEM: Item not found - ID: {id}', ['id' => $item_id]);
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

        log_message('info', 'INVENTORY UPDATE ITEM: Item {id} - Old beginning: {old}, New beginning: {new}, Sold: {sold}, New ending: {ending}', [
            'id' => $item_id,
            'old' => $oldBeginning,
            'new' => $newBeginning,
            'sold' => $quantitySold,
            'ending' => $newEndingStock
        ]);

        // Check if the item is a bread
        // if ($this->checkIfBread($item['product_id'])) {
        //     $this->updateBreadStockItem($item_id, $oldBeginning, $newBeginning);
        // }

        // Prepare update data
        $updateData = [
            'beginning_stock' => $newBeginning,
            'pull_out_quantity' => $newPullOut,
            'ending_stock' => $newEndingStock
        ];

        // ── Pre-check: block if raw materials are insufficient for the increase ──
        $stockIncrease = $newBeginning - $oldBeginning;
        log_message('info', 'INVENTORY UPDATE ITEM: Stock delta: {delta} pieces for product {product}', [
            'delta' => $stockIncrease,
            'product' => $item['product_id']
        ]);

        if ($stockIncrease > 0 && isset($item['product_id'])) {
            log_message('info', 'INVENTORY UPDATE ITEM: Checking raw materials for increase of {pieces} pieces', [
                'pieces' => $stockIncrease
            ]);
            $preview = $this->rawMaterialStockModel->deductForProduction(
                intval($item['product_id']),
                $stockIncrease,
                true // preview only
            );

            if (!empty($preview['has_insufficient'])) {
                $shortMaterials = array_filter($preview['deductions'], fn($d) => $d['insufficient']);
                $shortNames = array_map(fn($d) => $d['material_name'] . ' (need ' . $d['deduct_amount'] . ' ' . $d['unit'] . ', have ' . $d['before'] . ')', $shortMaterials);

                log_message('error', 'INVENTORY UPDATE ITEM: Insufficient raw materials - {materials}', [
                    'materials' => json_encode(array_values($shortNames))
                ]);

                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Cannot update — insufficient raw material stock for the additional ' . $stockIncrease . ' pieces.',
                    'insufficient_materials' => array_values($shortNames),
                    'preview' => $preview,
                ]);
            }
        }

        // Update the item
        if ($this->dailyStockItemsModel->update($item_id, $updateData)) {
            log_message('info', 'INVENTORY UPDATE ITEM: Item updated in database - ID: {id}', ['id' => $item_id]);

            $deductionResult = null;
            $restorationResult = null;

            // Beginning stock INCREASED → deduct additional raw materials
            if ($stockIncrease > 0 && isset($item['product_id'])) {
                log_message('info', 'INVENTORY UPDATE ITEM: Deducting {pieces} pieces from raw materials for product {product}', [
                    'pieces' => $stockIncrease,
                    'product' => $item['product_id']
                ]);
                $deductionResult = $this->rawMaterialStockModel->deductForProduction(
                    intval($item['product_id']),
                    $stockIncrease
                );
                log_message('info', 'INVENTORY UPDATE ITEM: Raw materials deducted - {result}', [
                    'result' => json_encode($deductionResult)
                ]);
            }

            // Beginning stock DECREASED → restore raw materials for the reduction
            if ($stockIncrease < 0 && isset($item['product_id'])) {
                $piecesToRestore = abs($stockIncrease);
                log_message('info', 'INVENTORY UPDATE ITEM: Restoring {pieces} pieces to raw materials for product {product}', [
                    'pieces' => $piecesToRestore,
                    'product' => $item['product_id']
                ]);
                $restorationResult = $this->rawMaterialStockModel->restoreForProduction(
                    intval($item['product_id']),
                    $piecesToRestore
                );
                log_message('info', 'INVENTORY UPDATE ITEM: Raw materials restored - {result}', [
                    'result' => json_encode($restorationResult)
                ]);
            }

            if ($stockIncrease == 0) {
                log_message('info', 'INVENTORY UPDATE ITEM: No stock change, no raw material adjustment needed');
            }

            // Trigger low stock notification check
            if ($stockIncrease != 0) {
                \App\Libraries\LowStockNotifier::checkAndNotify();
            }

            log_message('info', 'INVENTORY UPDATE ITEM: Completed successfully for item {id}', ['id' => $item_id]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inventory item updated successfully',
                'data' => $updateData,
                'deduction' => $deductionResult,
                'restoration' => $restorationResult
            ]);
        } else {
            log_message('error', 'INVENTORY UPDATE ITEM: Database update failed for item {id}', ['id' => $item_id]);
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to update inventory item',
                'errors' => $this->dailyStockItemsModel->errors()
            ]);
        }
    }

    /** 
     * Update Stock Item if bread
     */
    // public function updateBreadStockItem($item_id, $oldBeginning, $newBeginning)
    // {
    //     $item = $this->dailyStockItemsModel->find($item_id);
    //     if (!$item) {
    //         return $this->response->setStatusCode(404)->setJSON([
    //             'success' => false,
    //             'message' => 'Inventory item not found'
    //         ]);
    //     }

    //     $productId = $item['product_id'];
    //     $ingredientsList = $this->productRecipeModel->where('product_id', $productId)
    //         ->join('raw_materials rm', 'product_recipe.material_id = rm.material_id')
    //         ->findAll();

    //     foreach ($ingredientsList as $ingredient) {
    //         $initialQty = $this->rawMaterialStockModel->where('material_id', $ingredient['material_id'])->first()['initial_qty'];
    //         if ($initialQty === null || $initialQty <= 0) {
    //             return $this->response->setStatusCode(400)->setJSON([
    //                 'success' => false,
    //                 'message' => 'Insufficient stock for ' . $ingredient['material_name'],
    //             ]);
    //         }
    //     }
    //     // // Update the item with the total quantity sold for that bread
    //     // $updateData = [
    //     //     'quantity_sold' => $breadSales['quantity_sold'] ?? 0,
    //     //     'total_sales' => $breadSales['total_sales'] ?? 0
    //     // ];

    //     // $this->dailyStockItemsModel->update($item_id, $updateData);
    // }

    /**  
     * Check if Bread Item
     */
    // private function checkIfBread($product_id)
    // {
    //     $product = $this->productModel->find($product_id);
    //     return $product && $product['category'] === 'bakery';
    // }
    /**
     * Delete a single inventory item
     */
    public function deleteStockItem($item_id)
    {
        log_message('info', 'INVENTORY DELETE ITEM: Starting for item ID: {id}', ['id' => $item_id]);

        // Get the item to verify it exists
        $item = $this->dailyStockItemsModel->find($item_id);

        if (!$item) {
            log_message('error', 'INVENTORY DELETE ITEM: Item not found - ID: {id}', ['id' => $item_id]);
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory item not found'
            ]);
        }

        log_message('info', 'INVENTORY DELETE ITEM: Found item - Product: {product}, Beginning Stock: {stock}', [
            'product' => $item['product_id'],
            'stock' => $item['beginning_stock']
        ]);

        // Get the daily stock to check the date
        $dailyStock = $this->dailyStockModel->find($item['daily_stock_id']);

        if (!$dailyStock) {
            log_message('error', 'INVENTORY DELETE ITEM: Daily stock record not found - ID: {id}', [
                'id' => $item['daily_stock_id']
            ]);
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory record not found'
            ]);
        }

        $inventoryDate = $dailyStock['inventory_date'];
        log_message('info', 'INVENTORY DELETE ITEM: Inventory date: {date}', ['date' => $inventoryDate]);

        // Check if there's a remittance for this date
        $remittance = $this->remittanceDetailsModel
            ->where('DATE(remittance_date)', $inventoryDate)
            ->get()
            ->getRow();

        if ($remittance) {
            log_message('warning', 'INVENTORY DELETE ITEM: Blocked - Remittance exists for {date}', [
                'date' => $inventoryDate
            ]);
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
            log_message('warning', 'INVENTORY DELETE ITEM: Blocked - Transactions exist for item {id}', [
                'id' => $item_id
            ]);
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Cannot delete item. Sales transactions exist for this product.'
            ]);
        }

        // Restore raw materials for the beginning stock of this item before deleting
        $beginningStock = intval($item['beginning_stock'] ?? 0);
        if ($beginningStock > 0 && isset($item['product_id'])) {
            log_message('info', 'INVENTORY DELETE ITEM: Restoring {stock} pieces to raw materials', [
                'stock' => $beginningStock
            ]);
            $restoreResult = $this->rawMaterialStockModel->restoreForProduction(
                intval($item['product_id']),
                $beginningStock
            );
            log_message('info', 'INVENTORY DELETE ITEM: Raw materials restored - {result}', [
                'result' => json_encode($restoreResult)
            ]);
        }

        // Safe to delete - no remittance and no transactions
        if ($this->dailyStockItemsModel->delete($item_id)) {
            log_message('info', 'INVENTORY DELETE ITEM: Item deleted from database - ID: {id}', ['id' => $item_id]);

            // Check low stock notification
            if ($beginningStock > 0) {
                \App\Libraries\LowStockNotifier::checkAndNotify();
            }

            log_message('info', 'INVENTORY DELETE ITEM: Completed successfully for item {id}', ['id' => $item_id]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inventory item deleted successfully'
            ]);
        } else {
            log_message('error', 'INVENTORY DELETE ITEM: Database deletion failed for item {id}', [
                'id' => $item_id
            ]);
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to delete inventory item'
            ]);
        }
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
            $inventory['total_sold'] = max(0, $totalBeginning - $totalEnding - $totalPullOut);
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

    /**
     * Preview raw material deductions for a product without actually deducting.
     * GET /Inventory/PreviewDeduction?product_id=X&pieces=Y
     */
    public function previewDeduction()
    {
        $productId = intval($this->request->getGet('product_id'));
        $pieces = intval($this->request->getGet('pieces'));

        if ($productId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Product ID is required'
            ]);
        }

        if ($pieces <= 0) {
            // Return empty preview if no pieces specified
            return $this->response->setJSON([
                'success' => true,
                'preview' => true,
                'message' => 'Enter a quantity to see deduction preview',
                'deductions' => []
            ]);
        }

        $result = $this->rawMaterialStockModel->deductForProduction($productId, $pieces, true);

        return $this->response->setJSON($result);
    }

    /**
     * Preview raw material deductions for all products in today's distribution.
     * GET /Inventory/PreviewBatchDeduction
     */
    public function previewBatchDeduction()
    {
        $today = date('Y-m-d');

        // Fetch distribution records for today
        $distributionItems = $this->distributionModel->getDistributionByDate($today);

        if (!$distributionItems || count($distributionItems) === 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No distribution records found for today.'
            ]);
        }

        $result = $this->rawMaterialStockModel->deductForInventoryBatch($distributionItems, true);

        return $this->response->setJSON($result);
    }
}
