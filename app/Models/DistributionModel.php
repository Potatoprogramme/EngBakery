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
        'qty_mode',
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
            ->where('products.deleted_at IS NULL')
            ->groupBy('distributions.distribution_id')
            ->findAll();
    }

    /**
     * Get distribution records for a date range (for calendar view)
     */
    public function getDistributionByDateRange($startDate, $endDate)
    {
        return $this->select(
            'distributions.*, 
            products.product_id AS product_id, 
            products.product_name, 
            products.product_description, 
            products.category'
        )
            ->join('products', 'distributions.product_id = products.product_id')
            ->where('distribution_date >=', $startDate)
            ->where('distribution_date <=', $endDate)
            ->where('products.deleted_at IS NULL')
            ->orderBy('distribution_date', 'ASC')
            ->findAll();
    }

    /**
     * Check if a product already exists in the distribution for a given date
     */
    public function existsForDate($productId, $date)
    {
        return $this->where('product_id', $productId)
            ->where('distribution_date', $date)
            ->first();
    }
}
