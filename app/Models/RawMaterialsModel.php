<?php
namespace App\Models;

use CodeIgniter\Model;

class RawMaterialsModel extends Model
{
    protected $table = 'raw_materials';
    protected $primaryKey = 'material_id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    protected $allowedFields = [
        'category_id',
        'material_name',
        'material_quantity',
        'unit',
        'date_created',
    ];

    public function getAllWithDetails(): array
    {
        return $this->db->query("
            SELECT rm.material_id, rm.material_name, rm.material_quantity, rm.unit, rm.category_id,
                   mc.category_name, mc.label, rmc.cost_per_unit,
                   COALESCE(rms.initial_qty, 0) as initial_qty,
                   CASE WHEN rms.stock_id IS NULL THEN 0 ELSE 1 END as has_stock
            FROM raw_materials rm
            LEFT JOIN material_category mc ON rm.category_id = mc.category_id
            LEFT JOIN raw_material_cost rmc ON rm.material_id = rmc.material_id
            LEFT JOIN raw_material_stock rms ON rm.material_id = rms.material_id
            ORDER BY rm.material_id DESC
        ")->getResultArray();
    }

    public function getMaterialById(int $id): ?array
    {
        return $this->db->query("
            SELECT rm.material_id, rm.material_name, rm.material_quantity, rm.unit, rm.category_id,
                   mc.category_name, rmc.cost_id, rmc.cost_per_unit,
                   rms.stock_id, COALESCE(rms.initial_qty, 0) as initial_qty,
                   COALESCE(rm.material_quantity * rmc.cost_per_unit, 0) as total_cost,
                   CASE WHEN rms.stock_id IS NULL THEN 0 ELSE 1 END as has_stock
            FROM raw_materials rm
            LEFT JOIN material_category mc ON rm.category_id = mc.category_id
            LEFT JOIN raw_material_cost rmc ON rm.material_id = rmc.material_id
            LEFT JOIN raw_material_stock rms ON rm.material_id = rms.material_id
            WHERE rm.material_id = ?
        ", [$id])->getRowArray();
    }

    public function nameExists(string $name, ?int $excludeId = null): bool
    {
        $builder = $this->db->table('raw_materials');
        $builder->where('LOWER(material_name)', strtolower(trim($name)));
        
        if ($excludeId) {
            $builder->where('material_id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    public function addMaterial(array $data)
    {
        $this->db->transStart();

        try {
            $qty = floatval($data['material_quantity']);
            $costPerUnit = floatval($data['total_cost']) / $qty;

            $this->db->query(
                "INSERT INTO raw_materials (category_id, material_name, material_quantity, unit) VALUES (?, ?, ?, ?)",
                [intval($data['category_id']), $data['material_name'], $qty, $data['unit']]
            );
            $materialId = $this->db->insertID();

            $this->db->query(
                "INSERT INTO raw_material_cost (material_id, cost_per_unit) VALUES (?, ?)",
                [$materialId, $costPerUnit]
            );

            // Stock initial is the baseline reference
            $this->db->query(
                "INSERT INTO raw_material_stock (material_id, initial_qty, qty_used, unit) VALUES (?, ?, 0, ?)",
                [$materialId, $qty, $data['unit']]
            );

            $this->db->transComplete();
            return $this->db->transStatus() ? $materialId : false;

        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function updateMaterial(array $data): bool
    {
        $this->db->transStart();

        try {
            $materialId = intval($data['material_id']);

            // Get current quantity to compute cost_per_unit (do NOT overwrite material_quantity)
            $current = $this->db->query(
                "SELECT material_quantity FROM raw_materials WHERE material_id = ?",
                [$materialId]
            )->getRowArray();
            $currentQty = floatval($current['material_quantity'] ?? 0);
            $costPerUnit = $currentQty > 0 ? floatval($data['total_cost']) / $currentQty : 0;

            // Update name, category, unit — but NOT material_quantity (managed by deductions/restock)
            $this->db->query(
                "UPDATE raw_materials SET category_id = ?, material_name = ?, unit = ? WHERE material_id = ?",
                [intval($data['category_id']), $data['material_name'], $data['unit'], $materialId]
            );

            $this->db->query(
                "UPDATE raw_material_cost SET cost_per_unit = ? WHERE material_id = ?",
                [$costPerUnit, $materialId]
            );

            $this->db->transComplete();
            return $this->db->transStatus();

        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Restock a raw material — adds quantity and updates initial_qty baseline
     */
    public function restockMaterial(int $materialId, float $addQty): bool
    {
        $this->db->transStart();

        try {
            // Add to current material_quantity
            $this->db->query(
                "UPDATE raw_materials SET material_quantity = material_quantity + ? WHERE material_id = ?",
                [$addQty, $materialId]
            );

            // Also update the initial_qty baseline in raw_material_stock
            $existing = $this->db->query(
                "SELECT stock_id FROM raw_material_stock WHERE material_id = ?",
                [$materialId]
            )->getRowArray();

            if ($existing) {
                $this->db->query(
                    "UPDATE raw_material_stock SET initial_qty = initial_qty + ? WHERE material_id = ?",
                    [$addQty, $materialId]
                );
            } else {
                $this->db->query(
                    "INSERT INTO raw_material_stock (material_id, initial_qty) VALUES (?, ?)",
                    [$materialId, $addQty]
                );
            }

            $this->db->transComplete();
            return $this->db->transStatus();

        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

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