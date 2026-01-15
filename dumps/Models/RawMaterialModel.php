<?php

namespace App\Models;

use CodeIgniter\Model;

class RawMaterialModel extends Model
{
    protected $table            = 'raw_materials';
    protected $primaryKey       = 'material_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'cost_id',
        'stock_id', 
        'category_id',
        'material_name',
        'material_quantity',
        'unit'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'date_created';
    protected $updatedField  = 'date_created';

    // Validation
    protected $validationRules = [
        'material_name'     => 'required|min_length[2]|max_length[255]',
        'material_quantity' => 'required|integer|greater_than[0]',
        'unit'              => 'required|max_length[255]',
        'category_id'       => 'required|integer',
    ];

    /**
     * Get all materials with their cost and stock info
     */
    public function getAllWithDetails(): array
    {
        return $this->select('
                raw_materials.*,
                raw_material_cost.cost_per_unit,
                raw_material_stock.current_quantity,
                material_category.category_name,
                (raw_materials.material_quantity * raw_material_cost.cost_per_unit) as total_cost
            ')
            ->join('raw_material_cost', 'raw_material_cost.cost_id = raw_materials.cost_id', 'left')
            ->join('raw_material_stock', 'raw_material_stock.stock_id = raw_materials.stock_id', 'left')
            ->join('material_category', 'material_category.category_id = raw_materials.category_id', 'left')
            ->findAll();
    }

    /**
     * Get material by ID with full details
     */
    public function getWithDetails(int $materialId): ?array
    {
        return $this->select('
                raw_materials.*,
                raw_material_cost.cost_per_unit,
                raw_material_stock.current_quantity,
                material_category.category_name,
                (raw_materials.material_quantity * raw_material_cost.cost_per_unit) as total_cost
            ')
            ->join('raw_material_cost', 'raw_material_cost.cost_id = raw_materials.cost_id', 'left')
            ->join('raw_material_stock', 'raw_material_stock.stock_id = raw_materials.stock_id', 'left')
            ->join('material_category', 'material_category.category_id = raw_materials.category_id', 'left')
            ->find($materialId);
    }

    /**
     * Get materials by category
     */
    public function getByCategory(int $categoryId): array
    {
        return $this->select('
                raw_materials.*,
                raw_material_cost.cost_per_unit,
                (raw_materials.material_quantity * raw_material_cost.cost_per_unit) as total_cost
            ')
            ->join('raw_material_cost', 'raw_material_cost.cost_id = raw_materials.cost_id', 'left')
            ->where('raw_materials.category_id', $categoryId)
            ->findAll();
    }

    /**
     * Calculate cost per unit from total cost
     */
    public function calculateCostPerUnit(float $totalCost, int $quantity): float
    {
        if ($quantity <= 0) {
            return 0;
        }
        return round($totalCost / $quantity, 6);
    }
}
