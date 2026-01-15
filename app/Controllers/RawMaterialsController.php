<?php

namespace App\Controllers;

use App\Models\RawMaterialsModel;
use App\Models\RawMaterialCostModel;
use App\Models\RawMaterialStockModel;
use App\Models\MaterialCategoryModel;

class RawMaterialsController extends BaseController
{
    protected RawMaterialsModel $materialModel;
    protected RawMaterialCostModel $costModel;
    protected RawMaterialStockModel $stockModel;
    protected MaterialCategoryModel $categoryModel;

    public function __construct()
    {
        $this->materialModel = new RawMaterialsModel();
        $this->costModel     = new RawMaterialCostModel();
        $this->stockModel    = new RawMaterialStockModel();
        $this->categoryModel = new MaterialCategoryModel();
    }

    /**
     * Raw Material main page with Add Material modal
     */
    public function rawMaterial(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('RawMaterial') .
                view('Template/Footer');
    }

    /**
     * API: Get all categories (JSON response)
     */
    public function getCategories()
    {
        $categories = $this->categoryModel->findAll();
        
        return $this->response->setJSON([
            'success' => true,
            'data'    => $categories
        ]);
    }

    /**
     * API: Get all raw materials with details (JSON response)
     */
    public function getAll()
    {
        $materials = $this->materialModel->getAllWithDetails();
        
        return $this->response->setJSON([
            'success' => true,
            'data'    => $materials
        ]);
    }

    /**
     * API: Add raw material via AJAX (JSON response)
     */
    public function addRawMaterial()
    {
        // Get JSON input
        $json = $this->request->getJSON(true);
        
        // If not JSON, try POST data
        if (empty($json)) {
            $json = $this->request->getPost();
        }

        // Validation
        if (empty($json['material_name']) || empty($json['material_quantity']) || 
            empty($json['unit']) || empty($json['category_id']) || empty($json['total_cost'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $db->query('SET FOREIGN_KEY_CHECKS = 0');

            $totalCost        = (float) $json['total_cost'];
            $materialQuantity = (float) $json['material_quantity'];
            $costPerUnit      = $this->materialModel->calculateCostPerUnit($totalCost, $materialQuantity);

            // Insert raw material
            $this->materialModel->insert([
                'cost_id'           => 0,
                'stock_id'          => 0,
                'category_id'       => $json['category_id'],
                'material_name'     => $json['material_name'],
                'material_quantity' => $materialQuantity,
                'unit'              => $json['unit'],
            ], false);
            
            $materialId = $this->materialModel->getInsertID();

            // Insert cost record
            $this->costModel->insert([
                'material_id'   => $materialId,
                'cost_per_unit' => $costPerUnit,
            ], false);
            
            $costId = $this->costModel->getInsertID();

            // Insert stock record
            $this->stockModel->insert([
                'material_id'      => $materialId,
                'current_quantity' => $materialQuantity,
            ], false);
            
            $stockId = $this->stockModel->getInsertID();

            // Update material with cost_id and stock_id
            $this->materialModel->update($materialId, [
                'cost_id'  => $costId,
                'stock_id' => $stockId,
            ]);

            $db->query('SET FOREIGN_KEY_CHECKS = 1');
            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create material.'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Material created successfully.',
                'data'    => ['id' => $materialId]
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Delete material via AJAX (JSON response)
     */
    public function delete(int $id)
    {
        $material = $this->materialModel->find($id);

        if (!$material) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Material not found.'
            ]);
        }

        if ($this->materialModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Material deleted successfully.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete material.'
        ]);
    }
}
