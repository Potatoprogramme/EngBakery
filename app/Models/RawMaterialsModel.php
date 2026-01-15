<?php
namespace App\Models;

use CodeIgniter\Model;

class RawMaterialsModel extends Model
{
    protected $table = 'raw_materials';
    protected $primaryKey = 'material_id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

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