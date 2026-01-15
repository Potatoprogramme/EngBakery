<?php

namespace App\Controllers;

use App\Models\RawMaterialModel;
use App\Models\RawMaterialCostModel;
use App\Models\RawMaterialStockModel;
use App\Models\MaterialCategoryModel;

class RawMaterials extends BaseController
{
    protected RawMaterialModel $materialModel;
    protected RawMaterialCostModel $costModel;
    protected RawMaterialStockModel $stockModel;
    protected MaterialCategoryModel $categoryModel;

    public function __construct()
    {
        $this->materialModel = new RawMaterialModel();
        $this->costModel     = new RawMaterialCostModel();
        $this->stockModel    = new RawMaterialStockModel();
        $this->categoryModel = new MaterialCategoryModel();
    }

    /**
     * Display list of all raw materials
     */
    public function index(): string
    {
        $data = [
            'title'     => 'Raw Materials',
            'materials' => $this->materialModel->getAllWithDetails(),
        ];

        return view('RawMaterials/Index', $data);
    }

    /**
     * Show form to create new material
     */
    public function create(): string
    {
        $data = [
            'title'      => 'Add Raw Material',
            'categories' => $this->categoryModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('RawMaterials/Create', $data);
    }

    /**
     * Store new material in database
     */
    public function store()
    {
        // Validation rules
        $rules = [
            'material_name'     => 'required|min_length[2]|max_length[255]',
            'material_quantity' => 'required|integer|greater_than[0]',
            'unit'              => 'required|max_length[255]',
            'category_id'       => 'required|integer',
            'total_cost'        => 'required|decimal|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Disable FK checks for circular reference handling
            $db->query('SET FOREIGN_KEY_CHECKS = 0');

            // Calculate cost per unit
            $totalCost        = (float) $this->request->getPost('total_cost');
            $materialQuantity = (float) $this->request->getPost('material_quantity');
            $costPerUnit      = $this->materialModel->calculateCostPerUnit($totalCost, $materialQuantity);
            $initialStock     = (float) $this->request->getPost('initial_stock') ?: $materialQuantity;

            // Insert material first (without cost_id and stock_id)
            $this->materialModel->insert([
                'cost_id'           => 0,
                'stock_id'          => 0,
                'category_id'       => $this->request->getPost('category_id'),
                'material_name'     => $this->request->getPost('material_name'),
                'material_quantity' => $materialQuantity,
                'unit'              => $this->request->getPost('unit'),
            ], false); // false = don't return insert ID yet
            
            $materialId = $this->materialModel->getInsertID();

            // Create cost record with valid material_id
            $this->costModel->insert([
                'material_id'   => $materialId,
                'cost_per_unit' => $costPerUnit,
            ], false);
            
            $costId = $this->costModel->getInsertID();

            // Create stock record with valid material_id
            $this->stockModel->insert([
                'material_id'      => $materialId,
                'current_quantity' => $initialStock,
            ], false);
            
            $stockId = $this->stockModel->getInsertID();

            // Update material with correct cost_id and stock_id
            $this->materialModel->update($materialId, [
                'cost_id'  => $costId,
                'stock_id' => $stockId,
            ]);

            // Re-enable FK checks
            $db->query('SET FOREIGN_KEY_CHECKS = 1');

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Failed to create material.');
            }

            return redirect()->to(site_url('RawMaterials'))->with('success', 'Material created successfully.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show single material details
     */
    public function show(int $id): string
    {
        $material = $this->materialModel->getWithDetails($id);

        if (!$material) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Material not found');
        }

        $data = [
            'title'    => $material['material_name'],
            'material' => $material,
        ];

        return view('RawMaterials/Show', $data);
    }

    /**
     * Show form to edit material
     */
    public function edit(int $id): string
    {
        $material = $this->materialModel->getWithDetails($id);

        if (!$material) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Material not found');
        }

        $data = [
            'title'      => 'Edit ' . $material['material_name'],
            'material'   => $material,
            'categories' => $this->categoryModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('RawMaterials/Edit', $data);
    }

    /**
     * Update material in database
     */
    public function update(int $id)
    {
        $material = $this->materialModel->find($id);

        if (!$material) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Material not found');
        }

        $rules = [
            'material_name'     => 'required|min_length[2]|max_length[255]',
            'material_quantity' => 'required|integer|greater_than[0]',
            'unit'              => 'required|max_length[255]',
            'category_id'       => 'required|integer',
            'total_cost'        => 'required|decimal|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Recalculate cost per unit
            $totalCost        = (float) $this->request->getPost('total_cost');
            $materialQuantity = (int) $this->request->getPost('material_quantity');
            $costPerUnit      = $this->materialModel->calculateCostPerUnit($totalCost, $materialQuantity);

            // Update material
            $this->materialModel->update($id, [
                'category_id'       => $this->request->getPost('category_id'),
                'material_name'     => $this->request->getPost('material_name'),
                'material_quantity' => $materialQuantity,
                'unit'              => $this->request->getPost('unit'),
            ]);

            // Update cost
            $this->costModel->update($material['cost_id'], [
                'cost_per_unit' => $costPerUnit,
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Failed to update material.');
            }

            return redirect()->to(site_url('RawMaterials'))->with('success', 'Material updated successfully.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Delete material from database
     */
    public function delete(int $id)
    {
        $material = $this->materialModel->find($id);

        if (!$material) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Material not found');
        }

        if ($this->materialModel->delete($id)) {
            return redirect()->to(site_url('RawMaterials'))->with('success', 'Material deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete material.');
    }

    /**
     * API: Get all materials as JSON
     */
    public function apiGetAll()
    {
        $materials = $this->materialModel->getAllWithDetails();
        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $materials,
        ]);
    }

    /**
     * API: Get material by ID as JSON
     */
    public function apiGet(int $id)
    {
        $material = $this->materialModel->getWithDetails($id);

        if (!$material) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Material not found',
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $material,
        ]);
    }
}