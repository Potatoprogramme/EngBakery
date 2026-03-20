<?php

namespace App\Controllers;

use App\Libraries\DistributionQuantityCalculator;

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

        $this->dailyStockItemsModel->consolidateDuplicateProductRows(intval($daily_stock['daily_stock_id']));

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

            // Get remaining stock from the latest earlier inventory date (carryover)
            $carryover = $this->dailyStockItemsModel->getCarryoverStock($today);

            // insert all products into daily stock items model
            if ($productIds && $this->dailyStockItemsModel->insertDailyStockItems($lastInsertId, $productIds, $carryover)) {
                $carryoverCount = count(array_filter($carryover, fn($qty) => $qty > 0));
                $message = 'Today\'s inventory added successfully.';
                if ($carryoverCount > 0) {
                    $message .= " Carried over remaining stock for {$carryoverCount} product(s) from previous day.";
                }

                // Immediate notification: inventory created
                $this->notify('notifyInventoryCreated', $today, count($productIds), $carryoverCount);

                return $this->response->setStatusCode(201)->setJSON([
                    'success' => true,
                    'message' => $message,
                    'carryover_count' => $carryoverCount
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
     * Strict flow: this may only run AFTER today's distribution is completed.
     * Only products from today's distribution records are added to inventory,
    * with carryover from the latest earlier inventory merged into beginning stock.
     */
    public function addInventoryFromDistribution()
    {
        $data = $this->request->getJSON(true);
        $today = date('Y-m-d');

        if (empty($data['time_start']) || empty($data['time_end'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Start time and end time are required.'
            ]);
        }

        if ($this->dailyStockModel->checkInventoryExists($today)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Inventory already exists for today.'
            ]);
        }

        $distributionItems = $this->distributionGroupModel->getGroupsByDate($today);

        // Strict rule: inventory-from-distribution must only run when distribution exists.
        if (!$distributionItems || count($distributionItems) === 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'No distribution records found for today. Complete distribution first before creating inventory from distribution.'
            ]);
        }

        // Flatten grouped distribution data into flat items array
        $flatItems = [];
        foreach ($distributionItems as $group) {
            $groupItems = is_array($group['items'] ?? null) ? $group['items'] : [];
            foreach ($groupItems as $item) {
                $flatItems[] = $item;
            }
        }

        if (empty($flatItems)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'No items found in today\'s distribution. Complete distribution with items first.'
            ]);
        }

        // Raw materials are already deducted at distribution time
        $insertData = [
            'inventory_date' => $today,
            'time_start' => $data['time_start'],
            'time_end' => $data['time_end'],
        ];

        if ($this->dailyStockModel->addTodaysInventory($insertData)) {
            $lastInsertId = $this->dailyStockModel->getInsertID();

            // Get remaining stock from the latest earlier inventory date (carryover)
            $carryover = $this->dailyStockItemsModel->getCarryoverStock($today);

            if ($this->dailyStockItemsModel->insertDailyStockItemsFromDistribution($lastInsertId, $flatItems, $carryover)) {
                $carryoverCount = count(array_filter($carryover, fn($qty) => $qty > 0));
                $message = 'Today\'s inventory created from distribution data successfully.';
                if ($carryoverCount > 0) {
                    $message .= " Carried over remaining stock for {$carryoverCount} product(s) from previous day.";
                }

                // Immediate notification: inventory created from distribution
                $this->notify('notifyInventoryCreated', $today, count($flatItems), $carryoverCount);

                return $this->response->setStatusCode(201)->setJSON([
                    'success' => true,
                    'message' => $message,
                    'items_count' => count($distributionItems),
                    'carryover_count' => $carryoverCount,
                ]);
            } else {
                $this->dailyStockModel->delete($lastInsertId);
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to add stock items from distribution.',
                    'error' => $this->dailyStockItemsModel->errors(),
                ]);
            }
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to create today\'s inventory.'
            ]);
        }
    }

    /**
     * Load distribution data into existing inventory.
     * Updates beginning_stock for products already in inventory,
     * adds new items for products not yet in inventory.
     */
    public function loadFromDistribution()
    {
        $today = date('Y-m-d');

        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();
        if (!$dailyStock) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'No inventory exists for today. Create inventory first.'
            ]);
        }

        $distributionGroups = $this->distributionGroupModel->getGroupsByDate($today);
        $distributionItems = $this->flattenDistributionGroups($distributionGroups);
        if (!$distributionItems || count($distributionItems) === 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'No distribution records found for today. Complete distribution first, then load it into inventory.'
            ]);
        }

        $dailyStockId = $dailyStock['daily_stock_id'];
        $this->dailyStockItemsModel->consolidateDuplicateProductRows(intval($dailyStockId));
        $distPieces = $this->calculateDistributionPieces($distributionItems);

        // Detect if distribution is already loaded by comparing data
        if ($this->isDistributionLoaded($dailyStockId, $distPieces, $today)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Distribution has already been loaded into today\'s inventory.'
            ]);
        }

        $carryover = $this->dailyStockItemsModel->getCarryoverStock($today);
        $updated = 0;
        $added = 0;

        foreach ($distPieces as $productId => $pieces) {
            $carryoverQty = intval($carryover[$productId] ?? 0);
            $existingItem = $this->dailyStockItemsModel
                ->where('daily_stock_id', $dailyStockId)
                ->where('product_id', $productId)
                ->first();

            if ($existingItem) {
                $currentBeginning = intval($existingItem['beginning_stock'] ?? 0);
                $currentDistributionQty = intval($existingItem['distribution_qty'] ?? 0);
                $pullOut = intval($existingItem['pull_out_quantity'] ?? 0);
                $currentEnding = intval($existingItem['ending_stock'] ?? 0);
                $quantitySold = max(0, $currentBeginning - $pullOut - $currentEnding);
                $manualQty = max(0, $currentBeginning - $currentDistributionQty - $carryoverQty);
                $newBeginning = $carryoverQty + $manualQty + $pieces;
                $newEnding = max(0, $newBeginning - $pullOut - $quantitySold);
                $this->dailyStockItemsModel->update($existingItem['item_id'], [
                    'beginning_stock' => $newBeginning,
                    'ending_stock' => $newEnding,
                    'distribution_qty' => $pieces,
                    'is_enabled' => ($newBeginning > 0) ? 1 : 0,
                ]);
                $updated++;
                log_message('info', 'LOAD FROM DISTRIBUTION: Updated Product {product} - added {pieces} pieces, new beginning: {new}', [
                    'product' => $productId,
                    'pieces' => $pieces,
                    'new' => $newBeginning
                ]);
            } else {
                $this->dailyStockItemsModel->insert([
                    'daily_stock_id' => $dailyStockId,
                    'product_id' => $productId,
                    'beginning_stock' => $carryoverQty + $pieces,
                    'pull_out_quantity' => 0,
                    'ending_stock' => $carryoverQty + $pieces,
                    'distribution_qty' => $pieces,
                    'is_enabled' => ($carryoverQty + $pieces > 0) ? 1 : 0,
                ]);
                $added++;
                log_message('info', 'LOAD FROM DISTRIBUTION: Added Product {product} - {pieces} pieces', [
                    'product' => $productId,
                    'pieces' => $pieces
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Distribution loaded: {$updated} product(s) updated, {$added} product(s) added.",
            'updated' => $updated,
            'added' => $added,
        ]);
    }

    /**
     * Get today's distribution items enriched with per-item loaded status.
     * Returns each distribution item with calculated pieces and loaded quantity.
     */
    public function getDistributionItemsWithStatus()
    {
        $today = date('Y-m-d');

        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();
        $dailyStockId = $dailyStock ? intval($dailyStock['daily_stock_id']) : 0;

        $distributionGroups = $this->distributionGroupModel->getGroupsByDate($today);
        $distributionItems = $this->flattenDistributionGroups($distributionGroups);
        if (!$distributionItems || count($distributionItems) === 0) {
            return $this->response->setJSON([
                'success' => true,
                'data' => [],
                'message' => 'No distribution records for today.'
            ]);
        }

        $aggregatedByProduct = [];

        foreach ($distributionItems as $item) {
            $productId = intval($item['product_id']);
            if ($productId <= 0) {
                continue;
            }

            $distributionQty = intval($item['product_qnty'] ?? 0);
            $qtyMode = DistributionQuantityCalculator::normalizeQtyMode($item['qty_mode'] ?? 'batch');
            $product = $this->productModel->find($productId);
            $costData = model('ProductCostModel')->getCostByProductId($productId);
            $metrics = DistributionQuantityCalculator::calculateDistributionMetrics($distributionQty, $qtyMode, $product, $costData);
            $pieces = (int) $metrics['pieces'];

            if (!isset($aggregatedByProduct[$productId])) {
                $aggregatedByProduct[$productId] = [
                    'distribution_id' => $item['distribution_id'],
                    'product_id' => $productId,
                    'product_name' => $item['product_name'] ?? ($product['product_name'] ?? 'N/A'),
                    'category' => $item['category'] ?? ($product['category'] ?? ''),
                    'product_qnty' => 0,
                    'qty_mode' => $qtyMode,
                    'calculated_pieces' => 0,
                ];
            }

            $aggregatedByProduct[$productId]['product_qnty'] += $distributionQty;
            $aggregatedByProduct[$productId]['calculated_pieces'] += $pieces;

            // Prefer the most specific mode when mixed records exist.
            if ($aggregatedByProduct[$productId]['qty_mode'] !== 'pieces' && $qtyMode === 'pieces') {
                $aggregatedByProduct[$productId]['qty_mode'] = 'pieces';
            }

            if ($aggregatedByProduct[$productId]['qty_mode'] === 'batch' && $qtyMode === 'box') {
                $aggregatedByProduct[$productId]['qty_mode'] = 'box';
            }
        }

        $enriched = [];
        foreach ($aggregatedByProduct as $productId => $row) {
            $loadedQty = 0;

            // Check loaded status from inventory
            if ($dailyStockId > 0) {
                $inventoryItem = $this->dailyStockItemsModel
                    ->where('daily_stock_id', $dailyStockId)
                    ->where('product_id', $productId)
                    ->first();
                if ($inventoryItem) {
                    $loadedQty = intval($inventoryItem['distribution_qty'] ?? 0);
                }
            }

            $enriched[] = [
                'distribution_id' => $row['distribution_id'],
                'product_id' => $productId,
                'product_name' => $row['product_name'],
                'category' => $row['category'],
                'product_qnty' => intval($row['product_qnty']),
                'qty_mode' => $row['qty_mode'],
                'calculated_pieces' => intval($row['calculated_pieces']),
                'loaded_qty' => $loadedQty,
                'loaded' => $loadedQty > 0,
            ];
        }

        usort($enriched, static function (array $a, array $b): int {
            $catCompare = strcmp((string) ($a['category'] ?? ''), (string) ($b['category'] ?? ''));
            if ($catCompare !== 0) {
                return $catCompare;
            }

            return strcmp((string) ($a['product_name'] ?? ''), (string) ($b['product_name'] ?? ''));
        });

        return $this->response->setJSON([
            'success' => true,
            'data' => $enriched,
        ]);
    }

    /**
     * Load a single distribution item into today's inventory.
     * Accepts a custom quantity and optional note.
     */
    public function loadSingleDistributionItem()
    {
        $today = date('Y-m-d');

        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();
        if (!$dailyStock) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'No inventory exists for today. Create inventory first.'
            ]);
        }

        $json = $this->request->getJSON();
        $productId = intval($json->product_id ?? 0);
        $quantity = intval($json->quantity ?? 0);
        $expectedPieces = intval($json->expected_pieces ?? 0);
        $note = trim($json->note ?? '');

        if ($productId <= 0 || $quantity <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Product ID and quantity are required.'
            ]);
        }

        // Require note when quantity differs from expected
        if ($expectedPieces > 0 && $quantity !== $expectedPieces && empty($note)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'A note is required when the quantity differs from the distribution amount (' . $expectedPieces . ' pcs).'
            ]);
        }

        $dailyStockId = intval($dailyStock['daily_stock_id']);
        $this->dailyStockItemsModel->consolidateDuplicateProductRows($dailyStockId);
        $carryover = $this->dailyStockItemsModel->getCarryoverStock($today);
        $carryoverQty = intval($carryover[$productId] ?? 0);

        $existingItem = $this->dailyStockItemsModel
            ->where('daily_stock_id', $dailyStockId)
            ->where('product_id', $productId)
            ->first();

        if ($existingItem) {
            $currentBeginning = intval($existingItem['beginning_stock'] ?? 0);
            $currentDistQty = intval($existingItem['distribution_qty'] ?? 0);
            $pullOut = intval($existingItem['pull_out_quantity'] ?? 0);
            $currentEnding = intval($existingItem['ending_stock'] ?? 0);
            $quantitySold = max(0, $currentBeginning - $pullOut - $currentEnding);
            $manualQty = max(0, $currentBeginning - $currentDistQty - $carryoverQty);
            $newDistQty = $quantity;
            $newBeginning = $carryoverQty + $manualQty + $newDistQty;
            $newEnding = max(0, $newBeginning - $pullOut - $quantitySold);
            $existingNotes = trim($existingItem['notes'] ?? '');
            $updatedNotes = $existingNotes;
            if (!empty($note)) {
                $updatedNotes = $existingNotes ? $existingNotes . ' | ' . $note : $note;
            }

            $this->dailyStockItemsModel->update($existingItem['item_id'], [
                'beginning_stock' => $newBeginning,
                'ending_stock' => $newEnding,
                'distribution_qty' => $newDistQty,
                'is_enabled' => ($newBeginning > 0) ? 1 : 0,
                'notes' => $updatedNotes,
            ]);

            log_message('info', 'LOAD SINGLE DISTRIBUTION: Updated Product {product} - added {qty} pcs (expected {expected}), new beginning: {new}', [
                'product' => $productId,
                'qty' => $quantity,
                'expected' => $expectedPieces,
                'new' => $newBeginning,
            ]);
        } else {
            $this->dailyStockItemsModel->insert([
                'daily_stock_id' => $dailyStockId,
                'product_id' => $productId,
                'beginning_stock' => $carryoverQty + $quantity,
                'pull_out_quantity' => 0,
                'ending_stock' => $carryoverQty + $quantity,
                'distribution_qty' => $quantity,
                'is_enabled' => ($carryoverQty + $quantity > 0) ? 1 : 0,
                'notes' => $note,
            ]);

            log_message('info', 'LOAD SINGLE DISTRIBUTION: Added Product {product} - {qty} pcs (expected {expected})', [
                'product' => $productId,
                'qty' => $quantity,
                'expected' => $expectedPieces,
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Distribution item loaded successfully (' . $quantity . ' pcs).',
        ]);
    }

    /**
     * Convert distribution items to [product_id => total_pieces] array.
     */
    private function calculateDistributionPieces(array $distributionItems): array
    {
        $result = [];

        foreach ($distributionItems as $item) {
            $productId = intval($item['product_id']);
            $distributionQty = intval($item['product_qnty'] ?? 0);
            $qtyMode = DistributionQuantityCalculator::normalizeQtyMode($item['qty_mode'] ?? 'batch');
            $product = $this->productModel->find($productId);
            $costData = model('ProductCostModel')->getCostByProductId($productId);
            $metrics = DistributionQuantityCalculator::calculateDistributionMetrics($distributionQty, $qtyMode, $product, $costData);
            $pieces = (int) $metrics['pieces'];

            // Accumulate in case of multiple distribution entries for same product
            $result[$productId] = ($result[$productId] ?? 0) + $pieces;
        }

        return $result;
    }

    /**
     * Detect if distribution data is already reflected in inventory.
     * Compares beginning_stock against (carryover + distribution_pieces).
     * Returns true if ALL distribution products are accounted for.
     */
    private function isDistributionLoaded(int $dailyStockId, array $distPieces, string $today): bool
    {
        if (empty($distPieces)) {
            return false;
        }

        foreach ($distPieces as $productId => $pieces) {
            $existingItem = $this->dailyStockItemsModel
                ->where('daily_stock_id', $dailyStockId)
                ->where('product_id', $productId)
                ->first();

            if (!$existingItem) {
                // Distribution product not in inventory → not loaded
                return false;
            }

            $currentDistributionQty = intval($existingItem['distribution_qty'] ?? 0);
            if ($currentDistributionQty !== intval($pieces)) {
                return false;
            }
        }

        return true;
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

        // Pre-check: block if raw materials are insufficient
        if ($beginningStock > 0) {
            $preview = $this->rawMaterialStockModel->deductForProduction(
                intval($json->product_id),
                $beginningStock,
                true // preview only
            );

            if (!empty($preview['has_insufficient'])) {
                $shortMaterials = array_filter($preview['deductions'], fn($d) => $d['insufficient']);
                $shortNames = array_map(fn($d) => $d['material_name'] . ' (need ' . $d['deduct_amount'] . ' ' . $d['unit'] . ', have ' . $d['before'] . ')', $shortMaterials);

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
            $deductionResult = null;

            if ($beginningStock > 0) {
                $deductionResult = $this->rawMaterialStockModel->deductForProduction(
                    intval($json->product_id),
                    $beginningStock
                );
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Product added to inventory successfully',
                'item_id' => $result,
                'deduction' => $deductionResult
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

        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->first();

        if (!$dailyStock) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'No inventory found for today.'
            ]);
        }

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
        // NOTE: Raw materials are NOT restored here because deductions happen
        // at distribution time. Deleting inventory only removes the inventory
        // record — distribution deductions remain intact.

        if ($this->dailyStockModel->deleteInventoryByDate($today)) {
            // Immediate notification: inventory deleted
            $this->notify('notifyInventoryDeleted', $today);

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

        if (!$json || !isset($json->beginning_stock) || !isset($json->pull_out_quantity)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid input data'
            ]);
        }

        $isAdjustmentMode = isset($json->adjustment_mode) && boolval($json->adjustment_mode);

        if (!$isAdjustmentMode && ($json->beginning_stock < 0 || $json->pull_out_quantity < 0)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Values cannot be negative'
            ]);
        }

        $item = $this->dailyStockItemsModel->find($item_id);

        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory item not found'
            ]);
        }

        // Get old values
        $oldBeginning = intval($item['beginning_stock']);
        $oldPullOut = intval($item['pull_out_quantity']);
        $oldEnding = intval($item['ending_stock']);

        $inputBeginning = intval($json->beginning_stock);
        $inputPullOut = intval($json->pull_out_quantity);
        $inputEnding = isset($json->ending_stock) ? intval($json->ending_stock) : 0;
        $notes = isset($json->notes) ? trim($json->notes) : null;

        if ($isAdjustmentMode) {
            if ($inputPullOut < 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Pull Out only accepts positive additions in adjustment mode.'
                ]);
            }

            $newBeginning = $oldBeginning + $inputBeginning;
            $newPullOut = $oldPullOut + $inputPullOut;
            $newEndingStock = $oldEnding + $inputEnding;

            if ($newBeginning < 0 || $newPullOut < 0 || $newEndingStock < 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Adjustment results cannot go below zero.'
                ]);
            }
        } else {
            $newBeginning = $inputBeginning;
            $newPullOut = $inputPullOut;
        }

        // Validate notes requirement when beginning stock deviates from expected
        $distributionQty = intval($item['distribution_qty'] ?? 0);
        $dailyStock = $this->dailyStockModel->find($item['daily_stock_id']);
        $carryover = $this->dailyStockItemsModel->getCarryoverStock($dailyStock['inventory_date']);
        $carryoverQty = intval($carryover[intval($item['product_id'])] ?? 0);
        $expectedBeginning = $distributionQty + $carryoverQty;

        if ($expectedBeginning > 0 && $newBeginning != $expectedBeginning && empty($notes)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Notes are required when beginning stock differs from expected amount (Distribution: ' . $distributionQty . ' + Carryover: ' . $carryoverQty . ' = ' . $expectedBeginning . ').',
                'notes_required' => true
            ]);
        }

        if (!$isAdjustmentMode) {
            $quantitySold = $oldBeginning - $oldPullOut - $oldEnding;
            if ($quantitySold < 0) {
                $quantitySold = 0;
            }

            $newEndingStock = $newBeginning - $newPullOut - $quantitySold;
            if ($newEndingStock < 0) {
                $newEndingStock = 0;
            }
        }

        $beginningDelta = $newBeginning - $oldBeginning;
        $pullOutDelta = $newPullOut - $oldPullOut;

        $updateData = [
            'beginning_stock' => $newBeginning,
            'pull_out_quantity' => $newPullOut,
            'ending_stock' => $newEndingStock,
            'notes' => $notes
        ];

        // Only beginning stock changes affect raw materials
        // (Pull out has NO effect — products are already made)
        $netRawMaterialChange = $beginningDelta;

        // Pre-check: block if raw materials are insufficient for the increase
        if ($netRawMaterialChange > 0 && isset($item['product_id'])) {
            $preview = $this->rawMaterialStockModel->deductForProduction(
                intval($item['product_id']),
                $netRawMaterialChange,
                true // preview only
            );

            if (!empty($preview['has_insufficient'])) {
                $shortMaterials = array_filter($preview['deductions'], fn($d) => $d['insufficient']);
                $shortNames = array_map(fn($d) => $d['material_name'] . ' (need ' . $d['deduct_amount'] . ' ' . $d['unit'] . ', have ' . $d['before'] . ')', $shortMaterials);

                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Cannot update — insufficient raw material stock for the additional ' . $netRawMaterialChange . ' pieces.',
                    'insufficient_materials' => array_values($shortNames),
                    'preview' => $preview,
                ]);
            }
        }

        if ($this->dailyStockItemsModel->update($item_id, $updateData)) {
            $deductionResult = null;
            $restorationResult = null;

            // Beginning increase → deduct raw materials
            if ($netRawMaterialChange > 0 && isset($item['product_id'])) {
                $deductionResult = $this->rawMaterialStockModel->deductForProduction(
                    intval($item['product_id']),
                    $netRawMaterialChange
                );
            }

            // Beginning decrease → restore raw materials
            if ($netRawMaterialChange < 0 && isset($item['product_id'])) {
                $restorationResult = $this->rawMaterialStockModel->restoreForProduction(
                    intval($item['product_id']),
                    abs($netRawMaterialChange)
                );
            }

            if ($netRawMaterialChange != 0) {
                \App\Libraries\LowStockNotifier::checkAndNotify();
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inventory item updated successfully',
                'data' => $updateData,
                'deduction' => $deductionResult,
                'restoration' => $restorationResult,
                'raw_material_change' => [
                    'beginning_delta' => $beginningDelta,
                    'pullout_delta' => $pullOutDelta,
                    'net_change' => $netRawMaterialChange
                ]
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
        $item = $this->dailyStockItemsModel->find($item_id);

        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory item not found'
            ]);
        }

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

        // Restore raw materials for the beginning stock before deleting
        // Only restore the manually-added portion — distribution & carryover were deducted elsewhere
        $beginningStock = intval($item['beginning_stock'] ?? 0);
        $distributionQty = intval($item['distribution_qty'] ?? 0);
        $carryover = $this->dailyStockItemsModel->getCarryoverStock($inventoryDate);
        $carryoverQty = intval($carryover[intval($item['product_id'])] ?? 0);
        $manualQty = max(0, $beginningStock - $distributionQty - $carryoverQty);

        if ($manualQty > 0 && isset($item['product_id'])) {
            $this->rawMaterialStockModel->restoreForProduction(
                intval($item['product_id']),
                $manualQty
            );
        }

        if ($this->dailyStockItemsModel->delete($item_id)) {
            if ($manualQty > 0) {
                \App\Libraries\LowStockNotifier::checkAndNotify();
            }

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
            $productNames = [];
            $productsDetail = [];

            foreach ($stockItems as $item) {
                $productName = trim((string) ($item['product_name'] ?? 'Unknown Product'));
                $quantitySold = intval($salesDataMap[$item['item_id']]['quantity_sold'] ?? 0);

                $productNames[] = $productName;
                $productsDetail[] = [
                    'product_name' => $productName,
                    'category' => $item['category'] ?? 'uncategorized',
                    'beginning_stock' => intval($item['beginning_stock'] ?? 0),
                    'quantity_sold' => $quantitySold,
                    'pull_out_quantity' => intval($item['pull_out_quantity'] ?? 0),
                    'ending_stock' => intval($item['ending_stock'] ?? 0),
                ];

                $totalBeginning += intval($item['beginning_stock'] ?? 0);
                $totalEnding += intval($item['ending_stock'] ?? 0);
                $totalPullOut += intval($item['pull_out_quantity'] ?? 0);
                // Get sales from transactions table for this item
                $totalSales += floatval($salesDataMap[$item['item_id']]['total_sales'] ?? 0);
            }

            $previewNames = array_slice($productNames, 0, 3);
            $remainingNames = max(0, count($productNames) - count($previewNames));
            $productsPreview = implode(', ', $previewNames);
            if ($remainingNames > 0) {
                $productsPreview .= ' +' . $remainingNames . ' more';
            }

            $inventory['total_items'] = $totalItems;
            $inventory['total_beginning'] = $totalBeginning;
            $inventory['total_ending'] = $totalEnding;
            $inventory['total_pull_out'] = $totalPullOut;
            $inventory['total_sold'] = max(0, $totalBeginning - $totalEnding - $totalPullOut);
            $inventory['total_sales'] = $totalSales;
            $inventory['products_preview'] = $productsPreview;
            $inventory['products_detail'] = $productsDetail;
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
        $distributionGroups = $this->distributionGroupModel->getGroupsByDate($today);
        $distributionItems = $this->flattenDistributionGroups($distributionGroups);

        if (!$distributionItems || count($distributionItems) === 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No distribution records found for today.'
            ]);
        }

        $previewItems = [];
        foreach ($distributionItems as $item) {
            $productId = intval($item['product_id'] ?? 0);
            if ($productId <= 0) {
                continue;
            }

            $product = $this->productModel->find($productId);
            $costData = model('ProductCostModel')->getCostByProductId($productId);
            $metrics = DistributionQuantityCalculator::calculateDistributionMetrics(
                intval($item['product_qnty'] ?? 0),
                $item['qty_mode'] ?? 'batch',
                $product,
                $costData
            );

            $previewItems[] = [
                'product_id' => $productId,
                'product_name' => $item['product_name'] ?? ($product['product_name'] ?? "Product #{$productId}"),
                'quantity' => (int) $metrics['pieces'],
            ];
        }

        $result = $this->rawMaterialStockModel->deductForInventoryBatch($previewItems, true);

        return $this->response->setJSON($result);
    }

    /**
     * Get yesterday's remaining stock only.
     * Returns product-level carryover data for display before creating inventory.
     * GET /Inventory/GetYesterdayRemaining
     */
    public function getYesterdayRemaining()
    {
        $today = date('Y-m-d');
        $carryover = $this->dailyStockItemsModel->getCarryoverStock($today);

        if (empty($carryover)) {
            return $this->response->setJSON([
                'success' => true,
                'data' => [],
                'message' => 'No remaining stock from previous day.'
            ]);
        }

        // Enrich with product names
        $enrichedData = [];
        foreach ($carryover as $productId => $remaining) {
            $product = $this->productModel->find($productId);
            if ($product) {
                $enrichedData[] = [
                    'product_id' => $productId,
                    'product_name' => $product['product_name'],
                    'category' => $product['category'] ?? '',
                    'remaining_stock' => $remaining,
                ];
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $enrichedData,
            'total_products' => count($enrichedData),
            'message' => count($enrichedData) . ' product(s) have remaining stock from previous day.'
        ]);
    }

    private function flattenDistributionGroups(array $groups): array
    {
        $items = [];

        foreach ($groups as $group) {
            $groupItems = is_array($group['items'] ?? null) ? $group['items'] : [];
            foreach ($groupItems as $item) {
                $items[] = $item;
            }
        }

        return $items;
    }

    public function ToggleStockItem($itemId)
    {
        $data = $this->request->getJSON(true);
        $isEnabled = isset($data['is_enabled']) ? (int) $data['is_enabled'] : 0;

        $updateData = [
            'is_enabled' => $isEnabled,
        ];

        $result = $this->dailyStockItemsModel->update($itemId, $updateData);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => $isEnabled ? 'Item enabled successfully.' : 'Item disabled successfully.',
            ]);
        }

        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'message' => 'Failed to update item status.',
        ]);
    }

    /**
     * Manually trigger the scheduled inventory report for a given slot.
     * Owner-only. Used for verifying the email before the scheduled window fires.
     *
     * POST /Inventory/SendReport
     * Body (JSON): { "slot": "am"|"pm", "force": true }
     */
    public function sendInventoryReport()
    {
        $session = $this->getSessionData();

        // Only owners may trigger this
        if (($session['employee_type'] ?? '') !== 'owner') {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message'  => 'Unauthorized. Only owners can trigger inventory reports.',
            ]);
        }

        $json  = $this->request->getJSON(true);
        $slot  = in_array($json['slot'] ?? '', ['am', 'pm']) ? $json['slot'] : 'am';
        $force = !empty($json['force']);

        $today    = date('Y-m-d');
        $flagFile = WRITEPATH . "inventory_report_sent_{$today}_{$slot}.flag";

        if (!$force && file_exists($flagFile)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Report for the '{$slot}' slot was already sent today. Pass \"force\": true to resend.",
                'flag'    => $flagFile,
            ]);
        }

        // Delete flag so the sender is not blocked
        if ($force && file_exists($flagFile)) {
            @unlink($flagFile);
        }

        try {
            // Use reflection to call private method for the force-test path
            $scheduler = new \ReflectionClass(\App\Libraries\AutoReportScheduler::class);
            $method    = $scheduler->getMethod('sendInventoryReport');
            $method->setAccessible(true);
            $sent      = $method->invoke(null, $slot, $today);

            if ($sent) {
                // Re-plant flag so the scheduler won't double-send
                file_put_contents($flagFile, date('Y-m-d H:i:s') . ' (manual)');

                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Inventory report for slot '{$slot}' sent successfully.",
                    'slot'    => $slot,
                    'date'    => $today,
                ]);
            }

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Report was not sent. Check writable/logs for details.',
            ]);

        } catch (\Throwable $e) {
            log_message('error', 'Manual inventory report trigger failed: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Get product recipe with raw materials and quantities
     * GET /Inventory/GetProductRecipe/{productId}
     * 
     * Returns all raw materials needed to produce one unit (piece) of the product
     * with their quantities and units.
     */
    public function GetProductRecipe($productId = null)
    {
        $productId = intval($productId);

        if ($productId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid product ID.'
            ]);
        }

        $product = $this->productModel->find($productId);
        if (!$product) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Product not found.'
            ]);
        }

        $recipeModel = model('ProductRecipeModel');
        $recipe = $recipeModel->getRecipeWithMaterialDetails($productId);

        return $this->response->setJSON([
            'success' => true,
            'product_id' => $productId,
            'product_name' => $product['product_name'] ?? '',
            'category' => $product['category'] ?? '',
            'recipe' => $recipe,
            'recipe_count' => count($recipe)
        ]);
    }
}