<?php

namespace App\Models;

use CodeIgniter\Model;

class RawMaterialStockModel extends Model
{
    protected $table            = 'raw_material_stock';
    protected $primaryKey       = 'stock_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['material_id', 'current_quantity'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'last_updated';
    protected $updatedField  = 'last_updated';

    // Validation
    protected $validationRules = [
        'current_quantity' => 'required|decimal|greater_than_equal_to[0]',
    ];

    /**
     * Get stock by material ID
     */
    public function getByMaterialId(int $materialId): ?array
    {
        return $this->where('material_id', $materialId)->first();
    }

    /**
     * Update stock quantity
     */
    public function updateStock(int $materialId, float $quantity): bool
    {
        $existing = $this->getByMaterialId($materialId);
        
        if ($existing) {
            return $this->update($existing['stock_id'], [
                'current_quantity' => $quantity
            ]);
        }
        
        return $this->insert([
            'material_id'      => $materialId,
            'current_quantity' => $quantity
        ]) !== false;
    }

    /**
     * Deduct from stock
     */
    public function deductStock(int $materialId, float $amount): bool
    {
        $existing = $this->getByMaterialId($materialId);
        
        if (!$existing) {
            return false;
        }
        
        $newQuantity = max(0, $existing['current_quantity'] - $amount);
        
        return $this->update($existing['stock_id'], [
            'current_quantity' => $newQuantity
        ]);
    }

    /**
     * Add to stock
     */
    public function addStock(int $materialId, float $amount): bool
    {
        $existing = $this->getByMaterialId($materialId);
        
        if (!$existing) {
            return $this->insert([
                'material_id'      => $materialId,
                'current_quantity' => $amount
            ]) !== false;
        }
        
        $newQuantity = $existing['current_quantity'] + $amount;
        
        return $this->update($existing['stock_id'], [
            'current_quantity' => $newQuantity
        ]);
    }

    /**
     * Get low stock materials (below threshold percentage)
     */
    public function getLowStock(float $thresholdPercentage = 20): array
    {
        return $this->select('
                raw_material_stock.*,
                raw_materials.material_name,
                raw_materials.material_quantity,
                raw_materials.unit,
                (raw_material_stock.current_quantity / raw_materials.material_quantity * 100) as stock_percentage
            ')
            ->join('raw_materials', 'raw_materials.stock_id = raw_material_stock.stock_id')
            ->having('stock_percentage <=', $thresholdPercentage)
            ->findAll();
    }
}
