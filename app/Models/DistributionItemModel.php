<?php

namespace App\Models;

use CodeIgniter\Model;

class DistributionItemModel extends Model
{
    protected $table      = 'distribution_item';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    protected $allowedFields = [
        'distribution_id',        // FK → distribution_group.id
        'product_id',
        'product_qnty',
        'qty_mode',
        'inventory_amount_used',  // raw-material units consumed
    ];

    // -------------------------------------------------------------------------
    // Fetchers
    // -------------------------------------------------------------------------

    /**
     * Return all items for a single group, joined with product info.
     */
    public function getItemsByGroup(int $groupId): array
    {
        return $this->select(
                'distribution_item.*,
                 products.product_name,
                 products.product_description,
                 products.category'
            )
            ->join('products', 'distribution_item.product_id = products.product_id')
            ->where('distribution_item.distribution_id', $groupId)
            ->where('products.deleted_at IS NULL')
            ->orderBy('distribution_item.created_at', 'ASC')
            ->findAll();
    }

    /**
     * Bulk-fetch items for multiple groups in one query.
     * Used by DistributionGroupModel::attachItems() to avoid N+1 queries.
     *
     * @param  int[] $groupIds
     */
    public function getItemsByGroups(array $groupIds): array
    {
        if (empty($groupIds)) {
            return [];
        }

        return $this->select(
                'distribution_item.*,
                 products.product_name,
                 products.product_description,
                 products.category'
            )
            ->join('products', 'distribution_item.product_id = products.product_id')
            ->whereIn('distribution_item.distribution_id', $groupIds)
            ->where('products.deleted_at IS NULL')
            ->orderBy('distribution_item.distribution_id', 'ASC')
            ->orderBy('distribution_item.created_at',      'ASC')
            ->findAll();
    }

    /**
     * Check whether a product is already in a specific group.
     * Used to prevent duplicate entries within the same schedule.
     */
    public function existsInGroup(int $groupId, int $productId): ?array
    {
        return $this->where('distribution_id', $groupId)
                    ->where('product_id', $productId)
                    ->first();
    }

    /**
     * Delete all items belonging to a group.
     * Called when an entire distribution group is deleted.
     */
    public function deleteByGroup(int $groupId): void
    {
        $this->where('distribution_id', $groupId)->delete();
    }

    /**
     * Return total inventory_amount_used for a group.
     * Convenience helper for raw-material accounting.
     */
    public function totalAmountUsedByGroup(int $groupId): float
    {
        $row = $this->selectSum('inventory_amount_used', 'total')
                    ->where('distribution_id', $groupId)
                    ->first();

        return (float)($row['total'] ?? 0);
    }
}