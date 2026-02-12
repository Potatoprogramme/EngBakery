<?php

namespace App\Controllers;

class RawMaterialsController extends BaseController
{
    public function rawMaterial(): string
    {
        $data = $this->getSessionData();

        return  view('Template/Header', $data).
                view('Template/SideNav', $data) . 
                view('Template/notification', $data) .
                view('RawMaterials/RawMaterial', $data) .
                view('Template/Footer', $data);
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
        
        // Validate required fields (allow numeric zero values)
        if (!isset($data['material_name']) || trim((string)$data['material_name']) === '' ||
            !isset($data['category_id']) || (string)$data['category_id'] === '' ||
            !isset($data['unit']) || trim((string)$data['unit']) === '' ||
            !array_key_exists('initial_quantity', $data) || (string)$data['initial_quantity'] === '' ||
            !array_key_exists('total_cost', $data) || (string)$data['total_cost'] === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }

        // Additional numeric validation: must be >= 0
        if (!is_numeric($data['initial_quantity']) || floatval($data['initial_quantity']) < 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Quantity must be a number greater than or equal to 0.'
            ]);
        }

        if (!is_numeric($data['total_cost']) || floatval($data['total_cost']) < 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Total cost must be a number greater than or equal to 0.'
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
        
        // Validate required fields — quantity is no longer editable (managed by deductions/restock)
        if (!isset($data['material_id']) || (string)$data['material_id'] === '' ||
            !isset($data['material_name']) || trim((string)$data['material_name']) === '' ||
            !isset($data['category_id']) || (string)$data['category_id'] === '' ||
            !isset($data['unit']) || trim((string)$data['unit']) === '' ||
            !array_key_exists('total_cost', $data) || (string)$data['total_cost'] === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }

        if (!is_numeric($data['total_cost']) || floatval($data['total_cost']) < 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Total cost must be a number greater than or equal to 0.'
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
     * Restock raw material — add quantity (AJAX)
     */
    public function restockMaterial()
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['material_id']) || (string)$data['material_id'] === '' ||
            !isset($data['restock_qty']) || (string)$data['restock_qty'] === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Material ID and restock quantity are required.'
            ]);
        }

        $addQty = floatval($data['restock_qty']);
        if ($addQty <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Restock quantity must be greater than 0.'
            ]);
        }

        try {
            $success = $this->rawMaterialsModel->restockMaterial(
                intval($data['material_id']),
                $addQty
            );

            if ($success) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Material restocked successfully.'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to restock material.'
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