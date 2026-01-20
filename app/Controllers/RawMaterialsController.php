<?php

namespace App\Controllers;

class RawMaterialsController extends BaseController
{
    public function rawMaterial(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('Template/notification') .
                view('RawMaterials/RawMaterial') .
                view('Template/Footer');
    }

    /**
     * Get all categories (AJAX)
     */
    public function getCategories()
    {
        $categories = $this->materialCategoryModel->findAll();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get all raw materials with details (AJAX)
     */
    public function getAll()
    {
        $materials = $this->rawMaterialsModel->getAllWithDetails();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $materials
        ]);
    }

    /**
     * Add new raw material (AJAX)
     */
    public function addRawMaterial()
    {
        $data = $this->request->getJSON(true);
        
        // Validate required fields (total_cost can be 0 for free ingredients)
        if (empty($data['material_name']) || empty($data['category_id']) || 
            empty($data['unit']) || empty($data['material_quantity']) || 
            (!isset($data['total_cost']) || $data['total_cost'] === '')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }

        try {
            $materialId = $this->rawMaterialsModel->addMaterial($data);

            if ($materialId) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Material added successfully.',
                    'material_id' => $materialId
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add material. Transaction error.'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check if material name already exists (AJAX)
     */
    public function checkMaterialName()
    {
        $data = $this->request->getJSON(true);
        
        if (empty($data['material_name'])) {
            return $this->response->setJSON([
                'exists' => false
            ]);
        }

        $materialId = !empty($data['material_id']) ? intval($data['material_id']) : null;
        $exists = $this->rawMaterialsModel->nameExists($data['material_name'], $materialId);
        
        return $this->response->setJSON([
            'exists' => $exists
        ]);
    }

    /**
     * Get single raw material by ID (AJAX)
     */
    public function getMaterial($id = null)
    {
        if (empty($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Material ID is required.'
            ]);
        }

        $material = $this->rawMaterialsModel->getMaterialById($id);
        
        if (!$material) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Material not found.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $material
        ]);
    }

    /**
     * Update raw material (AJAX)
     */
    public function updateRawMaterial()
    {
        $data = $this->request->getJSON(true);
        
        // Validate required fields (total_cost can be 0 for free ingredients)
        if (empty($data['material_id']) || empty($data['material_name']) || empty($data['category_id']) || 
            empty($data['unit']) || empty($data['material_quantity']) || 
            (!isset($data['total_cost']) || $data['total_cost'] === '')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }

        try {
            $success = $this->rawMaterialsModel->updateMaterial($data);

            if ($success) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Material updated successfully.'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update material. Transaction error.'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete raw material (AJAX)
     */
    public function delete($id = null)
    {
        if (empty($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Material ID is required.'
            ]);
        }

        $material = $this->rawMaterialsModel->find($id);
        
        if (!$material) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Material not found.'
            ]);
        }

        try {
            $this->rawMaterialsModel->deleteMaterial($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Material deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}