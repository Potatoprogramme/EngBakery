<?php

namespace App\Controllers;

use App\Libraries\DistributionQuantityCalculator;

class InventoryController extends BaseController
{
    private const MANUAL_DRINK_ADJ_PREFIX = 'MANUAL_DRINK_ADJ|';

    /**
     * Keep open-shift semantics as NULL when the schema allows it.
     * Fallback to a safe placeholder for legacy schemas where time_end is NOT NULL.
     */
    private function getOpenShiftTimeEndValue()
    {
        try {
            $db = db_connect();
            $fields = $db->getFieldData('daily_stock');
            foreach ($fields as $field) {
                if (($field->name ?? '') === 'time_end') {
                    $isNullable = property_exists($field, 'nullable') ? (bool) $field->nullable : false;
                    return $isNullable ? null : '00:00:00';
                }
            }
        } catch (\Throwable $e) {
            // Fall through to legacy-safe value below.
        }

        return '00:00:00';
    }

    private function productHasRawMaterialRecipe(int $productId): bool
    {
        if ($productId <= 0) {
            return false;
        }

        try {
            $productRecipeModel = model('ProductRecipeModel');
            $combinedRecipeModel = model('ProductCombinedRecipeModel');

            $directRecipe = $productRecipeModel->getRecipeWithMaterialDetails($productId);
            if (!empty($directRecipe)) {
                return true;
            }

            $combinedRecipes = $combinedRecipeModel->getCombinedRecipesByProductId($productId);
            return !empty($combinedRecipes);
        } catch (\Throwable $e) {
            log_message('error', 'productHasRawMaterialRecipe check failed: ' . $e->getMessage());
            // Fail-safe: keep validation/deduction path active when check itself fails.
            return true;
        }
    }

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
        $daily_stock = $this->dailyStockModel
            ->where('inventory_date', $today)
            ->orderBy('daily_stock_id', 'DESC')
            ->first();

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
        $daily_stock_items = array_values(array_filter($daily_stock_items, static function (array $item) {
            $category = strtolower(trim((string) ($item['category'] ?? '')));
            $beginningStock = intval($item['beginning_stock'] ?? 0);

            // Only filter out zero-stock items for bakery and grocery
            if (in_array($category, ['bakery', 'grocery'], true)) {
                return $beginningStock > 0;
            }

            // Drinks, dough, and everything else always included regardless of stock
            return true;
        }));

        // Get all sales data in a single batch query instead of N+1 queries
        $salesDataMap = [];
        $salesData = $this->transactionsModel->getSalesDataByDate($today);
        foreach ($salesData as $sale) {
            $salesDataMap[$sale['item_id']] = $sale;
        }

        // Enrich stock items with sales data
        foreach ($daily_stock_items as &$item) {
            $dbQtySold = intval($salesDataMap[$item['item_id']]['quantity_sold'] ?? 0);
            $category = strtolower(trim((string) ($item['category'] ?? '')));
            $beginningStock = intval($item['beginning_stock'] ?? 0);
            $pullOutQty = intval($item['pull_out_quantity'] ?? 0);
            $endingStock = intval($item['ending_stock'] ?? 0);
            // Inventory interpretation based on stock fields.
            $inventoryQtySold = max(0, $beginningStock - $pullOutQty - $endingStock);
            if (in_array($category, ['bakery', 'grocery'], true)) {
                // DB qty sold is the floor/source-of-truth for bakery/grocery.
                $effectiveQtySold = max($dbQtySold, $inventoryQtySold);
                $addedQtySold = max(0, $inventoryQtySold - $dbQtySold);
            } elseif ($category === 'drinks') {
                $baseline = $this->transactionsModel->getDrinksBaselineSalesForItem(intval($item['item_id']), self::MANUAL_DRINK_ADJ_PREFIX);
                $dbQtySold = intval($baseline['quantity_sold'] ?? 0);
                $effectiveQtySold = max($dbQtySold, intval($salesDataMap[$item['item_id']]['quantity_sold'] ?? 0));
                $addedQtySold = max(0, $effectiveQtySold - $dbQtySold);
            } else {
                $effectiveQtySold = $dbQtySold;
                $addedQtySold = 0;
            }

            $item['quantity_sold_db'] = $dbQtySold;
            $item['inventory_qty_sold'] = $inventoryQtySold;
            $item['discrepancy'] = $addedQtySold;
            $item['quantity_sold'] = $effectiveQtySold;

            $price = floatval(($item['selling_price_per_piece'] ?? 0) > 0
                ? ($item['selling_price_per_piece'] ?? 0)
                : ($item['selling_price'] ?? 0));
            $item['total_sales'] = $effectiveQtySold * $price;
        }

        if ($daily_stock_items) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'data' => $daily_stock_items,
                'inventory_id' => $daily_stock['daily_stock_id'],
                'is_closed' => $daily_stock['is_closed'] ?? 0,
                'report_sent' => $daily_stock['report_sent'] ?? 0,
                'is_remitted' => $daily_stock['is_remitted'] ?? 0,
                'message' => 'Inventory fetched successfully.'
            ]);
        } else {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'data' => $daily_stock_items,
                'inventory_id' => $daily_stock['daily_stock_id'],
                'is_closed' => $daily_stock['is_closed'] ?? 0,
                'report_sent' => $daily_stock['report_sent'] ?? 0,
                'is_remitted' => $daily_stock['is_remitted'] ?? 0,
                'message' => $daily_stock_items
                    ? 'Inventory fetched successfully.'
                    : 'No inventory items found.',
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

    public function checkActiveInventories()
    {
        $today = date('Y-m-d');
        $db = db_connect();
        $activeInventory = $db->table('daily_stock')
            ->where('inventory_date', $today)
            ->where('time_end IS NULL', null, false)
            ->where('is_closed', 0)
            ->where('report_sent', 0)
            ->where('is_remitted', 0)
            ->get()->getFirstRow();

        return $this->response->setJSON([
            'success' => true,
            'has_active' => !empty($activeInventory),
            'data' => $activeInventory ?? null
        ]);
    }

    public function addTodaysInventory()
    {
        $today = date('Y-m-d');
        $insertData = [
            'inventory_date' => $today,
            'time_start' => date('H:i:s'),
            'time_end' => $this->getOpenShiftTimeEndValue(),
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
            $productIds = $this->productModel
                ->where('category !=', 'dough')
                ->where('is_disabled', 0)
                ->findColumn("product_id");

            // Get remaining stock from the previous inventory ending stock (carryover)
            $carryover = $this->dailyStockItemsModel->getCarryoverStock($today);

            // Only include products that have a carryover quantity greater than 0
            $filteredProductIds = array_values(array_filter($productIds, fn($id) => !empty($carryover[$id]) && $carryover[$id] > 0));
            $filteredCarryover = array_filter($carryover, fn($qty) => $qty > 0);

            if (empty($filteredProductIds)) {
                return $this->response->setStatusCode(201)->setJSON([
                    'success' => true,
                    'message' => 'Today\'s inventory added successfully. No carryover stock to carry over.',
                    'carryover_count' => 0
                ]);
            }

            // insert only products with carryover stock into daily stock items
            if ($this->dailyStockItemsModel->insertDailyStockItems($lastInsertId, $filteredProductIds, $filteredCarryover)) {
                $carryoverCount = count($filteredProductIds);
                $message = 'Today\'s inventory added successfully.';
                $message .= " Carried over remaining stock for {$carryoverCount} product(s) from previous inventory.";

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
        $today = date('Y-m-d');

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
            'time_start' => date('H:i:s'),
            'time_end' => $this->getOpenShiftTimeEndValue(),
        ];

        if ($this->dailyStockModel->addTodaysInventory($insertData)) {
            $lastInsertId = $this->dailyStockModel->getInsertID();

            // Get remaining stock from the previous inventory ending stock (carryover)
            $carryover = $this->dailyStockItemsModel->getCarryoverStock($today);

            if ($this->dailyStockItemsModel->insertDailyStockItemsFromDistribution($lastInsertId, $flatItems, $carryover)) {
                $distributionProductIds = array_values(array_filter(array_unique(array_map(static function ($item) {
                    return intval($item['product_id'] ?? 0);
                }, $flatItems))));

                $drinkProductIds = $this->productModel
                    ->where('category', 'drinks')
                    ->where('is_disabled', 0)
                    ->where('deleted_at', null)
                    ->findColumn('product_id') ?? [];

                if (!empty($distributionProductIds) && !empty($drinkProductIds)) {
                    $drinkProductIds = array_values(array_diff($drinkProductIds, $distributionProductIds));
                }

                if (!$this->dailyStockItemsModel->insertDrinkStockItems($lastInsertId, $drinkProductIds, $carryover)) {
                    $this->dailyStockModel->delete($lastInsertId);
                    return $this->response->setStatusCode(500)->setJSON([
                        'success' => false,
                        'message' => 'Failed to add drink items to inventory.',
                    ]);
                }

                $carryoverCount = count(array_filter($carryover, fn($qty) => $qty > 0));
                $message = 'Today\'s inventory created from distribution data successfully.';
                if ($carryoverCount > 0) {
                    $message .= " Carried over remaining stock for {$carryoverCount} product(s) from previous inventory.";
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

        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->orderBy('daily_stock_id', 'DESC')->first();
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
                $piecesToAdd = max(0, $pieces - $currentDistributionQty);
                $newBeginning = $currentBeginning + $piecesToAdd;
                $newEnding = max(0, $newBeginning - $pullOut - $quantitySold);
                $this->dailyStockItemsModel->update($existingItem['item_id'], [
                    'beginning_stock' => $newBeginning,
                    'ending_stock' => $newEnding,
                    'distribution_qty' => $currentDistributionQty + $piecesToAdd,
                    'is_enabled' => ($newBeginning > 0) ? 1 : 0,
                ]);
                $updated++;
                log_message('info', 'LOAD FROM DISTRIBUTION: Updated Product {product} - added {pieces} pieces, new beginning: {new}', [
                    'product' => $productId,
                    'pieces' => $piecesToAdd,
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

        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->orderBy('daily_stock_id', 'DESC')->first();
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
     * Accepts a custom quantity.
     */
    public function loadSingleDistributionItem()
    {
        $today = date('Y-m-d');

        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->orderBy('daily_stock_id', 'DESC')->first();
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

        if ($productId <= 0 || $quantity <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Product ID and quantity are required.'
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

        $currentDistQty = $existingItem ? intval($existingItem['distribution_qty'] ?? 0) : 0;
        $totalAfter = $currentDistQty + $quantity;

        if ($existingItem) {
            $currentBeginning = intval($existingItem['beginning_stock'] ?? 0);
            $pullOut = intval($existingItem['pull_out_quantity'] ?? 0);
            $currentEnding = intval($existingItem['ending_stock'] ?? 0);
            $quantitySold = max(0, $currentBeginning - $pullOut - $currentEnding);
            $newDistQty = $currentDistQty + $quantity;
            $newBeginning = $currentBeginning + $quantity;
            $newEnding = max(0, $newBeginning - $pullOut - $quantitySold);

            $this->dailyStockItemsModel->update($existingItem['item_id'], [
                'beginning_stock' => $newBeginning,
                'ending_stock' => $newEnding,
                'distribution_qty' => $newDistQty,
                'is_enabled' => ($newBeginning > 0) ? 1 : 0,
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
        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->orderBy('daily_stock_id', 'DESC')->first();

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
        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->orderBy('daily_stock_id', 'DESC')->first();

        if (!$dailyStock) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'No inventory exists for today. Create inventory first.'
            ]);
        }

        $productId = intval($json->product_id ?? 0);
        $beginningStock = isset($json->beginning_stock) ? intval($json->beginning_stock) : 0;
        $hasRawMaterialRecipe = $this->productHasRawMaterialRecipe($productId);

        // Pre-check: block if raw materials are insufficient
        if ($beginningStock > 0 && $hasRawMaterialRecipe) {
            $preview = $this->rawMaterialStockModel->deductForProduction(
                $productId,
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
            $productId,
            $beginningStock
        );

        if ($result) {
            $deductionResult = null;

            if ($beginningStock > 0 && $hasRawMaterialRecipe) {
                $deductionResult = $this->rawMaterialStockModel->deductForProduction(
                    $productId,
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

    public function deleteInventory()
    {
        $today = date('Y-m-d');
        $data = $this->request->getJSON(true);
        $id = $data['inventory_id'] ?? null;

        if (!$id) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Inventory ID is required for deletion.'
            ]);
        }

        $dailyStock = $this->dailyStockModel->where('inventory_date', $today)->orderBy('daily_stock_id', 'DESC')->first();
        if (!$dailyStock) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'No inventory found for today.'
            ]);
        }

        $remittance = $this->remittanceDetailsModel
            ->where('DATE(remittance_date)', $today)
            ->where('daily_stock_id', $id)
            ->get()
            ->getRow();

        if ($remittance) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Cannot delete inventory. A remittance has already been created for today.'
            ]);
        }

        // Check if there are any transactions for today
        $hasTransactions = $this->transactionsModel->join('daily_stock_items dsi', 'dsi.item_id = transactions.item_id')
            ->where('DATE(transactions.date_created)', $today)
            ->where('dsi.daily_stock_id', $id)
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

        if ($this->dailyStockModel->deleteInventory($id)) {
            // Immediate notification: inventory deleted
            $this->notify('notifyInventoryDeleted', $today);

            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Inventory deleted successfully. Product catalog and historical orders were not changed.'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to delete inventory.'
            ]);
        }
    }

    public function updateStockItem($item_id)
    {
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid input data'
            ]);
        }

        $item = $this->dailyStockItemsModel->find($item_id);

        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory item not found'
            ]);
        }

        $productId = intval($item['product_id'] ?? 0);
        $product = $productId > 0 ? $this->productModel->find($productId) : null;
        $productCategory = strtolower(trim((string) ($product['category'] ?? '')));

        // NEW: Handle Store vs Distribute actions
        $action = $json->action ?? null;

        if ($action === 'store') {
            // Store action: Add to inventory (beginning_stock) + create distribution entry
            $productGroupQty = intval($json->product_group_qty ?? 0);
            if ($productGroupQty <= 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Store quantity must be greater than 0'
                ]);
            }

            // Update inventory item's beginning_stock
            $oldBeginning = intval($item['beginning_stock']);
            $newBeginning = $oldBeginning + $productGroupQty;
            $newEnding = intval($item['ending_stock']) + $productGroupQty;

            $this->dailyStockItemsModel->update($item_id, [
                'beginning_stock' => $newBeginning,
                'ending_stock' => $newEnding,
            ]);

            // Create distribution entry under "Store" category
            $dailyStockId = intval($item['daily_stock_id']);
            $this->createOrUpdateDistributionEntryForStore($dailyStockId, $productId, $productGroupQty);

            // Deduct raw materials
            $this->deductRawMaterialsForProduct($productId, $productGroupQty);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Product added to store inventory and distribution',
                'data' => ['item_id' => $item_id]
            ]);
        } else if ($action === 'distribute') {
            // Distribute action: Add to distribution ONLY (not inventory)
            $distributionGroupQty = intval($json->distribution_group_qty ?? 0);
            $distCategoryId = intval($json->distribution_category_id ?? 0);

            if ($distributionGroupQty <= 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Distribution quantity must be greater than 0'
                ]);
            }

            if ($distCategoryId <= 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Distribution category is required'
                ]);
            }

            // Create distribution entry under selected destination category
            $dailyStockId = intval($item['daily_stock_id']);
            $this->createOrUpdateDistributionEntryForDistribute($dailyStockId, $productId, $distributionGroupQty, $distCategoryId);

            // Deduct raw materials
            $this->deductRawMaterialsForProduct($productId, $distributionGroupQty);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Product added to distribution',
                'data' => ['item_id' => $item_id]
            ]);
        }

        if ($productCategory === 'drinks') {
            return $this->updateDrinksStockItem(intval($item_id), $item, $json);
        }

        if (!isset($json->beginning_stock) || !isset($json->pull_out_quantity)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Beginning and pull out values are required.'
            ]);
        }

        $rawAdjustmentMode = $json->adjustment_mode ?? null;
        $clientAdjustmentMode = in_array($rawAdjustmentMode, [true, 1, '1', 'true', 'TRUE'], true);

        $categoryAdjustmentMode = in_array($productCategory, ['bakery', 'grocery'], true);

        // Prefer server-side category rules, and keep client flag as fallback for compatibility.
        $isAdjustmentMode = $categoryAdjustmentMode || $clientAdjustmentMode;

        if ($isAdjustmentMode && !isset($json->ending_stock)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Ending stock is required in adjustment mode.'
            ]);
        }

        if (!$isAdjustmentMode && ($json->beginning_stock < 0 || $json->pull_out_quantity < 0)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Values cannot be negative'
            ]);
        }

        // Get old values
        $oldBeginning = intval($item['beginning_stock']);
        $oldPullOut = intval($item['pull_out_quantity']);
        $oldEnding = intval($item['ending_stock']);

        $inputBeginning = intval($json->beginning_stock);
        $inputPullOut = intval($json->pull_out_quantity);
        $inputEnding = isset($json->ending_stock) ? intval($json->ending_stock) : 0;
        $hasRawMaterialRecipe = $this->productHasRawMaterialRecipe($productId);

        if ($isAdjustmentMode) {
            if ($inputPullOut < 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Pull Out only accepts positive additions in adjustment mode.'
                ]);
            }

            if ($inputEnding < 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Ending stock cannot be negative.'
                ]);
            }

            // In adjustment mode, beginning and pull-out are independent edits.
            // Pull-out changes should affect ending/qty sold, not beginning.
            $newBeginning = $oldBeginning + $inputBeginning;
            $newPullOut = $oldPullOut + $inputPullOut;
            $newEndingStock = $inputEnding;

            if ($newBeginning < 0 || $newPullOut < 0 || $newEndingStock < 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Adjustment results cannot go below zero.'
                ]);
            }

            if ($newBeginning <= 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Beginning stock must be greater than zero. Delete the item if you want to remove it from inventory.'
                ]);
            }

            if ($newEndingStock > $newBeginning) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Ending stock cannot be greater than beginning stock.'
                ]);
            }
        } else {
            $newBeginning = $inputBeginning;
            $newPullOut = $inputPullOut;
        }

        if (!$isAdjustmentMode) {
            // Preserve ending as the current physical count in non-adjustment mode.
            // Pull out changes are reflected by the computed qty sold on fetch/render.
            $newEndingStock = max(0, $oldEnding);
        }

        $beginningDelta = $newBeginning - $oldBeginning;
        $pullOutDelta = $newPullOut - $oldPullOut;

        $updateData = [
            'beginning_stock' => $newBeginning,
            'pull_out_quantity' => $newPullOut,
            'ending_stock' => $newEndingStock,
        ];

        // Add More / Distribute actions already deduct materials separately.
        // Adjust Beginning Quantity edits should not consume raw materials.
        $netRawMaterialChange = $isAdjustmentMode ? 0 : $beginningDelta;

        $deductionResult = null;

        // Perform deduction once (non-preview) to avoid double computation latency.
        if ($netRawMaterialChange > 0 && $productId > 0 && $hasRawMaterialRecipe) {
            $deductionResult = $this->rawMaterialStockModel->deductForProduction(
                $productId,
                $netRawMaterialChange
            );

            if (empty($deductionResult['success'])) {
                $shortMaterials = array_filter($deductionResult['deductions'] ?? [], fn($d) => !empty($d['insufficient']));
                $shortNames = array_map(fn($d) => $d['material_name'] . ' (need ' . $d['deduct_amount'] . ' ' . $d['unit'] . ', have ' . $d['before'] . ')', $shortMaterials);

                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => $deductionResult['message'] ?? ('Cannot update — insufficient raw material stock for the additional ' . $netRawMaterialChange . ' pieces.'),
                    'insufficient_materials' => array_values($shortNames),
                    'preview' => $deductionResult,
                ]);
            }
        }

        if ($this->dailyStockItemsModel->update($item_id, $updateData)) {
            $restorationResult = null;

            // Beginning decrease → restore raw materials
            if ($netRawMaterialChange < 0 && $productId > 0 && $hasRawMaterialRecipe) {
                $restorationResult = $this->rawMaterialStockModel->restoreForProduction(
                    $productId,
                    abs($netRawMaterialChange)
                );
            }

            if ($netRawMaterialChange != 0 && $hasRawMaterialRecipe) {
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
            // Roll back raw material deduction if inventory row update fails.
            if ($netRawMaterialChange > 0 && !empty($deductionResult['success']) && $productId > 0 && $hasRawMaterialRecipe) {
                $this->rawMaterialStockModel->restoreForProduction(
                    $productId,
                    $netRawMaterialChange
                );
            }

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to update inventory item',
                'errors' => $this->dailyStockItemsModel->errors()
            ]);
        }
    }

    private function buildManualDrinkAdjustmentMarker(array $item): string
    {
        $dailyStockId = intval($item['daily_stock_id'] ?? 0);
        $itemId = intval($item['item_id'] ?? 0);

        return self::MANUAL_DRINK_ADJ_PREFIX . $dailyStockId . '|' . $itemId;
    }

    private function resolveDrinkSellingPrice(int $productId): float
    {
        $costData = $this->productCostModel
            ->where('product_id', $productId)
            ->orderBy('product_cost_id', 'DESC')
            ->first();

        if (empty($costData)) {
            return 0.0;
        }

        $pricePerPiece = floatval($costData['selling_price_per_piece'] ?? 0);
        $price = $pricePerPiece > 0
            ? $pricePerPiece
            : floatval($costData['selling_price'] ?? 0);

        return max(0, $price);
    }

    private function rollbackDrinkIngredientAdjustment(int $productId, int $adjustmentDelta): void
    {
        if ($adjustmentDelta === 0 || $productId <= 0) {
            return;
        }

        try {
            if ($adjustmentDelta > 0) {
                $this->rawMaterialStockModel->restoreForProduction($productId, $adjustmentDelta);
                return;
            }

            $this->rawMaterialStockModel->deductForProduction($productId, abs($adjustmentDelta));
        } catch (\Throwable $e) {
            log_message('error', 'rollbackDrinkIngredientAdjustment failed: ' . $e->getMessage());
        }
    }

    private function updateDrinksStockItem(int $itemId, array $item, object $json)
    {
        $targetQtyRaw = $json->quantity_sold_target ?? null;
        if ($targetQtyRaw === null || !is_numeric($targetQtyRaw)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Target quantity sold is required for drinks updates.'
            ]);
        }

        $targetQty = intval($targetQtyRaw);
        if ($targetQty < 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Quantity sold cannot be negative.'
            ]);
        }

        $dailyStockId = intval($item['daily_stock_id'] ?? 0);
        $dailyStock = $dailyStockId > 0 ? $this->dailyStockModel->find($dailyStockId) : null;
        if (empty($dailyStock)) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory record not found for this item.'
            ]);
        }

        $inventoryDate = (string) ($dailyStock['inventory_date'] ?? date('Y-m-d'));
        $inventoryTime = trim((string) ($dailyStock['time_end'] ?? ''));
        if ($inventoryTime === '' || $inventoryTime === '00:00:00') {
            $inventoryTime = date('H:i:s');
        }

        $productId = intval($item['product_id'] ?? 0);
        $sellingPrice = $this->resolveDrinkSellingPrice($productId);
        $marker = $this->buildManualDrinkAdjustmentMarker($item);

        $baseline = $this->transactionsModel->getDrinksBaselineSalesForItem($itemId, self::MANUAL_DRINK_ADJ_PREFIX);
        $existingAdjustment = $this->transactionsModel->getManualDrinkAdjustmentForItemByMarker($itemId, $marker);
        $existingAdjustmentQty = intval($existingAdjustment['quantity_sold'] ?? 0);
        $baselineQty = intval($baseline['quantity_sold'] ?? 0);

        if ($targetQty < $baselineQty) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Target quantity sold cannot be below DB source-of-truth (' . $baselineQty . ').'
            ]);
        }

        $desiredAdjustmentQty = $targetQty - $baselineQty;
        $adjustmentDelta = $desiredAdjustmentQty - $existingAdjustmentQty;

        $hasRawMaterialRecipe = $this->productHasRawMaterialRecipe($productId);
        if ($adjustmentDelta !== 0 && $hasRawMaterialRecipe) {
            if ($adjustmentDelta > 0) {
                $deductResult = $this->rawMaterialStockModel->deductForProduction($productId, $adjustmentDelta);
                if (empty($deductResult['success'])) {
                    $shortMaterials = array_filter($deductResult['deductions'] ?? [], fn($d) => !empty($d['insufficient']));
                    $shortNames = array_map(fn($d) => $d['material_name'] . ' (need ' . $d['deduct_amount'] . ' ' . $d['unit'] . ', have ' . $d['before'] . ')', $shortMaterials);

                    return $this->response->setStatusCode(400)->setJSON([
                        'success' => false,
                        'message' => $deductResult['message'] ?? 'Cannot apply drinks quantity increase due to insufficient ingredients.',
                        'insufficient_materials' => array_values($shortNames),
                        'preview' => $deductResult,
                    ]);
                }
            } else {
                $restoreResult = $this->rawMaterialStockModel->restoreForProduction($productId, abs($adjustmentDelta));
                if (empty($restoreResult['success'])) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'success' => false,
                        'message' => $restoreResult['message'] ?? 'Cannot apply drinks quantity decrease due to raw-material restore failure.',
                        'preview' => $restoreResult,
                    ]);
                }
            }
        }

        $sessionData = $this->getSessionData();
        $cashierId = intval($sessionData['user_id'] ?? 0);
        $cashierName = trim((string) ($sessionData['name'] ?? 'System'));
        if ($cashierName === '') {
            $cashierName = 'System';
        }

        $signedTotalSales = round($sellingPrice * $desiredAdjustmentQty, 2);
        $existingOrder = $this->orderModel->findManualDrinkAdjustmentOrder($marker);
        $orderId = intval($existingOrder['order_id'] ?? 0);
        $paymentMethod = 'cash';

        try {
            $this->db->transBegin();

            if ($desiredAdjustmentQty === 0) {
                if ($orderId > 0) {
                    $deletedAt = date('Y-m-d H:i:s');

                    $this->transactionsModel
                        ->builder()
                        ->where('order_id', $orderId)
                        ->where('item_id', $itemId)
                        ->update(['deleted_at' => $deletedAt]);

                    $this->orderModel->update($orderId, [
                        'total_payment_due' => 0,
                        'amount_received' => 0,
                        'amount_change' => 0,
                        'voided_at' => $deletedAt,
                        'voided_by' => $cashierId > 0 ? strval($cashierId) : 'system',
                    ]);
                }
            } else {
                $orderPayload = [
                    'total_payment_due' => $signedTotalSales,
                    'amount_received' => $signedTotalSales,
                    'amount_change' => 0,
                    'payment_method' => $paymentMethod,
                    'distributed_note' => $marker,
                    'date_created' => $inventoryDate,
                    'time_created' => $inventoryTime,
                    'cashier_id' => $cashierId,
                    'cashier_name' => $cashierName,
                ];

                if ($orderId > 0) {
                    if (!$this->orderModel->updateManualDrinkAdjustmentOrder($orderId, $orderPayload)) {
                        throw new \RuntimeException('Failed to update drinks adjustment order.');
                    }
                } else {
                    $orderId = intval($this->orderModel->createManualDrinkAdjustmentOrder($orderPayload));
                    if ($orderId <= 0) {
                        throw new \RuntimeException('Failed to create drinks adjustment order.');
                    }
                }

                $orderItemData = [
                    'order_id' => $orderId,
                    'product_id' => $productId,
                    'amount' => $desiredAdjustmentQty,
                    'cost_per_item' => $sellingPrice,
                    'total_cost_of_item' => $signedTotalSales,
                    'date_created' => $inventoryDate,
                    'time_created' => $inventoryTime,
                ];

                $existingOrderItem = $this->orderItemModel
                    ->where('order_id', $orderId)
                    ->where('product_id', $productId)
                    ->first();

                if (!empty($existingOrderItem['order_item_id'])) {
                    if (!$this->orderItemModel->update(intval($existingOrderItem['order_item_id']), $orderItemData)) {
                        throw new \RuntimeException('Failed to update drinks adjustment order item.');
                    }
                } else {
                    if (!$this->orderItemModel->insert($orderItemData)) {
                        throw new \RuntimeException('Failed to insert drinks adjustment order item.');
                    }
                }

                $existingTransaction = $this->transactionsModel
                    ->where('order_id', $orderId)
                    ->where('item_id', $itemId)
                    ->orderBy('sale_id', 'DESC')
                    ->first();

                $transactionData = [
                    'item_id' => $itemId,
                    'order_id' => $orderId,
                    'quantity_sold' => $desiredAdjustmentQty,
                    'total_sales' => $signedTotalSales,
                    'date_created' => $inventoryDate,
                    'time_created' => $inventoryTime,
                    'deleted_at' => null,
                ];

                if (!empty($existingTransaction['sale_id'])) {
                    if (!$this->transactionsModel->update(intval($existingTransaction['sale_id']), $transactionData)) {
                        throw new \RuntimeException('Failed to update drinks adjustment transaction.');
                    }
                } else {
                    if (!$this->transactionsModel->insert($transactionData)) {
                        throw new \RuntimeException('Failed to insert drinks adjustment transaction.');
                    }
                }
            }

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('Drinks adjustment transaction failed.');
            }

            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            if ($adjustmentDelta !== 0 && $hasRawMaterialRecipe) {
                $this->rollbackDrinkIngredientAdjustment($productId, $adjustmentDelta);
            }

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }

        if ($adjustmentDelta !== 0 && $hasRawMaterialRecipe) {
            \App\Libraries\LowStockNotifier::checkAndNotify();
        }

        $netSales = $this->transactionsModel->getNetSalesForItem($itemId);
        $isPostRemit = intval($dailyStock['is_remitted'] ?? 0) === 1;

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Drinks quantity updated successfully.',
            'data' => [
                'item_id' => $itemId,
                'baseline_qty_sold' => $baselineQty,
                'manual_adjustment_qty' => $desiredAdjustmentQty,
                'discrepancy_qty' => max(0, $desiredAdjustmentQty),
                'target_qty_sold' => $targetQty,
                'effective_qty_sold' => intval($netSales['quantity_sold'] ?? 0),
                'adjustment_delta' => $adjustmentDelta,
                'is_post_remit' => $isPostRemit ? 1 : 0,
            ],
        ]);
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
                'message' => 'Inventory item deleted successfully. Product catalog and historical order quantities remain unchanged.'
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
            $totalSold = 0;
            $totalSales = 0;
            $productNames = [];
            $productsDetail = [];

            foreach ($stockItems as $item) {
                $productName = trim((string) ($item['product_name'] ?? 'Unknown Product'));
                $dbQtySold = intval($salesDataMap[$item['item_id']]['quantity_sold'] ?? 0);
                $category = strtolower(trim((string) ($item['category'] ?? '')));
                $beginningStock = intval($item['beginning_stock'] ?? 0);
                $pullOutQty = intval($item['pull_out_quantity'] ?? 0);
                $endingStock = intval($item['ending_stock'] ?? 0);
                $inventoryQtySold = max(0, $beginningStock - $pullOutQty - $endingStock);
                $quantitySold = in_array($category, ['bakery', 'grocery'], true)
                    ? max($dbQtySold, $inventoryQtySold)
                    : $dbQtySold;

                $price = floatval(($item['selling_price_per_piece'] ?? 0) > 0
                    ? ($item['selling_price_per_piece'] ?? 0)
                    : ($item['selling_price'] ?? 0));
                $itemTotalSales = $quantitySold * $price;

                $productNames[] = $productName;
                $productsDetail[] = [
                    'product_name' => $productName,
                    'category' => $item['category'] ?? 'uncategorized',
                    'beginning_stock' => $beginningStock,
                    'quantity_sold' => $quantitySold,
                    'pull_out_quantity' => $pullOutQty,
                    'ending_stock' => $endingStock,
                ];

                $totalBeginning += $beginningStock;
                $totalEnding += $endingStock;
                $totalPullOut += $pullOutQty;
                $totalSold += $quantitySold;
                $totalSales += $itemTotalSales;
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
            $inventory['total_sold'] = $totalSold;
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

        $dailyStock = $this->dailyStockModel
            ->where('report_sent', 0)
            ->where('inventory_date', $date)
            ->orderBy('daily_stock_id', 'DESC')
            ->first();

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
            $dbQtySold = intval($salesMap[$item['item_id']]['quantity_sold'] ?? 0);
            $category = strtolower(trim((string) ($item['category'] ?? '')));
            $beginningStock = intval($item['beginning_stock'] ?? 0);
            $pullOutQty = intval($item['pull_out_quantity'] ?? 0);
            $endingStock = intval($item['ending_stock'] ?? 0);
            $inventoryQtySold = max(0, $beginningStock - $pullOutQty - $endingStock);

            $effectiveQtySold = in_array($category, ['bakery', 'grocery'], true)
                ? max($dbQtySold, $inventoryQtySold)
                : $dbQtySold;

            $price = floatval(($item['selling_price_per_piece'] ?? 0) > 0
                ? ($item['selling_price_per_piece'] ?? 0)
                : ($item['selling_price'] ?? 0));

            $item['quantity_sold'] = $effectiveQtySold;
            $item['total_sales'] = $effectiveQtySold * $price;
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
     * Get previous inventory's remaining stock only.
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
                'message' => 'No remaining stock from previous inventory.'
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
            'message' => count($enrichedData) . ' product(s) have remaining stock from previous inventory.'
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
     *  POST /Inventory/SendReport
     */
    public function sendReport()
    {
        $data = $this->request->getJSON(true);

        $inventoryId = $data['inventory_id'] ?? null;
        $resendReason = isset($data['resend_reason']) ? trim((string) $data['resend_reason']) : null;

        if ($inventoryId === null) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Missing inventory_id.',
            ]);
        }

        $state = $this->dailyStockModel->find($inventoryId);
        if (!$state) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Inventory record not found.',
            ]);
        }

        if (!$state['is_closed']) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Inventory must be closed first before sending a report.',
            ]);
        }

        $shiftStart = trim((string) ($state['time_start'] ?? ''));
        if ($shiftStart === '') {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Shift start time is missing.',
            ]);
        }

        if ($state['report_sent'] == 1 || $state['report_sent'] === true) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => false,
                'message' => 'Report has already been sent for this inventory.',
            ]);
        }

        try {
            $sessionData = $this->getSessionData();
            $cashierUserId = intval($sessionData['user_id'] ?? 0);
            $sendResult = \App\Libraries\AutoReportScheduler::sendManualReportForInventory(
                (int) $inventoryId,
                $resendReason,
                $cashierUserId > 0 ? $cashierUserId : null
            );

            if (empty($sendResult['success'])) {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => $sendResult['message'] ?? 'Failed to send inventory report.',
                ]);
            }

            $updateData = [
                'time_end' => date('H:i:s'),
                'report_sent' => 1,
                'report_sent_at' => date('Y-m-d H:i:s'),
            ];
            $this->dailyStockModel->update($inventoryId, $updateData);

            return $this->response->setJSON([
                'success' => true,
                'resent' => !empty($sendResult['resent']),
                'recipients' => $sendResult['recipients'] ?? [],
                'inventory_id' => (int) $inventoryId,
                'redirect_url' => base_url('Sales?daily_stock_id=' . (int) $inventoryId),
                'message' => $sendResult['message'] ?? 'Inventory report sent successfully.',
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $e->getMessage() ?: 'Failed to send report.',
            ]);
        }
    }
    public function resetInventory(int $inventoryId)
    {
        if (!$inventoryId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Oops, Inventory not found!'
            ]);
        }

        $sourceInventory = $this->dailyStockModel->find($inventoryId);
        if (!$sourceInventory) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Source inventory record not found.'
            ]);
        }

        if (intval($sourceInventory['is_closed'] ?? 0) !== 1) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Close the current inventory first before creating a new shift.'
            ]);
        }

        if ($sourceInventory['report_sent'] == 0) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => false,
                'message' => 'Oops! please send the inventory report first before resetting.'
            ]);
        }

        $duplicate_item = $this->dailyStockItemsModel->where('daily_stock_id', $inventoryId)->findAll(); // duplicate the items
        $insertData = [
            'inventory_date' => date('Y-m-d'),
            'time_start' => date('H:i:s'),
            'time_end' => $this->getOpenShiftTimeEndValue(),
        ];

        if ($this->dailyStockModel->insert($insertData)) {
            $newInventoryId = (int) $this->dailyStockModel->getInsertID();

            if (!empty($duplicate_item)) {
                $this->dailyStockItemsModel->insertBatch(
                    array_map(function ($item) use ($newInventoryId) {
                        $endingStock = intval($item['ending_stock'] ?? 0);

                        return [
                            'daily_stock_id' => $newInventoryId,
                            'product_id' => $item['product_id'],
                            'beginning_stock' => $endingStock, // carry over ending stock as new beginning
                            'pull_out_quantity' => 0,
                            'ending_stock' => $endingStock, // initial ending same as beginning
                            'distribution_qty' => intval($item['distribution_qty'] ?? 0), // keep same-day distribution context across shifts
                            'is_enabled' => ($endingStock > 0 || !empty($item['is_enabled'])) ? 1 : 0,
                        ];
                    }, $duplicate_item)
                );
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inventory reset successfully with carryover stock.',
                'new_inventory_id' => $newInventoryId,
            ]);
        }

        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'message' => 'Failed to reset inventory.'
        ]);
    }
    /**
     * Get product recipe with raw materials and quantities
     * GET /Inventory/GetProductRecipe/{productId}
     * 
     * Returns raw materials needed per yield of the product
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
        $costModel = model('ProductCostModel');
        $costData = $costModel->getCostByProductId($productId);

        return $this->response->setJSON([
            'success' => true,
            'product_id' => $productId,
            'product_name' => $product['product_name'] ?? '',
            'category' => $product['category'] ?? '',
            'pieces_per_yield' => $costData['pieces_per_yield'] ?? null,
            'trays_per_yield' => $costData['trays_per_yield'] ?? null,
            'recipe' => $recipe,
            'recipe_count' => count($recipe)
        ]);
    }

    public function closeInventory()
    {
        $data = $this->request->getJSON(true);
        $today = date('Y-m-d');
        $dailyStock = $this->dailyStockModel->where('daily_stock_id', $data['inventory_id'])->where('inventory_date', $today)->first();

        if (!$dailyStock) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'No inventory found for today.'
            ]);
        }

        if ($dailyStock['is_closed']) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Inventory is already closed.'
            ]);
        }

        $this->dailyStockModel->update($dailyStock['daily_stock_id'], [
            'is_closed' => 1,
            'time_end' => date('H:i:s'),
        ]);
        $new_data = $this->dailyStockModel->find($dailyStock['daily_stock_id']);

        // Immediate notification: inventory closed
        $this->notify('notifyInventoryClosed', $today);

        return $this->response->setJSON([
            'success' => true,
            'inventory_state' => $new_data['is_closed'],
            'message' => 'Inventory closed successfully.'
        ]);
    }
    public function openInventory()
    {
        $data = $this->request->getJSON(true);
        $today = date('Y-m-d');
        $dailyStock = $this->dailyStockModel->where('daily_stock_id', $data['inventory_id'])->where('inventory_date', $today)->first();

        if (!$dailyStock) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'No inventory found for today.'
            ]);
        }

        if (!$dailyStock['is_closed']) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Inventory is already open.'
            ]);
        }

        $updateData = [
            'is_closed' => 0,
            'time_end' => $this->getOpenShiftTimeEndValue(),
            'report_sent' => 0,
        ];

        $this->dailyStockModel->update($dailyStock['daily_stock_id'], $updateData);
        $new_data = $this->dailyStockModel->find($dailyStock['daily_stock_id']);

        // Immediate notification: inventory opened
        $this->notify('notifyInventoryOpened', $today);

        return $this->response->setJSON([
            'success' => true,
            'inventory_state' => $new_data['is_closed'],
            'message' => 'Inventory opened successfully.'
        ]);
    }

    /**
     * NEW: Create or update distribution entry for Store action
     * Store action creates distribution under category ID 1 ("Store")
     */
    private function createOrUpdateDistributionEntryForStore(int $dailyStockId, int $productId, int $quantity)
    {
        try {
            $distributionGroupModel = model('DistributionGroupModel');
            $distributionItemModel = model('DistributionItemModel');

            $today = date('Y-m-d');
            $distCategoryId = 1; // "Store" category

            // Find or create distribution group for today + Store category
            $existingGroup = $distributionGroupModel
                ->where('distribution_date', $today)
                ->where('dist_category_id', $distCategoryId)
                ->first();

            if ($existingGroup) {
                $groupId = $existingGroup['id'];
            } else {
                // Create new distribution group
                $groupId = $distributionGroupModel->insert([
                    'distribution_date' => $today,
                    'dist_category_id' => $distCategoryId,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            // Find or create distribution item for this product in the group
            $existingItem = $distributionItemModel
                ->where('distribution_id', $groupId)
                ->where('product_id', $productId)
                ->first();

            if ($existingItem) {
                // Update existing item quantity
                $newQty = intval($existingItem['product_qnty']) + $quantity;
                $distributionItemModel->update($existingItem['id'], [
                    'product_qnty' => $newQty,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                // Create new item
                $distributionItemModel->insert([
                    'distribution_id' => $groupId,
                    'product_id' => $productId,
                    'product_qnty' => $quantity,
                    'qty_mode' => 'batch',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            // Recalculate totals for the group
            $distributionGroupModel->recalculateTotals($groupId);

        } catch (\Throwable $e) {
            log_message('error', 'Failed to create Store distribution entry: ' . $e->getMessage());
        }
    }

    /**
     * NEW: Create or update distribution entry for Distribute action
     * Distribute action creates distribution under specified destination category
     */
    private function createOrUpdateDistributionEntryForDistribute(int $dailyStockId, int $productId, int $quantity, int $distCategoryId)
    {
        try {
            $distributionGroupModel = model('DistributionGroupModel');
            $distributionItemModel = model('DistributionItemModel');

            $today = date('Y-m-d');

            // Find or create distribution group for today + destination category
            $existingGroup = $distributionGroupModel
                ->where('distribution_date', $today)
                ->where('dist_category_id', $distCategoryId)
                ->first();

            if ($existingGroup) {
                $groupId = $existingGroup['id'];
            } else {
                // Create new distribution group
                $groupId = $distributionGroupModel->insert([
                    'distribution_date' => $today,
                    'dist_category_id' => $distCategoryId,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            // Find or create distribution item for this product in the group
            $existingItem = $distributionItemModel
                ->where('distribution_id', $groupId)
                ->where('product_id', $productId)
                ->first();

            if ($existingItem) {
                // Update existing item quantity
                $newQty = intval($existingItem['product_qnty']) + $quantity;
                $distributionItemModel->update($existingItem['id'], [
                    'product_qnty' => $newQty,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                // Create new item
                $distributionItemModel->insert([
                    'distribution_id' => $groupId,
                    'product_id' => $productId,
                    'product_qnty' => $quantity,
                    'qty_mode' => 'batch',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            // Recalculate totals for the group
            $distributionGroupModel->recalculateTotals($groupId);

        } catch (\Throwable $e) {
            log_message('error', 'Failed to create Distribute distribution entry: ' . $e->getMessage());
        }
    }

    /**
     * NEW: Deduct raw materials for a product
     * Handles both direct recipes and combined recipes
     */
    private function deductRawMaterialsForProduct(int $productId, int $quantity)
    {
        try {
            if ($productId <= 0 || $quantity <= 0) {
                return;
            }

            $productRecipeModel = model('ProductRecipeModel');
            $productCombinedRecipeModel = model('ProductCombinedRecipeModel');
            $rawMaterialModel = model('RawMaterialModel');

            // Check for direct recipes
            $directRecipe = $productRecipeModel->getRecipeWithMaterialDetails($productId);
            if (!empty($directRecipe)) {
                foreach ($directRecipe as $material) {
                    $materialId = intval($material['material_id'] ?? 0);
                    $requiredQty = floatval($material['required_quantity'] ?? 0);
                    if ($materialId > 0 && $requiredQty > 0) {
                        $totalDeduction = $requiredQty * $quantity;
                        $rawMaterialModel->deductMaterial($materialId, $totalDeduction);
                    }
                }
            }

            // Check for combined recipes
            $combinedRecipes = $productCombinedRecipeModel->getCombinedRecipesByProductId($productId);
            foreach ($combinedRecipes as $recipe) {
                $componentProductId = intval($recipe['component_product_id'] ?? 0);
                $componentQty = intval($recipe['component_quantity'] ?? 0);
                if ($componentProductId > 0 && $componentQty > 0) {
                    // Recursively deduct materials for component products
                    $this->deductRawMaterialsForProduct($componentProductId, $componentQty * $quantity);
                }
            }

        } catch (\Throwable $e) {
            log_message('error', 'Failed to deduct raw materials: ' . $e->getMessage());
        }
    }
}