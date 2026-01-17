<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductCategoryModel extends Model
{
    protected $table = 'product_category';
    protected $primaryKey = 'prod_cat_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'category_name',
        'description',
        'date_created',
    ];

    // Dates
    protected $useTimestamps = false;

    public function getAllCategories()
    {
        return $this->findAll();
    }

    public function getCategoryById($id)
    {
        return $this->find($id);
    }
    
    public function addCategory($data)
    {
        return $this->insert($data);
    }

    public function deleteCategory($id)
    {
        return $this->delete($id);
    }

    public function updateCategory($id, $data)
    {
        return $this->update($id, $data);
    }
}