<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductRecipeModel extends Model
{
    protected $table = 'product_recipe';
    protected $primaryKey = 'recipe_id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'product_id',
        'material_id',
        'quantity_needed',
        'unit',
        'date_created',
    ];

    // Dates
    protected $useTimestamps = false;

    /**
     * Get all recipe items for a product
     */
    public function getRecipeByProductId(int $productId): array
    {
        return $this->where('product_id', $productId)
            ->orderBy('recipe_id', 'ASC')
            ->findAll();
    }

    /**
     * Get recipe items with raw material details
     */
    public function getRecipeWithMaterialDetails(int $productId): array
    {
        return $this->db->query("
            SELECT 
                pr.recipe_id,
                pr.product_id,
                pr.material_id,
                pr.quantity_needed,
                pr.unit,
                pr.date_created,
                rm.material_name,
                rmc.cost_per_unit,
                (pr.quantity_needed * rmc.cost_per_unit) as ingredient_cost
            FROM product_recipe pr
            LEFT JOIN raw_materials rm ON pr.material_id = rm.material_id
            LEFT JOIN raw_material_cost rmc ON rm.material_id = rmc.material_id
            WHERE pr.product_id = ?
            ORDER BY pr.recipe_id ASC
        ", [$productId])->getResultArray();
    }

    /**
     * Add a single ingredient to a product recipe
     */
    public function addIngredient(array $data): int
    {
        $this->insert($data);
        return $this->insertID();
    }

    /**
     * Add multiple ingredients to a product recipe
     */
    public function addIngredients(int $productId, array $ingredients): bool
    {
        if (empty($ingredients)) {
            return true;
        }

        $this->db->transStart();

        try {
            foreach ($ingredients as $ingredient) {
                $ingredientData = [
                    'product_id' => $productId,
                    'material_id' => $ingredient['material_id'] ?? $ingredient['id'],
                    'quantity_needed' => floatval($ingredient['quantity_needed'] ?? $ingredient['quantity']),
                    'unit' => $ingredient['unit'],
                ];
                $this->insert($ingredientData);
            }

            $this->db->transComplete();
            return $this->db->transStatus() !== false;

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error adding ingredients: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete all recipe items for a product
     */
    public function deleteByProductId(int $productId): bool
    {
        return $this->where('product_id', $productId)->delete();
    }

    /**
     * Update recipe for a product (delete existing and insert new)
     */
    public function updateRecipe(int $productId, array $ingredients): bool
    {
        $this->db->transStart();

        try {
            // Delete existing recipe items
            $this->deleteByProductId($productId);

            // Add new ingredients
            if (!empty($ingredients)) {
                $this->addIngredients($productId, $ingredients);
            }

            $this->db->transComplete();
            return $this->db->transStatus() !== false;

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error updating recipe: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Calculate total direct cost from recipe ingredients
     */
    public function calculateDirectCost(int $productId): float
    {
        $result = $this->db->query("
            SELECT COALESCE(SUM(pr.quantity_needed * rmc.cost_per_unit), 0) as total_cost
            FROM product_recipe pr
            LEFT JOIN raw_material_cost rmc ON pr.material_id = rmc.material_id
            WHERE pr.product_id = ?
        ", [$productId])->getRow();

        return floatval($result->total_cost ?? 0);
    }

    /**
     * Check if a material is used in any recipe
     */
    public function isMaterialUsed(int $materialId): bool
    {
        return $this->where('material_id', $materialId)->countAllResults() > 0;
    }

    /**
     * Get all products using a specific material
     */
    public function getProductsUsingMaterial(int $materialId): array
    {
        return $this->db->query("
            SELECT DISTINCT 
                p.product_id,
                p.product_name,
                p.category
            FROM product_recipe pr
            JOIN products p ON pr.product_id = p.product_id
            WHERE pr.material_id = ?
            ORDER BY p.product_name
        ", [$materialId])->getResultArray();
    }
}
