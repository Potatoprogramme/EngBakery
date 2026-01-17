<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'recipe_id',
        'category',
        'product_name',
        'product_description',
        'overhead_cost_percentage',
        'profit_margin',
        'date_created',
    ];

    // Dates
    protected $useTimestamps = false;

    /**
     * Get all products with their details
     */
    public function getAllWithDetails(): array
    {
        return $this->db->query("
            SELECT 
                p.product_id,
                p.product_name,
                p.product_description,
                p.category,
                p.overhead_cost_percentage,
                p.profit_margin,
                pc.direct_cost,
                pc.overhead_cost,
                pc.total_cost,
                pc.selling_price,
                p.date_created
            FROM products p
            LEFT JOIN product_costs pc ON p.product_id = pc.product_id
            ORDER BY p.product_id DESC
        ")->getResultArray();
    }

    /**
     * Get single product by ID with all details
     */
    public function getProductById(int $id): ?array
    {
        return $this->db->query("
            SELECT 
                p.product_id,
                p.recipe_id,
                p.product_name,
                p.product_description,
                p.category,
                p.overhead_cost_percentage,
                p.profit_margin,
                pc.direct_cost,
                pc.overhead_cost,
                pc.total_cost,
                pc.selling_price,
                p.date_created
            FROM products p
            LEFT JOIN product_costs pc ON p.product_id = pc.product_id
            WHERE p.product_id = ?
        ", [$id])->getRowArray();
    }

    /**
     * Get product recipe/ingredients
     */
    public function getProductRecipe(int $productId): array
    {
        return $this->db->query("
            SELECT 
                pr.recipe_id,
                pr.material_id,
                pr.quantity_needed,
                pr.unit,
                rm.material_name,
                rmc.cost_per_unit,
                (pr.quantity_needed * rmc.cost_per_unit) as ingredient_cost
            FROM product_recipe pr
            LEFT JOIN raw_materials rm ON pr.material_id = rm.material_id
            LEFT JOIN raw_material_cost rmc ON rm.material_id = rmc.material_id
            WHERE pr.product_id = ?
            ORDER BY pr.recipe_id
        ", [$productId])->getResultArray();
    }

    /**
     * Check if product name exists (case-insensitive)
     */
    public function nameExists(string $name, ?int $excludeId = null): bool
    {
        $builder = $this->db->table('products');
        $builder->where('LOWER(product_name)', strtolower(trim($name)));
        
        if ($excludeId) {
            $builder->where('product_id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Add new product with recipe and cost records
     * Returns product_id on success, false on failure
     */
    public function addProduct(array $data)
    {
        $this->db->transStart();

        try {
            // Note: The database has a foreign key constraint from products to product_recipe
            // This is problematic. We'll need to handle this carefully.
            
            // First, create a placeholder recipe entry
            $recipeData = [
                'product_id' => 0, // Will be updated later
                'material_id' => 0, // Placeholder
                'quantity_needed' => 0,
                'unit' => 'grams',
            ];
            
            $this->db->table('product_recipe')->insert($recipeData);
            $recipeId = $this->db->insertID();

            // Now create the product
            $productData = [
                'recipe_id' => $recipeId,
                'category' => $data['category'],
                'product_name' => $data['product_name'],
                'product_description' => $data['product_description'] ?? '',
                'overhead_cost_percentage' => floatval($data['overhead_cost_percentage'] ?? 0),
                'profit_margin' => floatval($data['profit_margin'] ?? 0),
            ];

            $this->db->table('products')->insert($productData);
            $productId = $this->db->insertID();

            // Update the recipe with the actual product_id
            $this->db->table('product_recipe')
                ->where('recipe_id', $recipeId)
                ->update(['product_id' => $productId]);

            // Insert recipe ingredients if provided
            if (!empty($data['ingredients']) && is_array($data['ingredients'])) {
                // Delete the placeholder recipe
                $this->db->table('product_recipe')
                    ->where('recipe_id', $recipeId)
                    ->delete();

                foreach ($data['ingredients'] as $ingredient) {
                    $ingredientData = [
                        'product_id' => $productId,
                        'material_id' => $ingredient['material_id'],
                        'quantity_needed' => floatval($ingredient['quantity']),
                        'unit' => $ingredient['unit'],
                    ];
                    $this->db->table('product_recipe')->insert($ingredientData);
                }

                // Update recipe_id in products table with the first ingredient's recipe_id
                $firstRecipeId = $this->db->query("SELECT recipe_id FROM product_recipe WHERE product_id = ? LIMIT 1", [$productId])->getRow()->recipe_id ?? $recipeId;
                $this->db->table('products')
                    ->where('product_id', $productId)
                    ->update(['recipe_id' => $firstRecipeId]);
            }

            // Calculate and insert product costs
            $directCost = floatval($data['direct_cost'] ?? 0);
            $overheadCost = $directCost * (floatval($data['overhead_cost_percentage'] ?? 0) / 100);
            $totalCost = $directCost + $overheadCost;
            $profitAmount = $totalCost * (floatval($data['profit_margin'] ?? 0) / 100);
            $sellingPrice = $totalCost + $profitAmount;

            $costData = [
                'product_id' => $productId,
                'direct_cost' => $directCost,
                'overhead_cost' => $overheadCost,
                'total_cost' => $totalCost,
                'selling_price' => $sellingPrice,
            ];

            $this->db->table('product_costs')->insert($costData);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return false;
            }

            return $productId;

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error adding product: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update existing product
     */
    public function updateProduct(int $productId, array $data): bool
    {
        $this->db->transStart();

        try {
            // Update product basic info
            $productData = [
                'product_name' => $data['product_name'],
                'product_description' => $data['product_description'] ?? '',
                'category' => $data['category'],
                'overhead_cost_percentage' => floatval($data['overhead_cost_percentage'] ?? 0),
                'profit_margin' => floatval($data['profit_margin'] ?? 0),
            ];

            $this->db->table('products')
                ->where('product_id', $productId)
                ->update($productData);

            // Update recipe ingredients if provided
            if (!empty($data['ingredients']) && is_array($data['ingredients'])) {
                // Delete existing recipe items
                $this->db->table('product_recipe')
                    ->where('product_id', $productId)
                    ->delete();

                // Insert new recipe items
                foreach ($data['ingredients'] as $ingredient) {
                    $ingredientData = [
                        'product_id' => $productId,
                        'material_id' => $ingredient['material_id'],
                        'quantity_needed' => floatval($ingredient['quantity']),
                        'unit' => $ingredient['unit'],
                    ];
                    $this->db->table('product_recipe')->insert($ingredientData);
                }
            }

            // Update product costs if provided
            if (isset($data['direct_cost'])) {
                $directCost = floatval($data['direct_cost']);
                $overheadCost = $directCost * (floatval($data['overhead_cost_percentage'] ?? 0) / 100);
                $totalCost = $directCost + $overheadCost;
                $profitAmount = $totalCost * (floatval($data['profit_margin'] ?? 0) / 100);
                $sellingPrice = $totalCost + $profitAmount;

                $costData = [
                    'direct_cost' => $directCost,
                    'overhead_cost' => $overheadCost,
                    'total_cost' => $totalCost,
                    'selling_price' => $sellingPrice,
                ];

                // Check if cost record exists
                $existingCost = $this->db->table('product_costs')
                    ->where('product_id', $productId)
                    ->get()
                    ->getRowArray();

                if ($existingCost) {
                    $this->db->table('product_costs')
                        ->where('product_id', $productId)
                        ->update($costData);
                } else {
                    $costData['product_id'] = $productId;
                    $this->db->table('product_costs')->insert($costData);
                }
            }

            $this->db->transComplete();

            return $this->db->transStatus() !== false;

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error updating product: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete product and related records
     */
    public function deleteProduct(int $productId): bool
    {
        try {
            // Foreign keys will handle cascading deletes for product_costs and product_recipe
            return $this->delete($productId);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting product: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get products by category
     */
    public function getProductsByCategory(string $category): array
    {
        return $this->where('category', $category)
            ->orderBy('product_id', 'DESC')
            ->findAll();
    }

    /**
     * Calculate direct cost from ingredients
     */
    public function calculateDirectCostFromIngredients(int $productId): float
    {
        $ingredients = $this->getProductRecipe($productId);
        $totalCost = 0;

        foreach ($ingredients as $ingredient) {
            $totalCost += floatval($ingredient['ingredient_cost'] ?? 0);
        }

        return $totalCost;
    }
}
