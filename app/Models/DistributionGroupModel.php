<?php

namespace App\Models;

use App\Libraries\DistributionQuantityCalculator;
use CodeIgniter\Model;

class DistributionGroupModel extends Model
{
    protected $table = 'distribution_group';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'dist_category_id',
        'distribution_date',
        'distributed_to_note',
        'forecasted_sales',
        'total_cost',
    ];

    // -------------------------------------------------------------------------
    // Single-group fetchers
    // -------------------------------------------------------------------------

    /**
     * Find a group by its primary key, including its items and joined product info.
     */
    public function getGroupWithItems(int $groupId): ?array
    {
        $group = $this->find($groupId);
        if (!$group) {
            return null;
        }

        $group = $this->attachCategoryName($group);

        $group['items'] = model('DistributionItemModel')->getItemsByGroup($groupId);
        return $group;
    }

    // -------------------------------------------------------------------------
    // Date-based fetchers
    // -------------------------------------------------------------------------

    /**
     * Return all groups for a specific date, each with their items.
     */
    public function getGroupsByDate(string $date): array
    {
        $groups = $this->where('distribution_date', $date)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        return $this->attachItems($this->attachCategoryNames($groups));
    }

    /**
     * Return all groups within a date range (used for the calendar view).
     * Items are attached to each group for consistent data structure with getGroupsByDate().
     */
    public function getGroupsByDateRange(string $startDate, string $endDate): array
    {
        $groups = $this->where('distribution_date >=', $startDate)
            ->where('distribution_date <=', $endDate)
            ->orderBy('distribution_date', 'ASC')
            ->findAll();

        return $this->attachItems($this->attachCategoryNames($groups));
    }

    /**
     * Check whether at least one group already exists for the given date.
     */
    public function existsForDate(string $date): bool
    {
        return $this->where('distribution_date', $date)->countAllResults() > 0;
    }

    // -------------------------------------------------------------------------
    // Summary helpers
    // -------------------------------------------------------------------------

    /**
     * Recompute and persist forecasted_sales + total_cost for a group
     * by calculating totals from product pricing data and item quantities.
     *
     * Call this after any item is added, updated, or deleted.
     */
    public function recalculateTotals(int $groupId): void
    {
        $itemModel = model('DistributionItemModel');
        $productModel = model('ProductModel');
        $productCostModel = model('ProductCostModel');

        $items = $itemModel->getItemsByGroup($groupId);

        $forecastedSales = 0.0;
        $totalCost = 0.0;

        foreach ($items as $item) {
            $productId = (int) $item['product_id'];
            $quantity = (int) $item['product_qnty'];
            $qtyMode = DistributionQuantityCalculator::normalizeQtyMode($item['qty_mode'] ?? 'batch');

            // Get product and pricing data
            $product = $productModel->find($productId);
            $costData = $productCostModel->getCostByProductId($productId);

            if (!$product || !$costData) {
                continue;
            }

            // ─────────────────────────────────────────────────────────────
            // Calculate forecasted sales
            // Priority: use selling_price_per_piece for pieces, selling_price for batch
            // ─────────────────────────────────────────────────────────────
            $metrics = DistributionQuantityCalculator::calculateDistributionMetrics($quantity, $qtyMode, $product, $costData);

            $forecastedSales += $quantity * (float) $metrics['unit_price'];

            // ─────────────────────────────────────────────────────────────
            // Calculate total cost
            // Use the product's total_cost_per_yield multiplied by yield units.
            // For pieces/box modes, metrics already converts quantity to yield units.
            // ─────────────────────────────────────────────────────────────
            $costPerYield = (float) ($costData['total_cost'] ?? 0);
            if ($costPerYield <= 0) {
                $directCost = (float) ($costData['direct_cost'] ?? 0);
                $combinedRecipeCost = (float) ($costData['combined_recipe_cost'] ?? 0);
                $overheadCostAmount = (float) ($costData['overhead_cost_amount'] ?? 0);

                if ($overheadCostAmount <= 0) {
                    $overheadCostPercentage = (float) ($costData['overhead_cost_percentage'] ?? 0);
                    if ($directCost > 0 && $overheadCostPercentage > 0) {
                        $overheadCostAmount = $directCost * ($overheadCostPercentage / 100);
                    }
                }

                $costPerYield = $directCost + $combinedRecipeCost + $overheadCostAmount;
            }
            $totalCost += (float) $metrics['yield_units'] * $costPerYield;
        }

        $this->update($groupId, [
            'forecasted_sales' => $forecastedSales,
            'total_cost' => $totalCost,
        ]);
    }

    // -------------------------------------------------------------------------
    // Internals
    // -------------------------------------------------------------------------

    /**
     * Attach items (with product info) to an array of groups.
     */
    private function attachItems(array $groups): array
    {
        if (empty($groups)) {
            return [];
        }

        $itemModel = model('DistributionItemModel');
        $groupIds = array_column($groups, 'id');

        // Bulk-fetch all items for every group in one query
        $allItems = $itemModel->getItemsByGroups($groupIds);

        // Index items by group_id for O(1) lookup
        $itemsByGroup = [];
        foreach ($allItems as $item) {
            $itemsByGroup[$item['distribution_id']][] = $item;
        }

        foreach ($groups as &$group) {
            $group['items'] = $itemsByGroup[$group['id']] ?? [];
        }
        unset($group);

        return $groups;
    }

    /**
     * Attach category names to a set of groups so existing view code can keep
     * reading `title` while the real foreign key remains `dist_category_id`.
     */
    private function attachCategoryNames(array $groups): array
    {
        if (empty($groups)) {
            return [];
        }

        $categoryModel = model('DistributionCategory');
        $categories = $categoryModel->findAll();
        $categoryMap = [];

        foreach ($categories as $category) {
            $categoryMap[intval($category['dist_cat_id'] ?? 0)] = trim((string) ($category['name'] ?? ''));
        }

        foreach ($groups as &$group) {
            $group = $this->attachCategoryName($group, $categoryMap);
        }
        unset($group);

        return $groups;
    }

    /**
     * Attach a single category name to one group row.
     */
    private function attachCategoryName(array $group, ?array $categoryMap = null): array
    {
        $categoryId = intval($group['dist_category_id'] ?? 0);

        if ($categoryMap === null) {
            $categoryModel = model('DistributionCategory');
            $categoryMap = [];

            $category = $categoryModel->find($categoryId);
            if ($category) {
                $categoryMap[$categoryId] = trim((string) ($category['name'] ?? ''));
            }
        }

        $categoryName = trim((string) ($categoryMap[$categoryId] ?? ''));
        $group['title'] = $categoryName !== '' ? $categoryName : 'Default Group';
        $group['group_title'] = $group['title'];
        $group['distribution_category_name'] = $group['title'];

        return $group;
    }

    /**
     * Remove empty groups from API payloads to prevent phantom calendar/list entries.
     */
    private function filterGroupsWithItems(array $groups): array
    {
        return array_values(array_filter($groups, static function (array $group): bool {
            return !empty($group['items']) && is_array($group['items']);
        }));
    }
}