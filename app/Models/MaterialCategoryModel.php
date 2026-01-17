<?php
namespace App\Models;

use CodeIgniter\Model;

class MaterialCategoryModel extends Model
{
    protected $table = 'material_category';
    protected $primaryKey = 'category_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'category_name',
        'description',
        'label',
        'date_created',
    ];

    // Dates
    protected $useTimestamps = false;
    // protected $createdField = 'date_created';
    // protected $updatedField = 'date_updated';
    // protected $deletedField = 'date_deleted';


    public function saveCategory(array $data)
    {
        $categoryId = $data['category_id'] ?? null;
        $categoryData = [
            'category_name' => $data['category_name'],
            'description' => $data['category_description'] ?? $data['description'] ?? '',
            'label' => $data['label'] ?? '',
        ];

        if ($categoryId) {
            $this->update($categoryId, $categoryData);
            return ['action' => 'update', 'category_id' => $categoryId];
        }

        $this->insert($categoryData);
        return ['action' => 'insert', 'category_id' => $this->getInsertID()];
    }
    public function deleteCategoryById($id)
    {
        return (bool) $this->delete($id);
    }

    public function updateCategoryById($id, array $data)
    {
        return (bool) $this->update($id, $data);
    }

    public function getAllCategories()
    {
        return $this->findAll();
    }
}