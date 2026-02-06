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
     * Get low stock materials (at or below minimum stock level)
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

    /**
     * Get materials with low stock levels based on quantity thresholds
     * Critical: <= 10 units, Warning: <= 25 units
     */
    public function getLowStockMaterials(float $criticalLevel = 10, float $warningLevel = 25): array
    {
        return $this->db->query("
            SELECT 
                rms.stock_id,
                rms.material_id,
                rms.current_quantity,
                rms.last_updated,
                rm.material_name,
                rm.unit,
                mc.category_name,
                mc.label,
                rmc.cost_per_unit,
                CASE 
                    WHEN rms.current_quantity <= ? THEN 'critical'
                    WHEN rms.current_quantity <= ? THEN 'warning'
                    ELSE 'normal'
                END as stock_status
            FROM raw_material_stock rms
            JOIN raw_materials rm ON rms.material_id = rm.material_id
            LEFT JOIN material_category mc ON rm.category_id = mc.category_id
            LEFT JOIN raw_material_cost rmc ON rm.material_id = rmc.material_id
            WHERE rms.current_quantity <= ?
            ORDER BY rms.current_quantity ASC
        ", [$criticalLevel, $warningLevel, $warningLevel])->getResultArray();
    }

    /**
     * Get count of low stock materials
     */
    public function getLowStockCount(float $criticalLevel = 10, float $warningLevel = 25): array
    {
        $result = $this->db->query("
            SELECT 
                SUM(CASE WHEN current_quantity <= ? THEN 1 ELSE 0 END) as critical_count,
                SUM(CASE WHEN current_quantity > ? AND current_quantity <= ? THEN 1 ELSE 0 END) as warning_count
            FROM raw_material_stock
        ", [$criticalLevel, $criticalLevel, $warningLevel])->getRowArray();
        
        return [
            'critical' => intval($result['critical_count'] ?? 0),
            'warning' => intval($result['warning_count'] ?? 0),
            'total' => intval($result['critical_count'] ?? 0) + intval($result['warning_count'] ?? 0)
        ];
    }
}