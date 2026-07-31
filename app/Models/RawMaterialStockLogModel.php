<?php

namespace App\Models;

use CodeIgniter\Model;

class RawMaterialStockLogModel extends Model
{
    protected $table = 'raw_material_stock_logs';
    protected $primaryKey = 'log_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'material_id', 'action', 'amount', 'before_qty', 'after_qty',
        'unit', 'changed_by', 'changed_by_name', 'source', 'created_at',
    ];

    /**
     * Record a manual add/subtract edit. Skips logging if there's no real change.
     */
    public function logChange(array $data): bool
    {
        $amount = round(floatval($data['amount'] ?? 0), 4);
        if ($amount == 0) {
            return true; // nothing actually changed — don't clutter the log
        }

        $data['amount'] = abs($amount);
        $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');

        return $this->insert($data) !== false;
    }

    /**
     * All manual stock edits for a given Y-m-d date, joined with material name.
     */
    public function getEditsForDate(string $date): array
    {
        return $this->db->query("
            SELECT
                l.log_id, l.material_id, l.action, l.amount, l.before_qty, l.after_qty,
                l.unit, l.changed_by, l.changed_by_name, l.source, l.created_at,
                rm.material_name
            FROM raw_material_stock_logs l
            LEFT JOIN raw_materials rm ON rm.material_id = l.material_id
            WHERE DATE(l.created_at) = ?
            ORDER BY l.created_at ASC
        ", [$date])->getResultArray();
    }
}