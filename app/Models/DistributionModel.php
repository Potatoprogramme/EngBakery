<?php

namespace App\Models;

use CodeIgniter\Model;

class DistributionModel extends Model
{
    protected $table = 'distributions';
    protected $primaryKey = 'distribution_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'product_id',
        'product_qnty',
        'distribution_date',
    ];

    public function getDistributionByDate($date)
    {
        return $this->select(
            'distributions.*, 
            products.product_id AS product_id, 
            products.product_name, 
            products.product_description, 
            products.category'
        )
            ->join('products', 'distributions.product_id = products.product_id')
            ->where('distribution_date', $date)
            ->groupBy('distributions.distribution_id')
            ->findAll();
    }
}
