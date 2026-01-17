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
    protected $useTimestamps = false;

    /**
     * Get all raw materials with category and cost details
     */
    public function getAllWithDetails(): array
    {
        return $this->db->query("
            SELECT 
                rm.material_id,
                rm.material_name,
                rm.material_quantity,
                rm.unit,
                rm.category_id,
                mc.category_name,
                rmc.cost_per_unit
            FROM raw_materials rm
            LEFT JOIN material_category mc ON rm.category_id = mc.category_id
            LEFT JOIN raw_material_cost rmc ON rm.material_id = rmc.material_id
            ORDER BY rm.material_id DESC
        ")->getResultArray();
    }

    /**
     * Get single material by ID with all details
     */
    public function getMaterialById(int $id): ?array
    {
        return $this->db->query("
            SELECT 
                rm.material_id,
                rm.material_name,
                rm.material_quantity,
                rm.unit,
                rm.category_id,
                rm.cost_id,
                rm.stock_id,
                mc.category_name,
                rmc.cost_per_unit,
                (rm.material_quantity * rmc.cost_per_unit) as total_cost
            FROM raw_materials rm
            LEFT JOIN material_category mc ON rm.category_id = mc.category_id
            LEFT JOIN raw_material_cost rmc ON rm.material_id = rmc.material_id
            WHERE rm.material_id = ?
        ", [$id])->getRowArray();
    }

    /**
     * Check if material name exists (case-insensitive)
     */
    public function nameExists(string $name, ?int $excludeId = null): bool
    {
        $builder = $this->db->table('raw_materials');
        $builder->where('LOWER(material_name)', strtolower(trim($name)));
        
        if ($excludeId) {
            $builder->where('material_id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Add new material with cost and stock records
     * Returns material_id on success, false on failure
     */
    public function addMaterial(array $data)
    {
        $this->db->transStart();

        try {
            $costPerUnit = floatval($data['total_cost']) / floatval($data['material_quantity']);

            // Disable foreign key checks temporarily
            $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

            // 1. Insert into raw_material_cost
            $this->db->query("INSERT INTO raw_material_cost (material_id, cost_per_unit) VALUES (0, ?)", [$costPerUnit]);
            $costId = $this->db->insertID();

            // 2. Insert into raw_material_stock
            $this->db->query("INSERT INTO raw_material_stock (material_id, current_quantity) VALUES (0, ?)", [floatval($data['material_quantity'])]);
            $stockId = $this->db->insertID();

            // 3. Insert into raw_materials
            $this->db->query("INSERT INTO raw_materials (cost_id, stock_id, category_id, material_name, material_quantity, unit) VALUES (?, ?, ?, ?, ?, ?)", [
                $costId,
                $stockId,
                intval($data['category_id']),
                $data['material_name'],
                floatval($data['material_quantity']),
                $data['unit']
            ]);
            $materialId = $this->db->insertID();

            // 4. Update cost and stock tables with correct material_id
            $this->db->query("UPDATE raw_material_cost SET material_id = ? WHERE cost_id = ?", [$materialId, $costId]);
            $this->db->query("UPDATE raw_material_stock SET material_id = ? WHERE stock_id = ?", [$materialId, $stockId]);

            // Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

            $this->db->transComplete();

            return $this->db->transStatus() ? $materialId : false;

        } catch (\Exception $e) {
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Update material with cost and stock records
     */
    public function updateMaterial(array $data): bool
    {
        $this->db->transStart();

        try {
            $costPerUnit = floatval($data['total_cost']) / floatval($data['material_quantity']);
            $materialId = intval($data['material_id']);

            // Update raw_materials
            $this->db->query("UPDATE raw_materials SET category_id = ?, material_name = ?, material_quantity = ?, unit = ? WHERE material_id = ?", [
                intval($data['category_id']),
                $data['material_name'],
                floatval($data['material_quantity']),
                $data['unit'],
                $materialId
            ]);

            // Update raw_material_cost
            $this->db->query("UPDATE raw_material_cost SET cost_per_unit = ? WHERE material_id = ?", [$costPerUnit, $materialId]);

            // Update raw_material_stock
            $this->db->query("UPDATE raw_material_stock SET current_quantity = ? WHERE material_id = ?", [floatval($data['material_quantity']), $materialId]);

            $this->db->transComplete();

            return $this->db->transStatus();

        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Delete material (cascades to cost and stock via FK)
     */
    public function deleteMaterial(int $id): bool
    {
        $this->db->transStart();

        try {
            $this->delete($id);
            $this->db->transComplete();
            return true;

        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }
}