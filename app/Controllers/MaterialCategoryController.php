<?php

namespace App\Controllers;

class MaterialCategoryController extends BaseController
{
    public function testView()
    {
        return view('TestViews/MaterialTestView');
    }
    public function addCategory()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['category_name']) || empty($data['description'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Category name or description cannot be empty.',
            ]);
        }

        $this->materialCategoryModel->insert([
            'category_name' => $data['category_name'],
            'description' => $data['description'],
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Category added successfully.',
            'data' => $data,
        ]);
    }
}
