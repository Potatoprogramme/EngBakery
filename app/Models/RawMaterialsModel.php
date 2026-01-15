<?php
namespace App\Models;

use CodeIgniter\Model;

class RawMaterialsModel extends Model
{
    protected $table = 'raw_materials';
    protected $primaryKey = 'material_id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'cost_id',
        'stock_id',
        'category_id',
        'material_name',
        'material_quantity',
        'unit',
        'date_created',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField = 'date_created';
    // protected $updatedField = 'updated_at';
}