<?php
namespace App\Models;

use CodeIgniter\Model;

class MaterialCategoryModel extends Model
{
    protected $table = 'material_category';
    protected $primaryKey = 'category_id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'category_name',
        'description',
        'date_created',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $createdField = 'date_created';
    // protected $updatedField = 'date_updated';
    // protected $deletedField = 'date_deleted';
}