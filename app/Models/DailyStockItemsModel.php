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
        'ending_stock',
    ];

    // Dates
    protected $useTimestamps = false;
    // protected $createdField = 'date_created';
    // protected $updatedField = 'date_updated';
    // protected $deletedField = 'date_deleted';

    public function addDailyStockItem($id, $data)
    {
        $data['daily_stock_id'] = $id;
        return $this->insert($data);
    }
}