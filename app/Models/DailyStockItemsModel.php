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

    public function insertDailyStockItems($dailyStockId, $productIds)
    {
        $insertData = [];
        foreach ($productIds as $productId) {
            $insertData[] = [
                'daily_stock_id' => $dailyStockId,
                'product_id' => $productId,
                'beginning_stock' => 0,
                'pull_out_quantity' => 0,
                'ending_stock' => 0,
            ];
        }
        return $this->insertBatch($insertData);
    }

    public function fetchAllStockItems($dailyStockId)
    {
        $stockItems = $this->where('daily_stock_id', $dailyStockId)
            ->select('daily_stock_items.*, products.product_name, products.category, product_costs.selling_price, product_costs.selling_price_per_piece')
            ->join('products', 'daily_stock_items.product_id = products.product_id', 'left')
            ->join('product_costs', 'products.product_id = product_costs.product_id', 'left')
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
            WHERE p.product_id NOT IN (
                SELECT dsi.product_id FROM daily_stock_items dsi WHERE dsi.daily_stock_id = ?
            )
            ORDER BY p.category, p.product_name
        ", [$dailyStockId])->getResultArray();
    }
}