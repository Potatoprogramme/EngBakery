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
            ->join('products', 'daily_stock_items.product_id = products.product_id', 'left')
            ->join('product_costs', 'products.product_id = product_costs.product_id', 'left')
            ->findAll();
        return $stockItems;
    }
}