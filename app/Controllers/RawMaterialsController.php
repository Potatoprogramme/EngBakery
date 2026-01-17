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
        $query = $this->db->query("
            SELECT 
                rm.material_id,
                rm.material_name,
                rm.material_quantity,
                rm.unit,
                rm.category_id,
                mc.category_name,
                rmc.cost_per_unit
            FROM raw_materials rm
            LEFT JOIN material_category mc ON rm.category_id = mc.category_id
            LEFT JOIN raw_material_cost rmc ON rm.material_id = rmc.material_id
            ORDER BY rm.material_id DESC
        ");
        
        $materials = $query->getResultArray();
        
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
        
        // Validate required fields
        if (empty($data['material_name']) || empty($data['category_id']) || 
            empty($data['unit']) || empty($data['material_quantity']) || empty($data['total_cost'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }

        $this->db->transStart();

        try {
            // Calculate cost per unit
            $costPerUnit = floatval($data['total_cost']) / floatval($data['material_quantity']);

            // Disable foreign key checks temporarily
            $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

            // 1. First insert into raw_material_cost
            $this->db->query("INSERT INTO raw_material_cost (material_id, cost_per_unit) VALUES (0, ?)", [$costPerUnit]);
            $costId = $this->db->insertID();

            // 2. Insert into raw_material_stock
            $this->db->query("INSERT INTO raw_material_stock (material_id, current_quantity) VALUES (0, ?)", [floatval($data['material_quantity'])]);
            $stockId = $this->db->insertID();

            // 3. Insert into raw_materials
            $this->db->query("INSERT INTO raw_materials (cost_id, stock_id, category_id, material_name, material_quantity, unit) VALUES (?, ?, ?, ?, ?, ?)", [
                $costId,
                $stockId,
                intval($data['category_id']),
                $data['material_name'],
                floatval($data['material_quantity']),
                $data['unit']
            ]);
            $materialId = $this->db->insertID();

            // 4. Update cost and stock tables with the correct material_id
            $this->db->query("UPDATE raw_material_cost SET material_id = ? WHERE cost_id = ?", [$materialId, $costId]);
            $this->db->query("UPDATE raw_material_stock SET material_id = ? WHERE stock_id = ?", [$materialId, $stockId]);

            // Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to add material. Transaction error.'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Material added successfully.',
                'material_id' => $materialId
            ]);

        } catch (\Exception $e) {
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
            $this->db->transRollback();
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

        $builder = $this->db->table('raw_materials');
        $builder->where('LOWER(material_name)', strtolower(trim($data['material_name'])));
        
        // If editing, exclude current material from check
        if (!empty($data['material_id'])) {
            $builder->where('material_id !=', intval($data['material_id']));
        }
        
        $count = $builder->countAllResults();
        
        return $this->response->setJSON([
            'exists' => $count > 0
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

        $query = $this->db->query("
            SELECT 
                rm.material_id,
                rm.material_name,
                rm.material_quantity,
                rm.unit,
                rm.category_id,
                rm.cost_id,
                rm.stock_id,
                mc.category_name,
                rmc.cost_per_unit,
                (rm.material_quantity * rmc.cost_per_unit) as total_cost
            FROM raw_materials rm
            LEFT JOIN material_category mc ON rm.category_id = mc.category_id
            LEFT JOIN raw_material_cost rmc ON rm.material_id = rmc.material_id
            WHERE rm.material_id = ?
        ", [$id]);
        
        $material = $query->getRowArray();
        
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
        
        // Validate required fields
        if (empty($data['material_id']) || empty($data['material_name']) || empty($data['category_id']) || 
            empty($data['unit']) || empty($data['material_quantity']) || empty($data['total_cost'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }

        $this->db->transStart();

        try {
            // Calculate cost per unit
            $costPerUnit = floatval($data['total_cost']) / floatval($data['material_quantity']);
            $materialId = intval($data['material_id']);

            // Update raw_materials
            $this->db->query("UPDATE raw_materials SET category_id = ?, material_name = ?, material_quantity = ?, unit = ? WHERE material_id = ?", [
                intval($data['category_id']),
                $data['material_name'],
                floatval($data['material_quantity']),
                $data['unit'],
                $materialId
            ]);

            // Update raw_material_cost
            $this->db->query("UPDATE raw_material_cost SET cost_per_unit = ? WHERE material_id = ?", [$costPerUnit, $materialId]);

            // Update raw_material_stock
            $this->db->query("UPDATE raw_material_stock SET current_quantity = ? WHERE material_id = ?", [floatval($data['material_quantity']), $materialId]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update material. Transaction error.'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Material updated successfully.'
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
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

        $this->db->transStart();

        try {
             $this->rawMaterialsModel->delete($id);

            $this->db->transComplete();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Material deleted successfully.'
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}