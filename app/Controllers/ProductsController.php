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
        $data = $this->request->getPost();
    }
    
    public function fetchAllCategories()
    {
        try {
            $categories = $this->productCategoryModel->getAllCategories();
            
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'An error occurred while fetching categories.',
            ]);
        }
    }

    public function addCategory()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['category_name'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Category name is required.',
            ]);
        }

        // Prepare category data with trimmed values
        $categoryData = [
            'category_name' => trim($data['category_name']),
            'description' => trim($data['category_description'] ?? ''),
        ];

        try {
            // Check if updating or adding
            if (!empty($data['category_id'])) {
                // Update existing category
                $result = $this->productCategoryModel->updateCategory($data['category_id'], $categoryData);
                
                if (!$result) {
                    return $this->response->setStatusCode(500)->setJSON([
                        'success' => false,
                        'message' => 'Failed to update category. Please try again.',
                    ]);
                }
                
                $categoryData['prod_cat_id'] = $data['category_id'];
                
                return $this->response->setStatusCode(200)->setJSON([
                    'success' => true,
                    'message' => 'Category updated successfully.',
                    'data' => $categoryData,
                ]);
            } else {
                // Insert new category
                $insertId = $this->productCategoryModel->addCategory($categoryData);
                
                if (!$insertId) {
                    return $this->response->setStatusCode(500)->setJSON([
                        'success' => false,
                        'message' => 'Failed to add category. Please try again.',
                    ]);
                }
                
                $categoryData['prod_cat_id'] = $insertId;
                
                return $this->response->setStatusCode(201)->setJSON([
                    'success' => true,
                    'message' => 'Category added successfully.',
                    'data' => $categoryData,
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'An error occurred while managing the category.',
            ]);
        }
    }

    public function deleteCategory($id = null)
    {
        try {
            if ($id === null) {
                $data = $this->request->getJSON(true);
                $id = $data['category_id'] ?? null;
            }
            
            if (!$id) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Category ID is required.',
                ]);
            }
            
            $result = $this->productCategoryModel->deleteCategory($id);
            
            if (!$result) {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete category. Please try again.',
                ]);
            }
            
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Category deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'An error occurred while deleting the category.',
            ]);
        }
    }
}
