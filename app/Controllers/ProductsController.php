<?php

namespace App\Controllers;

use App\Models\ProductRecipeModel;
use App\Models\ProductCostModel;

class ProductsController extends BaseController
{
    protected $productRecipeModel;
    protected $productCostModel;

    public function __construct()
    {
        $this->productRecipeModel = new ProductRecipeModel();
        $this->productCostModel = new ProductCostModel();
    }

    public function test(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('Template/notification') .
                view('Products/ProductTest') .
                view('Template/Footer');
    }

    public function products(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('Template/notification') .
                view('Products/Product') .
                view('Template/Footer');
    }

    public function addProduct()
    {
        try {
            $data = $this->request->getJSON(true);
            
            // Log incoming data for debugging
            log_message('debug', 'AddProduct received data: ' . json_encode($data));
            
            // Handle both naming conventions (material_name/product_name, category_id/category)
            $productName = $data['product_name'] ?? $data['material_name'] ?? null;
            $category = $data['category'] ?? $data['category_id'] ?? null;
            
            // Validate required fields
            if (empty($productName)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Product name is required.',
                ]);
            }
            
            if (empty($category)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Product category is required.',
                ]);
            }
            
            // Check for ingredients
            $ingredients = $data['ingredients'] ?? [];
            if (empty($ingredients)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'At least one ingredient is required.',
                ]);
            }
            
            // Check if product name already exists
            if ($this->productModel->nameExists($productName)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'A product with this name already exists.',
                ]);
            }
            
            // Start database transaction
            $this->db->transStart();
            
            try {
                // Step 1: Insert the product first (recipe_id will be updated later)
                // NOTE: This requires running the fix_circular_fk_jan18.sql migration
                // to remove the FK constraint on products.recipe_id
                $productData = [
                    'recipe_id' => 0, // Will be updated after recipe is created
                    'category' => $category,
                    'product_name' => trim($productName),
                    'product_description' => trim($data['product_description'] ?? ''),
                    'overhead_cost_percentage' => floatval($data['overhead_cost_percentage'] ?? 0),
                    'profit_margin' => floatval($data['profit_margin'] ?? 0),
                ];
                
                $this->db->table('products')->insert($productData);
                $productId = $this->db->insertID();
                
                log_message('debug', 'Created product with ID: ' . $productId);
                
                // Step 2: Insert all recipe ingredients
                $firstRecipeId = null;
                foreach ($ingredients as $index => $ingredient) {
                    $ingredientData = [
                        'product_id' => $productId,
                        'material_id' => intval($ingredient['material_id']),
                        'quantity_needed' => floatval($ingredient['quantity']),
                        'unit' => $ingredient['unit'],
                    ];
                    $this->db->table('product_recipe')->insert($ingredientData);
                    
                    if ($index === 0) {
                        $firstRecipeId = $this->db->insertID();
                    }
                    
                    log_message('debug', 'Inserted ingredient: ' . json_encode($ingredientData));
                }
                
                // Step 3: Update product with first recipe ID (optional reference)
                if ($firstRecipeId) {
                    $this->db->table('products')
                        ->where('product_id', $productId)
                        ->update(['recipe_id' => $firstRecipeId]);
                    
                    log_message('debug', 'Updated product recipe_id to: ' . $firstRecipeId);
                }
                
                // Step 4: Insert product costs
                $costData = [
                    'product_id' => $productId,
                    'direct_cost' => floatval($data['direct_cost'] ?? 0),
                    'overhead_cost' => floatval($data['overhead_cost'] ?? 0),
                    'total_cost' => floatval($data['total_cost'] ?? 0),
                    'selling_price' => floatval($data['selling_price_overall'] ?? 0),
                ];
                
                $this->db->table('product_costs')->insert($costData);
                
                log_message('debug', 'Inserted product costs: ' . json_encode($costData));
                
                // Commit transaction
                $this->db->transComplete();
                
                if ($this->db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }
                
                log_message('info', 'Product added successfully with ID: ' . $productId);
                
                return $this->response->setStatusCode(201)->setJSON([
                    'success' => true,
                    'message' => 'Product added successfully.',
                    'data' => [
                        'product_id' => $productId,
                        'product_name' => $productName,
                        'category' => $category,
                        'ingredients_count' => count($ingredients),
                    ],
                ]);
                
            } catch (\Exception $e) {
                $this->db->transRollback();
                throw $e;
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error adding product: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'An error occurred while adding the product: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Get all products with details
     */
    public function getAllProducts()
    {
        try {
            $products = $this->productModel->getAllWithDetails();
            
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching products: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'An error occurred while fetching products.',
            ]);
        }
    }

    /**
     * Get single product by ID
     */
    public function getProduct($id)
    {
        try {
            if (!$id) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Product ID is required.',
                ]);
            }

            $product = $this->productModel->getProductById($id);
            
            if (!$product) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Product not found.',
                ]);
            }

            // Get recipe/ingredients
            $recipe = $this->productModel->getProductRecipe($id);
            $product['ingredients'] = $recipe;
            
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching product: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'An error occurred while fetching the product.',
            ]);
        }
    }

    /**
     * Update product
     */
    public function updateProduct()
    {
        try {
            $data = $this->request->getJSON(true);
            
            if (empty($data['product_id'])) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Product ID is required.',
                ]);
            }

            $productId = $data['product_id'];

            // Check if product exists
            $existingProduct = $this->productModel->getProductById($productId);
            if (!$existingProduct) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Product not found.',
                ]);
            }

            // Check if new name conflicts with another product
            if (!empty($data['product_name']) && $data['product_name'] !== $existingProduct['product_name']) {
                if ($this->productModel->nameExists($data['product_name'], $productId)) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'success' => false,
                        'message' => 'A product with this name already exists.',
                    ]);
                }
            }

            // Prepare update data
            $updateData = [
                'product_name' => trim($data['product_name'] ?? $existingProduct['product_name']),
                'product_description' => trim($data['product_description'] ?? $existingProduct['product_description']),
                'category' => $data['category'] ?? $existingProduct['category'],
                'overhead_cost_percentage' => floatval($data['overhead_cost_percentage'] ?? $existingProduct['overhead_cost_percentage']),
                'profit_margin' => floatval($data['profit_margin'] ?? $existingProduct['profit_margin']),
            ];

            if (isset($data['direct_cost'])) {
                $updateData['direct_cost'] = floatval($data['direct_cost']);
            }

            if (isset($data['ingredients'])) {
                $updateData['ingredients'] = $data['ingredients'];
            }

            // Update product
            $result = $this->productModel->updateProduct($productId, $updateData);
            
            if (!$result) {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to update product. Please try again.',
                ]);
            }
            
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Product updated successfully.',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error updating product: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'An error occurred while updating the product.',
            ]);
        }
    }

    /**
     * Delete product
     */
    public function deleteProduct($id)
    {
        try {
            if (!$id) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Product ID is required.',
                ]);
            }

            // Check if product exists
            $product = $this->productModel->getProductById($id);
            if (!$product) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Product not found.',
                ]);
            }

            // Delete product
            $result = $this->productModel->deleteProduct($id);
            
            if (!$result) {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete product. Please try again.',
                ]);
            }
            
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Product deleted successfully.',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting product: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'An error occurred while deleting the product.',
            ]);
        }
    }
}
