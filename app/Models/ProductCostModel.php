<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductCostModel extends Model
{
    protected $table = 'product_costs';
    protected $primaryKey = 'product_cost_id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'product_id',
        'direct_cost',
        'combined_recipe_cost',
        'overhead_cost',
        'total_cost',
        'profit_margin',
        'profit_amount',
        'selling_price',
        'selling_price_per_tray',
        'selling_price_per_piece',
        'yield_grams',
        'trays_per_yield',
        'pieces_per_yield',
        'date_created',
    ];

    // Dates
    protected $useTimestamps = false;

    /**
     * Get cost record by product ID
     */
    public function getCostByProductId(int $productId): ?array
    {
        return $this->where('product_id', $productId)->first();
    }

    /**
     * Create or update cost record for a product
     */
    public function saveCost(int $productId, array $data): bool
    {
        $existingCost = $this->getCostByProductId($productId);

        $costData = [
            'product_id' => $productId,
            'direct_cost' => floatval($data['direct_cost'] ?? 0),
            'combined_recipe_cost' => floatval($data['combined_recipe_cost'] ?? 0),
            'overhead_cost' => floatval($data['overhead_cost'] ?? 0),
            'total_cost' => floatval($data['total_cost'] ?? 0),
            'profit_margin' => floatval($data['profit_margin'] ?? 0),
            'profit_amount' => floatval($data['profit_amount'] ?? 0),
            'selling_price' => floatval($data['selling_price'] ?? $data['selling_price_overall'] ?? 0),
            'selling_price_per_tray' => floatval($data['selling_price_per_tray'] ?? 0),
            'selling_price_per_piece' => floatval($data['selling_price_per_piece'] ?? 0),
            'yield_grams' => floatval($data['yield_grams'] ?? 0),
            'trays_per_yield' => intval($data['trays_per_yield'] ?? 0),
            'pieces_per_yield' => intval($data['pieces_per_yield'] ?? 0),
        ];

        if ($existingCost) {
            return $this->update($existingCost['product_cost_id'], $costData);
        } else {
            return $this->insert($costData) !== false;
        }
    }

    /**
     * Delete cost record by product ID
     */
    public function deleteCostByProductId(int $productId): bool
    {
        return $this->where('product_id', $productId)->delete();
    }

    /**
     * Calculate costs from given parameters
     */
    public function calculateCosts(array $params): array
    {
        $directCost = floatval($params['direct_cost'] ?? 0);
        $combinedRecipeCost = floatval($params['combined_recipe_cost'] ?? 0);
        $overheadPercentage = floatval($params['overhead_percentage'] ?? 0);
        $profitMargin = floatval($params['profit_margin'] ?? 0);
        $yieldGrams = floatval($params['yield_grams'] ?? 0);
        $traysPerYield = intval($params['trays_per_yield'] ?? 0);
        $piecesPerYield = intval($params['pieces_per_yield'] ?? 0);

        // Calculate overhead cost based on direct cost
        $overheadCost = $directCost * ($overheadPercentage / 100);

        // Total cost = direct + combined + overhead
        $totalCost = $directCost + $combinedRecipeCost + $overheadCost;

        // Calculate profit amount
        $profitAmount = $totalCost * ($profitMargin / 100);

        // Overall selling price
        $sellingPrice = $totalCost + $profitAmount;

        // Calculate per-unit prices
        $sellingPricePerTray = 0;
        $sellingPricePerPiece = 0;

        if ($traysPerYield > 0) {
            $sellingPricePerTray = $sellingPrice / $traysPerYield;
        }

        if ($piecesPerYield > 0) {
            if ($traysPerYield > 0) {
                // Pieces input means "pieces per tray"
                $totalPieces = $traysPerYield * $piecesPerYield;
                $sellingPricePerPiece = $sellingPrice / $totalPieces;
            } else {
                // Direct division by pieces
                $sellingPricePerPiece = $sellingPrice / $piecesPerYield;
            }
        }

        return [
            'direct_cost' => $directCost,
            'combined_recipe_cost' => $combinedRecipeCost,
            'overhead_cost' => $overheadCost,
            'total_cost' => $totalCost,
            'profit_margin' => $profitMargin,
            'profit_amount' => $profitAmount,
            'selling_price' => $sellingPrice,
            'selling_price_per_tray' => $sellingPricePerTray,
            'selling_price_per_piece' => $sellingPricePerPiece,
            'yield_grams' => $yieldGrams,
            'trays_per_yield' => $traysPerYield,
            'pieces_per_yield' => $piecesPerYield,
        ];
    }

    /**
     * Get all products with their costs
     */
    public function getAllWithProductDetails(): array
    {
        return $this->db->query("
            SELECT 
                p.product_id,
                p.product_name,
                p.category,
                pc.product_cost_id,
                pc.direct_cost,
                pc.combined_recipe_cost,
                pc.overhead_cost,
                pc.total_cost,
                pc.profit_margin,
                pc.profit_amount,
                pc.selling_price,
                pc.selling_price_per_tray,
                pc.selling_price_per_piece,
                pc.yield_grams,
                pc.trays_per_yield,
                pc.pieces_per_yield,
                pc.date_created
            FROM product_costs pc
            JOIN products p ON pc.product_id = p.product_id
            ORDER BY p.product_name ASC
        ")->getResultArray();
    }

    /**
     * Update selling prices only
     */
    public function updateSellingPrices(int $productId, float $overall, float $perTray = 0, float $perPiece = 0): bool
    {
        $existingCost = $this->getCostByProductId($productId);

        if (!$existingCost) {
            return false;
        }

        return $this->update($existingCost['product_cost_id'], [
            'selling_price' => $overall,
            'selling_price_per_tray' => $perTray,
            'selling_price_per_piece' => $perPiece,
        ]);
    }
}
