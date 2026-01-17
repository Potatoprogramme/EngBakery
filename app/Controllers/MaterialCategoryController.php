<?php

namespace App\Controllers;

class MaterialCategoryController extends BaseController
{
    public function testView(): string
    {
        return view('TestViews/MaterialTestView');
    }

    /**
     * Add or Update category (AJAX)
     */
    public function addCategory()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['category_name'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Category name is required.',
            ]);
        }

        // Delegate insert/update to model
        $result = $this->materialCategoryModel->saveCategory($data);

        if ($result['action'] === 'update') {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Category updated successfully.',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Category added successfully.',
            'category_id' => $result['category_id'],
        ]);
    }

    /**
     * Delete category (AJAX)
     */
    public function deleteCategory()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['category_id'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Category ID is required.',
            ]);
        }

        if ($this->materialCategoryModel->find($data['category_id'])) {
            $this->materialCategoryModel->deleteCategoryById($data['category_id']);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Category deleted successfully.',
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Category not found.',
            ]);
        }
    }

    /**
     * Update category (AJAX) - from main
     */
    public function updateCategory()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['category_id'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Category ID is required.',
            ]);
        }

        $updateData = [
            'category_name' => $data['category_name'] ?? null,
            'description' => $data['description'] ?? $data['category_description'] ?? null,
        ];

        if ($this->materialCategoryModel->find($data['category_id'])) {
            $this->materialCategoryModel->updateCategoryById($data['category_id'], $updateData);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Category updated successfully.',
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Category not found.',
            ]);
        }
    }

    /**
     * Fetch all categories (AJAX) - from main
     */
    public function fetchAllCategories()
    {
        $categories = $this->materialCategoryModel->getAllCategories();

        return $this->response->setJSON([
            'success' => true,
            'data' => $categories,
        ]);
    }
}
