<?php
namespace App\Models;

use CodeIgniter\Model;

class DailyStockModel extends Model
{
    protected $table = 'daily_stock';
    protected $primaryKey = 'daily_stock_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'inventory_date',
        'time_start',
        'time_end',
    ];

    // Dates
    protected $useTimestamps = false;
    // protected $createdField = 'date_created';
    // protected $updatedField = 'date_updated';
    // protected $deletedField = 'date_deleted';

    public function checkInventoryExistsToday($date)
    {
        return $this->where('inventory_date', $date)->first();
    }

    public function checkInventoryToday($date)
    {
        if ($this->where('inventory_date', $date)->first()) {
            return true;
        }
        return false;
    }
    public function checkInventoryExists($date)
    {
        return $this->where('inventory_date', $date)->first();
    }
    public function addTodaysInventory($data)
    {
        return $this->insert($data);
    }
}