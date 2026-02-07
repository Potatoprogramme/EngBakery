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

    /**
     * Deduct raw materials for production based on product recipe.
     *
     * Formula: yields_needed = pieces / pieces_per_yield
     *          deduction per material = quantity_needed (per yield) × yields_needed
     *
     * Also handles combined recipes (products made from other products)
     * by recursively deducting the source product's raw materials.
     *
     * @param int $productId  The product being produced
     * @param int $pieces     Number of pieces produced
     * @param bool $preview   If true, calculate only — don't actually deduct
     * @return array           Summary of deductions performed
     */
    public function deductForProduction(int $productId, int $pieces, bool $preview = false): array
    {
        if ($pieces <= 0) {
            return ['success' => false, 'message' => 'Pieces must be greater than 0', 'deductions' => []];
        }

        $productRecipeModel = model('ProductRecipeModel');
        $productCostModel   = model('ProductCostModel');
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
                $stock = $this->getByMaterialId($materialId);
                $currentQty = floatval($stock['current_quantity'] ?? 0);
                $afterQty   = max(0, $currentQty - $deductAmount);

                $deductions[] = [
                    'material_id'   => $materialId,
                    'material_name' => $materialName,
                    'unit'          => $unit,
                    'quantity_needed_per_yield' => $quantityNeeded,
                    'yields_needed' => round($yieldsNeeded, 2),
                    'deduct_amount' => round($deductAmount, 4),
                    'before'        => round($currentQty, 4),
                    'after'         => round($afterQty, 4),
                    'insufficient'  => $currentQty < $deductAmount,
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
                $sourceCost = $productCostModel->getCostByProductId($sourceProductId);
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

                        $stock = $this->getByMaterialId($materialId);
                        $currentQty = floatval($stock['current_quantity'] ?? 0);
                        $afterQty   = max(0, $currentQty - $deductAmount);

                        $deductions[] = [
                            'material_id'   => $materialId,
                            'material_name' => $materialName,
                            'unit'          => $unit,
                            'quantity_needed_per_yield' => $quantityNeeded,
                            'yields_needed' => round($sourceYieldsNeeded, 2),
                            'deduct_amount' => round($deductAmount, 4),
                            'before'        => round($currentQty, 4),
                            'after'         => round($afterQty, 4),
                            'insufficient'  => $currentQty < $deductAmount,
                            'from_combined' => $sourceName,
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
            'success'      => empty($errors),
            'preview'      => $preview,
            'message'      => $preview
                ? 'Preview calculated'
                : (empty($errors) ? 'Raw materials deducted successfully' : implode('; ', $errors)),
            'pieces'       => $pieces,
            'pieces_per_yield' => $piecesPerYield,
            'yields_needed'    => round($yieldsNeeded, 2),
            'deductions'       => $deductions,
            'has_insufficient' => $hasInsufficient,
        ];
    }
}
