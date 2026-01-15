<?php

namespace App\Controllers;

class MaterialCategoryController extends BaseController
{
    public function testView(): string
    {
        return view('TestViews/MaterialTestView');
    }
    public function addCategory()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['category_name']) || empty($data['description'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'status' => 'error',
                'message' => 'Category name or description cannot be empty.',
            ]);
        }

        $this->materialCategoryModel->insert([
            'category_name' => $data['category_name'],
            'description' => $data['description'],
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Category added successfully.',
            'data' => $data,
        ]);
    }

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
            $this->materialCategoryModel->delete($data['category_id']);
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
}
