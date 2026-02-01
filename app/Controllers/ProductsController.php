<?php

namespace App\Controllers;

class ProductsController extends BaseController
{
    private function getSessionData()
    {
        $session = session();
        return [
            'user_id' => $session->get('id'),
            'email' => $session->get('email'),
            'username' => $session->get('username'),
            'employee_type' => $session->get('employee_type'),
            'name' => $session->get('name'),
            'is_logged_in' => $session->get('is_logged_in'),
        ];
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
        $data = $this->getSessionData();
        return  view('Template/Header', $data).
                view('Template/SideNav', $data) . 
                view('Template/notification', $data) .
                view('Products/Product', $data) .
                view('Template/Footer', $data);
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
            
            // Check for ingredients (not required for grocery category)
            $ingredients = $data['ingredients'] ?? [];
            if (empty($ingredients) && strtolower($category) !== 'grocery') {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'At least one ingredient is required.',
                ]);
            }
            
            // For grocery category, validate direct_cost
            if (strtolower($category) === 'grocery') {
                $directCost = floatval($data['direct_cost'] ?? 0);
                if ($directCost <= 0) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'success' => false,
                        'message' => 'Product price is required for grocery items.',
                    ]);
                }
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
                // Step 1: Insert the product first
                $productData = [
                    'category' => $category,
                    'product_name' => trim($productName),
                    'product_description' => trim($data['product_description'] ?? ''),
                ];
                
                $this->db->table('products')->insert($productData);
                $productId = $this->db->insertID();
                
                log_message('debug', 'Created product with ID: ' . $productId);
                
                // Step 2: Insert all recipe ingredients
                foreach ($ingredients as $index => $ingredient) {
                    $ingredientData = [
                        'product_id' => $productId,
                        'material_id' => intval($ingredient['material_id']),
                        'quantity_needed' => floatval($ingredient['quantity']),
                        'unit' => $ingredient['unit'],
                    ];
                    $this->db->table('product_recipe')->insert($ingredientData);
                    
                    log_message('debug', 'Inserted ingredient: ' . json_encode($ingredientData));
                }
                
                // Step 3: Insert product costs
                $costData = [
                    'product_id' => $productId,
                    'direct_cost' => floatval($data['direct_cost'] ?? 0),
                    'overhead_cost_percentage' => floatval($data['overhead_cost_percentage'] ?? 0),
                    'overhead_cost_amount' => floatval($data['overhead_cost_amount'] ?? 0),
                    'combined_recipe_cost' => floatval($data['combined_recipe_cost'] ?? 0),
                    'profit_margin_percentage' => floatval($data['profit_margin_percentage'] ?? 0),
                    'profit_amount' => floatval($data['profit_amount'] ?? 0),
                    'total_cost' => floatval($data['total_cost'] ?? 0),
                    'selling_price' => floatval($data['selling_price_overall'] ?? 0),
                    'selling_price_per_tray' => floatval($data['selling_price_per_tray'] ?? 0),
                    'selling_price_per_piece' => floatval($data['selling_price_per_piece'] ?? 0),
                    'yield_grams' => floatval($data['yield_grams'] ?? 0),
                    'trays_per_yield' => intval($data['trays_per_yield'] ?? 0),
                    'pieces_per_yield' => intval($data['pieces_per_yield'] ?? 0),
                    'grams_per_tray' => floatval($data['grams_per_tray'] ?? 0),
                    'grams_per_piece' => floatval($data['grams_per_piece'] ?? 0),
                ];
                
                $this->db->table('product_costs')->insert($costData);
                
                log_message('debug', 'Inserted product costs: ' . json_encode($costData));

                // Step 4: Insert combined recipes if provided
                $combinedRecipes = $data['combined_recipes'] ?? [];
                if (!empty($combinedRecipes)) {
                    foreach ($combinedRecipes as $combinedRecipe) {
                        $combinedRecipeData = [
                            'product_id' => $productId,
                            'source_product_id' => intval($combinedRecipe['id'] ?? $combinedRecipe['source_product_id']),
                            'grams_per_piece' => floatval($combinedRecipe['gramsPerPiece'] ?? $combinedRecipe['grams_per_piece'] ?? 0),
                            'cost_per_gram' => floatval($combinedRecipe['costPerUnit'] ?? $combinedRecipe['cost_per_gram'] ?? 0),
                            'total_cost' => floatval($combinedRecipe['totalCost'] ?? $combinedRecipe['total_cost'] ?? 0),
                        ];
                        $this->db->table('product_combined_recipes')->insert($combinedRecipeData);
                        
                        log_message('debug', 'Inserted combined recipe: ' . json_encode($combinedRecipeData));
                    }
                }
                
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

            // Get combined recipes
            $combinedRecipes = $this->productCombinedRecipeModel->getCombinedRecipesByProductId($id);
            $product['combined_recipes'] = $combinedRecipes;
            
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
            ];

            if (isset($data['direct_cost'])) {
                $updateData['direct_cost'] = floatval($data['direct_cost']);
            }

            if (isset($data['overhead_cost_percentage'])) {
                $updateData['overhead_cost_percentage'] = floatval($data['overhead_cost_percentage']);
            }

            if (isset($data['overhead_cost_amount'])) {
                $updateData['overhead_cost_amount'] = floatval($data['overhead_cost_amount']);
            }

            if (isset($data['combined_recipe_cost'])) {
                $updateData['combined_recipe_cost'] = floatval($data['combined_recipe_cost']);
            }

            if (isset($data['profit_margin_percentage'])) {
                $updateData['profit_margin_percentage'] = floatval($data['profit_margin_percentage']);
            }

            if (isset($data['profit_amount'])) {
                $updateData['profit_amount'] = floatval($data['profit_amount']);
            }

            if (isset($data['total_cost'])) {
                $updateData['total_cost'] = floatval($data['total_cost']);
            }

            if (isset($data['selling_price_overall'])) {
                $updateData['selling_price'] = floatval($data['selling_price_overall']);
            }

            if (isset($data['selling_price_per_tray'])) {
                $updateData['selling_price_per_tray'] = floatval($data['selling_price_per_tray']);
            }

            if (isset($data['selling_price_per_piece'])) {
                $updateData['selling_price_per_piece'] = floatval($data['selling_price_per_piece']);
            }

            if (isset($data['yield_grams'])) {
                $updateData['yield_grams'] = floatval($data['yield_grams']);
            }

            if (isset($data['trays_per_yield'])) {
                $updateData['trays_per_yield'] = intval($data['trays_per_yield']);
            }

            if (isset($data['pieces_per_yield'])) {
                $updateData['pieces_per_yield'] = intval($data['pieces_per_yield']);
            }

            if (isset($data['grams_per_tray'])) {
                $updateData['grams_per_tray'] = floatval($data['grams_per_tray']);
            }

            if (isset($data['grams_per_piece'])) {
                $updateData['grams_per_piece'] = floatval($data['grams_per_piece']);
            }

            if (isset($data['ingredients'])) {
                $updateData['ingredients'] = $data['ingredients'];
            }

            // Handle combined recipes
            if (isset($data['combined_recipes'])) {
                $updateData['combined_recipes'] = $data['combined_recipes'];
            }

            // Update product
            $result = $this->productModel->updateProduct($productId, $updateData);

            // Update combined recipes separately (delete and re-insert)
            if (isset($data['combined_recipes'])) {
                $this->productCombinedRecipeModel->saveCombinedRecipes($productId, $data['combined_recipes']);
            }
            
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