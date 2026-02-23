<?php
namespace App\Models;

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
            ];
        }
        return $this->insertBatch($insertData);
    }

    /**
     * Insert daily stock items from distribution data.
     * Each distribution record provides the product_id and product_qnty (used as beginning_stock).
     *
     * @param int   $dailyStockId
     * @param array $distributionItems Array of distribution records with product_id and product_qnty
     * @return int|false Number of rows inserted or false on failure
     */
    public function insertDailyStockItemsFromDistribution(int $dailyStockId, array $distributionItems, array $carryover = [])
    {
        $insertData = [];
        $productModel = model('ProductModel');
        $productCostModel = model('ProductCostModel');
        
        // Track which products come from distribution
        $distributedProductIds = [];
        
        foreach ($distributionItems as $item) {
            $productId = intval($item['product_id']);
            $distributedProductIds[] = $productId;
            $distributionQty = intval($item['product_qnty'] ?? 0);
            $qtyMode = $item['qty_mode'] ?? 'batch';
            
            // If qty_mode is 'pieces', the value is already in pieces — no conversion needed
            if ($qtyMode === 'pieces') {
                $beginningStockPieces = $distributionQty;
            } else {
                // Convert batches to pieces
                $product = $productModel->find($productId);
                $category = $product['category'] ?? '';
                
                if (in_array($category, ['drinks', 'grocery'])) {
                    // For drinks/grocery: 1 distribution qty = 1 piece
                    $beginningStockPieces = $distributionQty;
                } else {
                    // For bakery/dough: 1 distribution qty = 1 batch = pieces_per_yield pieces
                    $costData = $productCostModel->getCostByProductId($productId);
                    $piecesPerYield = intval($costData['pieces_per_yield'] ?? 0);
                    if ($piecesPerYield <= 0) {
                        $piecesPerYield = 1;
                    }
                    $beginningStockPieces = $distributionQty * $piecesPerYield;
                }
            }
            
            // Add yesterday's remaining stock (carryover)
            $carryoverQty = $carryover[$productId] ?? 0;
            $totalBeginning = $beginningStockPieces + $carryoverQty;
            
            log_message('info', 'INVENTORY ITEMS INSERT: Product {product}, Distribution: {dist} {mode} → {pieces} pcs + Carryover: {carryover} = {total}', [
                'product' => $productId,
                'dist' => $distributionQty,
                'mode' => $qtyMode,
                'pieces' => $beginningStockPieces,
                'carryover' => $carryoverQty,
                'total' => $totalBeginning
            ]);
            
            $insertData[] = [
                'daily_stock_id' => $dailyStockId,
                'product_id'     => $productId,
                'beginning_stock' => $totalBeginning,
                'pull_out_quantity' => 0,
                'ending_stock'    => $totalBeginning,
            ];
        }
        
        // Add carryover-only products (not in today's distribution but had remaining stock yesterday)
        foreach ($carryover as $productId => $carryoverQty) {
            if (!in_array($productId, $distributedProductIds) && $carryoverQty > 0) {
                log_message('info', 'INVENTORY ITEMS INSERT (carryover only): Product {product}, Carryover: {carryover}', [
                    'product' => $productId,
                    'carryover' => $carryoverQty
                ]);
                
                $insertData[] = [
                    'daily_stock_id' => $dailyStockId,
                    'product_id'     => $productId,
                    'beginning_stock' => $carryoverQty,
                    'pull_out_quantity' => 0,
                    'ending_stock'    => $carryoverQty,
                ];
            }
        }

        if (empty($insertData)) {
            return false;
        }

        return $this->insertBatch($insertData);
    }

    public function fetchAllStockItems($dailyStockId)
    {
        $stockItems = $this->where('daily_stock_id', $dailyStockId)
            ->select('daily_stock_items.*, products.product_name, products.category, product_costs.selling_price, product_costs.selling_price_per_piece')
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
     * Get the most recent previous day's ending_stock per product (carryover).
     * Returns an associative array keyed by product_id => ending_stock.
     *
     * @param string $beforeDate  Only look at inventory dates strictly before this date (Y-m-d)
     * @return array<int, int>    [product_id => ending_stock]
     */
    public function getCarryoverStock(string $beforeDate): array
    {
        $db = \Config\Database::connect();

        // Find the most recent inventory date before the given date
        $previousStock = $db->query("
            SELECT ds.inventory_date
            FROM daily_stock ds
            WHERE ds.inventory_date < ?
            ORDER BY ds.inventory_date DESC
            LIMIT 1
        ", [$beforeDate])->getRowArray();

        if (!$previousStock) {
            return [];
        }

        $previousDate = $previousStock['inventory_date'];

        // Get all ending_stock values from that day
        $items = $db->query("
            SELECT dsi.product_id, dsi.ending_stock
            FROM daily_stock_items dsi
            JOIN daily_stock ds ON dsi.daily_stock_id = ds.daily_stock_id
            WHERE ds.inventory_date = ?
        ", [$previousDate])->getResultArray();

        $carryover = [];
        foreach ($items as $item) {
            $remaining = intval($item['ending_stock']);
            if ($remaining > 0) {
                $carryover[intval($item['product_id'])] = $remaining;
            }
        }

        return $carryover;
    }
}