<?php

namespace App\Models;

use CodeIgniter\Model;

class RawMaterialStockInitialModel extends Model
{
    protected $table            = 'raw_material_stock_initial';
    protected $primaryKey       = 'entry_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['material_id', 'initial_quantity', 'quantity_used', 'unit'];

    protected $useTimestamps = false;

    /**
     * Get all entries with material name joined
     */
    public function getAllWithDetails(): array
    {
        return $this->db->query("
            SELECT 
                rsi.entry_id,
                rsi.material_id,
                rsi.initial_quantity,
                rsi.quantity_used,
                (rsi.initial_quantity - rsi.quantity_used) as remaining,
                rsi.unit,
                rsi.date_added,
                rm.material_name,
                mc.category_name,
                mc.label
            FROM raw_material_stock_initial rsi
            JOIN raw_materials rm ON rsi.material_id = rm.material_id
            LEFT JOIN material_category mc ON rm.category_id = mc.category_id
            ORDER BY rsi.date_added DESC
        ")->getResultArray();
    }

    /**
     * Get single entry by ID with material details
     */
    public function getEntryById(int $id): ?array
    {
        return $this->db->query("
            SELECT 
                rsi.entry_id,
                rsi.material_id,
                rsi.initial_quantity,
                rsi.quantity_used,
                (rsi.initial_quantity - rsi.quantity_used) as remaining,
                rsi.unit,
                rsi.date_added,
                rm.material_name
            FROM raw_material_stock_initial rsi
            JOIN raw_materials rm ON rsi.material_id = rm.material_id
            WHERE rsi.entry_id = ?
        ", [$id])->getRowArray();
    }

    /**
     * Add a new stock initial entry
     */
    public function addEntry(array $data): int|false
    {
        $success = $this->insert([
            'material_id'      => intval($data['material_id']),
            'initial_quantity'  => floatval($data['initial_quantity']),
            'quantity_used'     => 0,
            'unit'             => $data['unit'],
        ]);

        return $success ? $this->getInsertID() : false;
    }

    /**
     * Update an existing entry
     */
    public function updateEntry(int $entryId, array $data): bool
    {
        return $this->update($entryId, [
            'material_id'      => intval($data['material_id']),
            'initial_quantity'  => floatval($data['initial_quantity']),
            'quantity_used'     => floatval($data['quantity_used']),
            'unit'             => $data['unit'],
        ]);
    }

    /**
     * Delete an entry
     */
    public function deleteEntry(int $entryId): bool
    {
        return $this->delete($entryId);
    }

    /**
     * Get entries for a specific material
     */
    public function getByMaterialId(int $materialId): array
    {
        return $this->where('material_id', $materialId)
                     ->orderBy('date_added', 'DESC')
                     ->findAll();
    }
}
