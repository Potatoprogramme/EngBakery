<?php

namespace App\Models;

use CodeIgniter\Model;

class DistributionCategory extends Model
{
    protected $table = 'distribution_category';
    protected $primaryKey = 'dist_cat_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['name'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}
