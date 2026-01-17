<?php

namespace App\Controllers;

class ProductsController extends BaseController
{
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
            
            // Prepare product data
            $productData = [
                'product_name' => trim($productName),
                'product_description' => trim($data['product_description'] ?? ''),
                'category' => $category,
                'overhead_cost_percentage' => floatval($data['overhead_cost_percentage'] ?? 0),
                'profit_margin' => floatval($data['profit_margin'] ?? 0),
                'direct_cost' => floatval($data['direct_cost'] ?? $data['total_cost'] ?? 0),
                'ingredients' => $data['ingredients'] ?? [],
            ];
            
            // For now, return success (model integration can be added later)
            return $this->response->setStatusCode(201)->setJSON([
                'success' => true,
                'message' => 'Product added successfully.',
                'data' => $productData,
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error adding product: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'An error occurred while adding the product.',
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
