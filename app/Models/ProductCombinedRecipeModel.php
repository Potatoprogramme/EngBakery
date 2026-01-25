<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductCombinedRecipeModel extends Model
{
    protected $table = 'product_combined_recipes';
    protected $primaryKey = 'combined_recipe_id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'product_id',
        'source_product_id',
        'grams_per_piece',
        'cost_per_gram',
        'total_cost',
        'date_created',
    ];

    protected $useTimestamps = false;

    /**
     * Get all combined recipes for a product
     */
    public function getCombinedRecipesByProductId(int $productId): array
    {
        return $this->db->query("
            SELECT 
                pcr.combined_recipe_id,
                pcr.product_id,
                pcr.source_product_id,
                pcr.grams_per_piece,
                pcr.cost_per_gram,
                pcr.total_cost,
                pcr.date_created,
                p.product_name as source_product_name,
                pc.yield_grams as source_yield_grams,
                pc.total_cost as source_total_cost,
                pc.trays_per_yield as source_trays_per_yield,
                pc.pieces_per_yield as source_pieces_per_yield,
                pc.grams_per_piece as source_grams_per_piece
            FROM product_combined_recipes pcr
            LEFT JOIN products p ON pcr.source_product_id = p.product_id
            LEFT JOIN product_costs pc ON pcr.source_product_id = pc.product_id
            WHERE pcr.product_id = ?
            ORDER BY pcr.combined_recipe_id ASC
        ", [$productId])->getResultArray();
    }

    /**
     * Save combined recipes for a product
     * This replaces all existing combined recipes
     */
    public function saveCombinedRecipes(int $productId, array $combinedRecipes): bool
    {
        $this->db->transStart();

        try {
            // Delete existing combined recipes for this product
            $this->where('product_id', $productId)->delete();

            // Insert new combined recipes
            if (!empty($combinedRecipes)) {
                foreach ($combinedRecipes as $recipe) {
                    $data = [
                        'product_id' => $productId,
                        'source_product_id' => intval($recipe['source_product_id'] ?? $recipe['id']),
                        'grams_per_piece' => floatval($recipe['grams_per_piece'] ?? $recipe['gramsPerPiece'] ?? 0),
                        'cost_per_gram' => floatval($recipe['cost_per_gram'] ?? $recipe['costPerUnit'] ?? 0),
                        'total_cost' => floatval($recipe['total_cost'] ?? $recipe['totalCost'] ?? 0),
                    ];
                    $this->insert($data);
                }
            }

            $this->db->transComplete();
            return $this->db->transStatus() !== false;

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error saving combined recipes: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete all combined recipes for a product
     */
    public function deleteCombinedRecipesByProductId(int $productId): bool
    {
        return $this->where('product_id', $productId)->delete();
    }

    /**
     * Add a single combined recipe
     */
    public function addCombinedRecipe(array $data): int
    {
        $this->insert($data);
        return $this->insertID();
    }

    /**
     * Check if a product is used as a combined recipe in other products
     * Useful before deleting a product
     */
    public function isUsedAsCombinedRecipe(int $productId): array
    {
        return $this->db->query("
            SELECT 
                pcr.product_id,
                p.product_name
            FROM product_combined_recipes pcr
            LEFT JOIN products p ON pcr.product_id = p.product_id
            WHERE pcr.source_product_id = ?
        ", [$productId])->getResultArray();
    }
}
