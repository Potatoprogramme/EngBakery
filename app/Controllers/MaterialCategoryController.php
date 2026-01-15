<?php

namespace App\Controllers;

class RawMaterialsController extends BaseController
{
    public function addCategory()
    {
        $data = $this->request->getPost([
            'category_name',
            'description',
        ]);

        if (empty($data['category_name']) || empty($data['description'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Category name or description cannot be empty.',
            ]);
        }

        $this->materialCategoryModel->insert()
    }
}
