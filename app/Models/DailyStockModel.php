<?php
namespace App\Models;

use CodeIgniter\Model;

class DailyStockModel extends Model
{
    protected $table = 'daily_stock';
    protected $primaryKey = 'daily_stock_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'inventory_date',
        'time_start',
        'time_end',
        'is_closed',
        'report_sent',
        'report_sent_at',
    ];

    // Dates
    protected $useTimestamps = false;
    // protected $createdField = 'date_created';
    // protected $updatedField = 'date_updated';
    // protected $deletedField = 'date_deleted';

    // public function checkInventoryExistsToday($date)
    // {
    //     return $this
    //         ->where('inventory_date', $date)
    //         ->orderBy('daily_stock_id', 'DESC')
    //         ->first();
    // }

    public function checkInventoryToday($date)
    {
        return $this
            ->where('inventory_date', $date)
            ->orderBy('daily_stock_id', 'DESC')
            ->first();
    }
    public function checkInventoryExists($date)
    {
        return $this
            ->where('inventory_date', $date)
            ->orderBy('daily_stock_id', 'DESC')
            ->first();
    }
    public function addTodaysInventory($data)
    {
        return $this->insert($data);
    }
    public function deleteInventory(int $id)
    {
        return $this->where('daily_stock_id', $id)->delete();
    }

    /**
     * Get today's inventory record
     */
    public function getTodaysInventory(): ?array
    {
        return $this
            ->where('inventory_date', date('Y-m-d'))
            ->orderBy('daily_stock_id', 'DESC')
            ->first();
    }

    /**
     * Get today's active inventory record used by Orders flow.
     * Active means the shift is not closed and report is not sent yet.
     */
    public function getActiveTodaysInventory(): ?array
    {
        return $this
            ->where('inventory_date', date('Y-m-d'))
            ->orderBy('inventory_date', 'DESC')
            ->orderBy('daily_stock_id', 'DESC')
            ->first();
    }

    /**
     * Get inventory history with optional date filters
     */
    public function getInventoryHistory(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $builder = $this->orderBy('inventory_date', 'DESC');

        if ($dateFrom) {
            $builder->where('inventory_date >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('inventory_date <=', $dateTo);
        }

        return $builder->findAll();
    }

    /**
     * Get inventory by ID
     */
    public function getInventoryById(int $id): ?array
    {
        return $this->find($id);
    }
}