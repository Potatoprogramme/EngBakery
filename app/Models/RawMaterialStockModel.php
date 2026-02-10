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
    protected $allowedFields    = ['material_id', 'initial_qty', 'qty_used', 'unit'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'updated_at';
    protected $updatedField  = 'updated_at';

    // ═══════════════════════════════════════════
    //  STOCK PAGE CRUD
    // ═══════════════════════════════════════════

    /**
     * Get all stock entries with material name & category joined
     */
    public function getAllWithDetails(): array
    {
        return $this->db->query("
            SELECT 
                rms.stock_id,
                rms.material_id,
                rms.initial_qty,
                rms.qty_used,
                (rms.initial_qty - rms.qty_used) as remaining,
                rms.unit,
                rms.updated_at,
                rm.material_name,
                mc.category_name,
                mc.label
            FROM raw_material_stock rms
            JOIN raw_materials rm ON rms.material_id = rm.material_id
            LEFT JOIN material_category mc ON rm.category_id = mc.category_id
            ORDER BY rms.updated_at DESC
        ")->getResultArray();
    }

    /**
     * Get single stock entry by ID with material details
     */
    public function getEntryById(int $id): ?array
    {
        return $this->db->query("
            SELECT 
                rms.stock_id,
                rms.material_id,
                rms.initial_qty,
                rms.qty_used,
                (rms.initial_qty - rms.qty_used) as remaining,
                rms.unit,
                rms.updated_at,
                rm.material_name
            FROM raw_material_stock rms
            JOIN raw_materials rm ON rms.material_id = rm.material_id
            WHERE rms.stock_id = ?
        ", [$id])->getRowArray();
    }

    /**
     * Add a new stock entry
     */
    public function addEntry(array $data): int|false
    {
        $success = $this->insert([
            'material_id' => intval($data['material_id']),
            'initial_qty' => floatval($data['initial_qty']),
            'qty_used'    => 0,
            'unit'        => $data['unit'],
        ]);

        return $success ? $this->getInsertID() : false;
    }

    /**
     * Update an existing stock entry
     */
    public function updateEntry(int $stockId, array $data): bool
    {
        return $this->update($stockId, [
            'material_id' => intval($data['material_id']),
            'initial_qty' => floatval($data['initial_qty']),
            'qty_used'    => floatval($data['qty_used']),
            'unit'        => $data['unit'],
        ]);
    }

    /**
     * Delete a stock entry
     */
    public function deleteEntry(int $stockId): bool
    {
        return $this->delete($stockId);
    }

    // ═══════════════════════════════════════════
    //  STOCK ENGINE METHODS
    // ═══════════════════════════════════════════

    /**
     * Get stock by material ID.
     * Uses raw_materials.material_quantity as the current available quantity.
     */
    public function getByMaterialId(int $materialId): ?array
    {
        $row = $this->db->query("
            SELECT rms.*, rm.material_quantity as current_quantity
            FROM raw_material_stock rms
            JOIN raw_materials rm ON rm.material_id = rms.material_id
            WHERE rms.material_id = ?
        ", [$materialId])->getRowArray();

        // If no stock entry exists, still check raw_materials
        if (!$row) {
            $material = $this->db->query(
                "SELECT material_id, material_quantity as current_quantity FROM raw_materials WHERE material_id = ?",
                [$materialId]
            )->getRowArray();

            return $material ?: null;
        }

        return $row;
    }

    /**
     * Set stock to a specific quantity (resets qty_used to 0)
     */
    public function updateStock(int $materialId, float $quantity): bool
    {
        $existing = $this->getByMaterialId($materialId);

        if ($existing) {
            return $this->update($existing['stock_id'], [
                'initial_qty' => $quantity,
                'qty_used'    => 0,
            ]);
        }

        return $this->insert([
            'material_id' => $materialId,
            'initial_qty' => $quantity,
            'qty_used'    => 0,
        ]) !== false;
    }

    /**
     * Deduct from raw materials (decreases raw_materials.material_quantity)
     */
    public function deductStock(int $materialId, float $amount): bool
    {
        // Log the deduction attempt
        log_message('info', "DEDUCT: material_id={$materialId}, amount=" . round($amount, 4));

        // Reduce material_quantity in raw_materials, floor at 0
        $result = $this->db->query(
            "UPDATE raw_materials SET material_quantity = GREATEST(0, material_quantity - ?) WHERE material_id = ?",
            [round($amount, 4), $materialId]
        );

        $affected = $this->db->affectedRows();
        log_message('info', "DEDUCT RESULT: affected_rows={$affected}");

        // Return true if query executed (even if no rows changed because qty was already 0)
        return $result !== false;
    }

    /**
     * Add to stock (increases initial_qty)
     */
    public function addStock(int $materialId, float $amount, string $unit = ''): bool
    {
        $existing = $this->getByMaterialId($materialId);

        if (!$existing) {
            return $this->insert([
                'material_id' => $materialId,
                'initial_qty' => $amount,
                'qty_used'    => 0,
                'unit'        => $unit,
            ]) !== false;
        }

        $newInitial = floatval($existing['initial_qty']) + $amount;

        return $this->update($existing['stock_id'], [
            'initial_qty' => $newInitial,
        ]);
    }

    // ═══════════════════════════════════════════
    //  LOW STOCK METHODS
    // ═══════════════════════════════════════════

    /**
     * Get low stock materials (below threshold percentage)
     */
    public function getLowStock(float $thresholdPercentage = 20): array
    {
        return $this->db->query("
            SELECT 
                rms.*,
                rm.material_quantity as current_quantity,
                rm.material_name,
                rm.unit as material_unit,
                (rm.material_quantity / NULLIF(rms.initial_qty, 0) * 100) as stock_percentage
            FROM raw_material_stock rms
            JOIN raw_materials rm ON rm.material_id = rms.material_id
            HAVING stock_percentage <= ?
        ", [$thresholdPercentage])->getResultArray();
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
                rm.material_quantity as current_quantity,
                rms.updated_at,
                rm.material_name,
                rm.unit,
                mc.category_name,
                mc.label,
                rmc.cost_per_unit,
                CASE 
                    WHEN rm.material_quantity <= ? THEN 'critical'
                    WHEN rm.material_quantity <= ? THEN 'warning'
                    ELSE 'normal'
                END as stock_status
            FROM raw_material_stock rms
            JOIN raw_materials rm ON rms.material_id = rm.material_id
            LEFT JOIN material_category mc ON rm.category_id = mc.category_id
            LEFT JOIN raw_material_cost rmc ON rm.material_id = rmc.material_id
            WHERE rm.material_quantity <= ?
            ORDER BY rm.material_quantity ASC
        ", [$criticalLevel, $warningLevel, $warningLevel])->getResultArray();
    }

    /**
     * Get count of low stock materials
     */
    public function getLowStockCount(float $criticalLevel = 10, float $warningLevel = 25): array
    {
        $result = $this->db->query("
            SELECT 
                SUM(CASE WHEN rm.material_quantity <= ? THEN 1 ELSE 0 END) as critical_count,
                SUM(CASE WHEN rm.material_quantity > ? AND rm.material_quantity <= ? THEN 1 ELSE 0 END) as warning_count
            FROM raw_material_stock rms
            JOIN raw_materials rm ON rms.material_id = rm.material_id
        ", [$criticalLevel, $criticalLevel, $warningLevel])->getRowArray();

        return [
            'critical' => intval($result['critical_count'] ?? 0),
            'warning'  => intval($result['warning_count'] ?? 0),
            'total'    => intval($result['critical_count'] ?? 0) + intval($result['warning_count'] ?? 0),
        ];
    }

    // ═══════════════════════════════════════════
    //  BATCH DEDUCTION (for inventory creation)
    // ═══════════════════════════════════════════

    /**
     * Deduct raw materials for a batch of products being added to inventory.
     * Alerts for products with no recipe or insufficient raw material stock.
     *
     * @param array $items  Array of ['product_id' => int, 'quantity' => int, 'product_name' => string]
     * @param bool  $preview If true, only calculate — don't actually deduct
     * @return array Summary with per-product deduction results and alerts
     */
    public function deductForInventoryBatch(array $items, bool $preview = false): array
    {
        $results = [];
        $noRecipeProducts = [];
        $insufficientProducts = [];
        $successCount = 0;
        $totalDeductions = 0;

        foreach ($items as $item) {
            $productId   = intval($item['product_id']);
            $quantity    = intval($item['quantity'] ?? $item['product_qnty'] ?? 0);
            $productName = $item['product_name'] ?? "Product #{$productId}";

            if ($quantity <= 0) {
                continue;
            }

            $result = $this->deductForProduction($productId, $quantity, $preview);

            $result['product_id']   = $productId;
            $result['product_name'] = $productName;
            $result['pieces']       = $quantity;

            if (!$result['success'] && (
                str_contains($result['message'], 'No recipe found') ||
                str_contains($result['message'], 'No yield data found')
            )) {
                $noRecipeProducts[] = $productName;
            }

            if (!empty($result['has_insufficient'])) {
                $insufficientProducts[] = $productName;
            }

            if ($result['success']) {
                $successCount++;
                $totalDeductions += count($result['deductions'] ?? []);
            }

            $results[] = $result;
        }

        return [
            'success'                => true,
            'preview'                => $preview,
            'total_products'         => count($items),
            'products_deducted'      => $successCount,
            'total_deductions'       => $totalDeductions,
            'no_recipe_products'     => $noRecipeProducts,
            'insufficient_products'  => $insufficientProducts,
            'product_results'        => $results,
            'has_warnings'           => !empty($noRecipeProducts) || !empty($insufficientProducts),
        ];
    }

    // ═══════════════════════════════════════════
    //  PRODUCTION DEDUCTION
    // ═══════════════════════════════════════════

    /**
     * Deduct raw materials for production based on product recipe.
     *
     * Formula: yields_needed = pieces / pieces_per_yield
     *          deduction per material = quantity_needed (per yield) × yields_needed
     *
     * Also handles combined recipes (products made from other products)
     * by recursively deducting the source product's raw materials.
     *
     * @param int  $productId  The product being produced
     * @param int  $pieces     Number of pieces produced
     * @param bool $preview    If true, calculate only — don't actually deduct
     * @return array           Summary of deductions performed
     */
    public function deductForProduction(int $productId, int $pieces, bool $preview = false): array
    {
        if ($pieces <= 0) {
            return ['success' => false, 'message' => 'Pieces must be greater than 0', 'deductions' => []];
        }

        $productRecipeModel  = model('ProductRecipeModel');
        $productCostModel    = model('ProductCostModel');
        $combinedRecipeModel = model('ProductCombinedRecipeModel');

        // Get pieces_per_yield from product_costs
        $costData = $productCostModel->getCostByProductId($productId);
        $piecesPerYield = intval($costData['pieces_per_yield'] ?? 0);

        if ($piecesPerYield <= 0) {
            return ['success' => false, 'message' => 'No yield data found for this product', 'deductions' => []];
        }

        // Calculate how many yields are needed
        $yieldsNeeded = $pieces / $piecesPerYield;

        // Get the direct recipe (raw materials)
        $recipe = $productRecipeModel->getRecipeWithMaterialDetails($productId);

        // Get combined recipes (products used as ingredients)
        $combinedRecipes = $combinedRecipeModel->getCombinedRecipesByProductId($productId);

        if (empty($recipe) && empty($combinedRecipes)) {
            return ['success' => false, 'message' => 'No recipe found for this product', 'deductions' => []];
        }

        $deductions = [];
        $errors = [];

        if (!$preview) {
            $this->db->transStart();
        }

        try {
            // Process direct raw material ingredients
            foreach ($recipe as $ingredient) {
                $materialId     = intval($ingredient['material_id']);
                $quantityNeeded = floatval($ingredient['quantity_needed']);
                $deductAmount   = $quantityNeeded * $yieldsNeeded;
                $materialName   = $ingredient['material_name'] ?? 'Unknown';
                $unit           = $ingredient['unit'] ?? '';

                // Get current stock before deduction
                $stock      = $this->getByMaterialId($materialId);
                $currentQty = floatval($stock['current_quantity'] ?? 0);
                $afterQty   = max(0, $currentQty - $deductAmount);

                $deductions[] = [
                    'material_id'              => $materialId,
                    'material_name'            => $materialName,
                    'unit'                     => $unit,
                    'quantity_needed_per_yield' => $quantityNeeded,
                    'yields_needed'            => round($yieldsNeeded, 2),
                    'deduct_amount'            => round($deductAmount, 4),
                    'before'                   => round($currentQty, 4),
                    'after'                    => round($afterQty, 4),
                    'insufficient'             => $currentQty < $deductAmount,
                ];

                if (!$preview) {
                    if (!$this->deductStock($materialId, $deductAmount)) {
                        $errors[] = "Failed to deduct {$materialName}";
                    }
                }
            }

            // Process combined recipes — deduct raw materials of the source product
            foreach ($combinedRecipes as $combined) {
                $sourceProductId = intval($combined['source_product_id']);
                $gramsPerPiece   = floatval($combined['grams_per_piece']);
                $sourceName      = $combined['source_product_name'] ?? 'Unknown';

                // Total grams needed from the source product
                $totalGramsNeeded = $gramsPerPiece * $pieces;

                // Get the source product's yield info to convert grams → yields
                $sourceCost       = $productCostModel->getCostByProductId($sourceProductId);
                $sourceYieldGrams = floatval($sourceCost['yield_grams'] ?? 0);

                if ($sourceYieldGrams > 0) {
                    $sourceYieldsNeeded = $totalGramsNeeded / $sourceYieldGrams;

                    // Get the source product's direct recipe
                    $sourceRecipe = $productRecipeModel->getRecipeWithMaterialDetails($sourceProductId);

                    foreach ($sourceRecipe as $ingredient) {
                        $materialId     = intval($ingredient['material_id']);
                        $quantityNeeded = floatval($ingredient['quantity_needed']);
                        $deductAmount   = $quantityNeeded * $sourceYieldsNeeded;
                        $materialName   = $ingredient['material_name'] ?? 'Unknown';
                        $unit           = $ingredient['unit'] ?? '';

                        $stock      = $this->getByMaterialId($materialId);
                        $currentQty = floatval($stock['current_quantity'] ?? 0);
                        $afterQty   = max(0, $currentQty - $deductAmount);

                        $deductions[] = [
                            'material_id'              => $materialId,
                            'material_name'            => $materialName,
                            'unit'                     => $unit,
                            'quantity_needed_per_yield' => $quantityNeeded,
                            'yields_needed'            => round($sourceYieldsNeeded, 2),
                            'deduct_amount'            => round($deductAmount, 4),
                            'before'                   => round($currentQty, 4),
                            'after'                    => round($afterQty, 4),
                            'insufficient'             => $currentQty < $deductAmount,
                            'from_combined'            => $sourceName,
                        ];

                        if (!$preview) {
                            if (!$this->deductStock($materialId, $deductAmount)) {
                                $errors[] = "Failed to deduct {$materialName} (from {$sourceName})";
                            }
                        }
                    }
                }
            }

            if (!$preview) {
                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    return ['success' => false, 'message' => 'Transaction failed', 'deductions' => $deductions];
                }
            }

        } catch (\Exception $e) {
            if (!$preview) {
                $this->db->transRollback();
            }
            log_message('error', 'deductForProduction error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'deductions' => []];
        }

        $hasInsufficient = !empty(array_filter($deductions, fn($d) => $d['insufficient']));

        return [
            'success'          => empty($errors),
            'preview'          => $preview,
            'message'          => $preview
                ? 'Preview calculated'
                : (empty($errors) ? 'Raw materials deducted successfully' : implode('; ', $errors)),
            'pieces'           => $pieces,
            'pieces_per_yield' => $piecesPerYield,
            'yields_needed'    => round($yieldsNeeded, 2),
            'deductions'       => $deductions,
            'has_insufficient' => $hasInsufficient,
        ];
    }
}
