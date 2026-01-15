<?php

namespace App\Models;

use CodeIgniter\Model;

class RawMaterialCostModel extends Model
{
    protected $table            = 'raw_material_cost';
    protected $primaryKey       = 'cost_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['material_id', 'cost_per_unit'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'date_created';
    protected $updatedField  = 'date_created';

    // Validation
    protected $validationRules = [
        'cost_per_unit' => 'required|decimal|greater_than_equal_to[0]',
    ];

    /**
     * Get cost by material ID
     */
    public function getByMaterialId(int $materialId): ?array
    {
        return $this->where('material_id', $materialId)->first();
    }

    /**
     * Update or create cost for material
     */
    public function upsertCost(int $materialId, float $costPerUnit): bool
    {
        $existing = $this->getByMaterialId($materialId);
        
        if ($existing) {
            return $this->update($existing['cost_id'], [
                'cost_per_unit' => $costPerUnit
            ]);
        }
        
        return $this->insert([
            'material_id'   => $materialId,
            'cost_per_unit' => $costPerUnit
        ]) !== false;
    }
}
