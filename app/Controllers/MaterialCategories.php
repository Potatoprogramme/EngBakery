<?php

namespace App\Controllers;

use App\Models\MaterialCategoryModel;

class MaterialCategories extends BaseController
{
    protected MaterialCategoryModel $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new MaterialCategoryModel();
    }

    /**
     * Display list of all categories
     */
    public function index(): string
    {
        $data = [
            'title'      => 'Material Categories',
            'categories' => $this->categoryModel->getAllWithCount(),
        ];

        return view('MaterialCategories/Index', $data);
    }

    /**
     * Show form to create new category
     */
    public function create(): string
    {
        $data = [
            'title'      => 'Add Category',
            'validation' => \Config\Services::validation(),
        ];

        return view('MaterialCategories/Create', $data);
    }

    /**
     * Store new category in database
     */
    public function store()
    {
        $rules = [
            'category_name' => 'required|min_length[2]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->insert([
            'category_name' => $this->request->getPost('category_name'),
            'description'   => $this->request->getPost('description'),
        ]);

        return redirect()->to(site_url('MaterialCategories'))->with('success', 'Category created successfully.');
    }

    /**
     * Show form to edit category
     */
    public function edit(int $id): string
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Category not found');
        }

        $data = [
            'title'      => 'Edit ' . $category['category_name'],
            'category'   => $category,
            'validation' => \Config\Services::validation(),
        ];

        return view('MaterialCategories/Edit', $data);
    }

    /**
     * Update category in database
     */
    public function update(int $id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Category not found');
        }

        $rules = [
            'category_name' => 'required|min_length[2]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->update($id, [
            'category_name' => $this->request->getPost('category_name'),
            'description'   => $this->request->getPost('description'),
        ]);

        return redirect()->to(site_url('MaterialCategories'))->with('success', 'Category updated successfully.');
    }

    /**
     * Delete category from database
     */
    public function delete(int $id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Category not found');
        }

        if ($this->categoryModel->delete($id)) {
            return redirect()->to(site_url('MaterialCategories'))->with('success', 'Category deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete category.');
    }
}
