<?php
namespace App\Models;

use App\Libraries\DistributionQuantityCalculator;
use CodeIgniter\Model;

class DailyStockItemsModel extends Model
{
    protected $table = 'daily_stock_items';
    protected $primaryKey = 'item_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'daily_stock_id',
        'product_id',
        'beginning_stock',
        'pull_out_quantity',
        'ending_stock', // can be calculated
        'distribution_qty', // pieces sourced from distribution (0 if none)
        'is_enabled', // for enabling stock item
        'notes'
    ];

    // Dates
    protected $useTimestamps = false;
    // protected $createdField = 'date_created';
    // protected $updatedField = 'date_updated';
    // protected $deletedField = 'date_deleted';

    public function insertDailyStockItems($dailyStockId, $productIds, array $carryover = [])
    {
        $insertData = [];
        foreach ($productIds as $productId) {
            $carryoverQty = $carryover[intval($productId)] ?? 0;
            $insertData[] = [
                'daily_stock_id' => $dailyStockId,
                'product_id' => $productId,
                'beginning_stock' => $carryoverQty,
                'pull_out_quantity' => 0,
                'ending_stock' => $carryoverQty,
                'distribution_qty' => 0, // no distribution in this path
                'is_enabled' => ($carryoverQty > 0) ? 1 : 0, // enable if there's carryover stock
            ];
        }
        return $this->insertBatch($insertData);
    }

    /**
     * Insert daily stock items from distribution data.
     * Each distribution record provides the product_id and product_qnty,
     * which are converted into pieces using distribution qty mode semantics.
     *
     * @param int   $dailyStockId
     * @param array $distributionItems Array of distribution records with product_id and product_qnty
     * @return int|false Number of rows inserted or false on failure
     */
    public function insertDailyStockItemsFromDistribution(int $dailyStockId, array $distributionItems, array $carryover = [])
    {
        $insertData = [];
        $aggregatedProducts = $this->aggregateDistributionInventoryRows($distributionItems, $carryover);

        foreach ($aggregatedProducts as $productRow) {
            $productId = intval($productRow['product_id']);
            $distributionPieces = intval($productRow['distribution_qty']);
            $carryoverQty = intval($productRow['carryover_qty']);

            // Business rules:
            // - Carryover means already baked stock exists -> enabled.
            // - If carryover exists, do NOT auto-load today's distribution yet.
            //   Staff will load distribution manually once new batch is baked.
            // - Distribution-only rows are initialized as disabled.
            $autoLoadedDistribution = ($carryoverQty > 0) ? 0 : $distributionPieces;
            $totalBeginning = $carryoverQty + $autoLoadedDistribution;
            $isEnabled = ($carryoverQty > 0) ? 1 : 0;

            log_message('info', 'INVENTORY ITEMS INSERT: Product {product}, Distribution pieces: {pieces} + Carryover: {carryover} = {total}', [
                'product' => $productId,
                'pieces' => $distributionPieces,
                'carryover' => $carryoverQty,
                'total' => $totalBeginning
            ]);

            $insertData[] = [
                'daily_stock_id' => $dailyStockId,
                'product_id' => $productId,
                'beginning_stock' => $totalBeginning,
                'pull_out_quantity' => 0,
                'ending_stock' => $totalBeginning,
                'distribution_qty' => $autoLoadedDistribution,
                'is_enabled' => $isEnabled,
            ];
        }

        if (empty($insertData)) {
            return false;
        }

        return $this->insertBatch($insertData);
    }

    /**
     * Insert drink items that are not part of distribution inventory.
     * Drinks are enabled by default to ensure they appear in inventory.
     */
    public function insertDrinkStockItems(int $dailyStockId, array $productIds, array $carryover = []): bool
    {
        if (empty($productIds)) {
            return true;
        }

        $insertData = [];
        foreach ($productIds as $productId) {
            $productId = intval($productId);
            $carryoverQty = intval($carryover[$productId] ?? 0);

            $insertData[] = [
                'daily_stock_id' => $dailyStockId,
                'product_id' => $productId,
                'beginning_stock' => $carryoverQty,
                'pull_out_quantity' => 0,
                'ending_stock' => $carryoverQty,
                'distribution_qty' => 0,
                'is_enabled' => 1,
            ];
        }

        return $this->insertBatch($insertData) !== false;
    }

    public function consolidateDuplicateProductRows(int $dailyStockId): int
    {
        $rows = $this->where('daily_stock_id', $dailyStockId)
            ->orderBy('item_id', 'ASC')
            ->findAll();

        if (count($rows) <= 1) {
            return 0;
        }

        $groupedRows = [];
        foreach ($rows as $row) {
            $groupedRows[intval($row['product_id'])][] = $row;
        }

        $mergedRows = 0;
        $this->db->transStart();

        foreach ($groupedRows as $productId => $productRows) {
            if (count($productRows) <= 1) {
                continue;
            }

            $primaryRow = array_shift($productRows);
            $allRows = array_merge([$primaryRow], $productRows);
            $duplicateIds = array_map(static fn($row) => intval($row['item_id']), $productRows);

            $mergedData = [
                'beginning_stock' => array_sum(array_map(static fn($row) => intval($row['beginning_stock'] ?? 0), $allRows)),
                'pull_out_quantity' => array_sum(array_map(static fn($row) => intval($row['pull_out_quantity'] ?? 0), $allRows)),
                'ending_stock' => array_sum(array_map(static fn($row) => intval($row['ending_stock'] ?? 0), $allRows)),
                'distribution_qty' => array_sum(array_map(static fn($row) => intval($row['distribution_qty'] ?? 0), $allRows)),
                'is_enabled' => max(array_map(static fn($row) => intval($row['is_enabled'] ?? 0), $allRows)),
                'notes' => $this->mergeInventoryNotes(array_map(static fn($row) => $row['notes'] ?? null, $allRows)),
            ];

            if (!empty($duplicateIds)) {
                $this->db->table('transactions')
                    ->whereIn('item_id', $duplicateIds)
                    ->update(['item_id' => intval($primaryRow['item_id'])]);

                $this->whereIn('item_id', $duplicateIds)->delete();
            }

            $this->update(intval($primaryRow['item_id']), $mergedData);
            $mergedRows += count($productRows);

            log_message('warning', 'INVENTORY ITEMS CONSOLIDATE: Merged duplicate rows for product {product} in daily_stock {dailyStockId}. Kept item {itemId}, removed {removedCount} duplicate(s).', [
                'product' => $productId,
                'dailyStockId' => $dailyStockId,
                'itemId' => intval($primaryRow['item_id']),
                'removedCount' => count($productRows),
            ]);
        }

        $this->db->transComplete();

        return $this->db->transStatus() === false ? 0 : $mergedRows;
    }

    public function fetchAllStockItems($dailyStockId)
    {
        $stockItems = $this->where('daily_stock_id', $dailyStockId)
            ->select('daily_stock_items.*, products.product_name, products.category, product_costs.selling_price, product_costs.selling_price_per_piece, product_costs.direct_cost, product_costs.overhead_cost_amount, product_costs.pieces_per_yield, product_costs.trays_per_yield')
            ->join('products', 'daily_stock_items.product_id = products.product_id', 'left')
            ->join('product_costs', 'products.product_id = product_costs.product_id', 'left')
            ->where('products.deleted_at IS NULL')
            ->orderBy('products.category', 'ASC')
            ->orderBy('products.product_name', 'ASC')
            ->findAll();
        return $stockItems;
    }
    public function updateStockItem($item_id, $data)
    {
        return $this->update($item_id, $data);
    }

    /**
     * Get stock item for a specific product on a given daily stock
     */
    public function getStockItemByProduct(int $dailyStockId, int $productId): ?array
    {
        return $this->where('daily_stock_id', $dailyStockId)
            ->where('product_id', $productId)
            ->first();
    }

    /**
     * Deduct stock quantity when order is placed
     */
    public function deductStock(int $itemId, int $quantity): bool
    {
        $stockItem = $this->find($itemId);
        if (!$stockItem) {
            return false;
        }

        $newEndingStock = max(0, intval($stockItem['ending_stock']) - $quantity);
        return $this->update($itemId, ['ending_stock' => $newEndingStock]);
    }

    /**
     * Restore stock quantity when order is voided
     */
    public function restoreStock(int $itemId, int $quantity): bool
    {
        $stockItem = $this->find($itemId);
        if (!$stockItem) {
            return false;
        }

        $newEndingStock = intval($stockItem['ending_stock']) + $quantity;
        return $this->update($itemId, ['ending_stock' => $newEndingStock]);
    }

    /**
     * Add a single product to existing daily inventory
     */
    public function addProductToInventory(int $dailyStockId, int $productId, int $beginningStock = 0): int|false
    {
        // Check if product already exists in this inventory
        $existing = $this->where('daily_stock_id', $dailyStockId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            return false; // Already exists
        }

        $data = [
            'daily_stock_id' => $dailyStockId,
            'product_id' => $productId,
            'beginning_stock' => $beginningStock,
            'pull_out_quantity' => 0,
            'ending_stock' => $beginningStock,
        ];

        if ($this->insert($data)) {
            return $this->insertID();
        }
        return false;
    }

    /**
     * Get products NOT in current inventory (for adding)
     */
    public function getProductsNotInInventory(int $dailyStockId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT p.product_id, p.product_name, p.category
            FROM products p
            WHERE p.deleted_at IS NULL
            AND p.product_id NOT IN (
                SELECT dsi.product_id FROM daily_stock_items dsi WHERE dsi.daily_stock_id = ?
            )
            ORDER BY p.category, p.product_name
        ", [$dailyStockId])->getResultArray();
    }
    
    /**
     * Get products that can be added or restored in today's inventory.
     * Includes products not yet in inventory and products whose current ending stock is 0.
     */
    public function getProductsAvailableForInventory(int $dailyStockId): array
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT
                p.product_id,
                p.product_name,
                p.category,
                COALESCE(current_stock.ending_stock, 0) AS ending_stock
            FROM products p
            LEFT JOIN (
                SELECT
                    dsi.product_id,
                    SUM(COALESCE(dsi.ending_stock, 0)) AS ending_stock
                FROM daily_stock_items dsi
                WHERE dsi.daily_stock_id = ?
                GROUP BY dsi.product_id
            ) current_stock ON current_stock.product_id = p.product_id
                        WHERE p.deleted_at IS NULL
                            AND p.category != 'drinks'
                            AND (current_stock.product_id IS NULL OR current_stock.ending_stock = 0)
            ORDER BY p.category, p.product_name
        ", [$dailyStockId])->getResultArray();
    }

    /**
     * Get carryover stock from the most recent previous inventory record.
     * Returns an associative array keyed by product_id => ending_stock.
     *
     * Carryover rule:
     * - Use the most recent inventory record that still has positive ending stock.
     * - This uses the previous inventory's ending stock, not just the previous calendar day.
     *
     * @return array<int, int> [product_id => ending_stock]
     */
    public function getCarryoverStock(string $beforeDate): array
    {
        $db = \Config\Database::connect();

        try {
            $carryoverInventoryRow = $db->query(
                "SELECT ds.daily_stock_id, ds.inventory_date
                 FROM daily_stock ds
                 INNER JOIN daily_stock_items dsi ON dsi.daily_stock_id = ds.daily_stock_id
                 WHERE dsi.ending_stock > 0
                 ORDER BY ds.daily_stock_id DESC
                 LIMIT 1"
            )->getRowArray();
        } catch (\Throwable $e) {
            log_message('error', 'CARRYOVER: Failed to load previous inventory carryover: {error}', [
                'error' => $e->getMessage(),
            ]);
            return [];
        }

        $carryoverDailyStockId = $carryoverInventoryRow['daily_stock_id'] ?? null;
        if (!$carryoverDailyStockId) {
            log_message('info', 'CARRYOVER: beforeDate={beforeDate}, no previous inventory with remaining stock', [
                'beforeDate' => $beforeDate,
            ]);
            return [];
        }

        $items = $db->query("
            SELECT dsi.product_id, dsi.ending_stock
            FROM daily_stock_items dsi
            WHERE dsi.daily_stock_id = ?
              AND dsi.ending_stock > 0
        ", [$carryoverDailyStockId])->getResultArray();

        $carryover = [];
        foreach ($items as $item) {
            $remaining = intval($item['ending_stock']);
            if ($remaining > 0) {
                $carryover[intval($item['product_id'])] = $remaining;
            }
        }

        log_message('info', 'CARRYOVER: beforeDate={beforeDate}, carryover_daily_stock_id={carryoverDailyStockId}, products={count}', [
            'beforeDate' => $beforeDate,
            'carryoverDailyStockId' => $carryoverDailyStockId,
            'count' => count($carryover),
        ]);

        return $carryover;
    }

    private function aggregateDistributionInventoryRows(array $distributionItems, array $carryover = []): array
    {
        $productModel = model('ProductModel');
        $productCostModel = model('ProductCostModel');
        $aggregated = [];

        foreach ($distributionItems as $item) {
            $productId = intval($item['product_id'] ?? 0);
            if ($productId <= 0) {
                continue;
            }

            if (!isset($aggregated[$productId])) {
                $aggregated[$productId] = [
                    'product_id' => $productId,
                    'distribution_qty' => 0,
                    'carryover_qty' => intval($carryover[$productId] ?? 0),
                ];
            }

            $distributionQty = intval($item['product_qnty'] ?? 0);
            $qtyMode = DistributionQuantityCalculator::normalizeQtyMode($item['qty_mode'] ?? 'batch');
            $product = $productModel->find($productId);
            $costData = $productCostModel->getCostByProductId($productId);
            $metrics = DistributionQuantityCalculator::calculateDistributionMetrics($distributionQty, $qtyMode, $product, $costData);

            $aggregated[$productId]['distribution_qty'] += intval($metrics['pieces']);
        }

        foreach ($carryover as $productId => $carryoverQty) {
            $productId = intval($productId);
            $carryoverQty = intval($carryoverQty);

            if ($carryoverQty <= 0 || isset($aggregated[$productId])) {
                continue;
            }

            $aggregated[$productId] = [
                'product_id' => $productId,
                'distribution_qty' => 0,
                'carryover_qty' => $carryoverQty,
            ];
        }

        ksort($aggregated);

        return array_values($aggregated);
    }

    private function mergeInventoryNotes(array $notes): ?string
    {
        $filteredNotes = array_values(array_unique(array_filter(array_map(static function ($note) {
            $value = trim((string) $note);
            return $value !== '' ? $value : null;
        }, $notes))));

        if (empty($filteredNotes)) {
            return null;
        }

        return implode(' | ', $filteredNotes);
    }
}