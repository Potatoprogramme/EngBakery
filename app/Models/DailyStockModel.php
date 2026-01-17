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
    ];

    // Dates
    protected $useTimestamps = false;
    // protected $createdField = 'date_created';
    // protected $updatedField = 'date_updated';
    // protected $deletedField = 'date_deleted';
}