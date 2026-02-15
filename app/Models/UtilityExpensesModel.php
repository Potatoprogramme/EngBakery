<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilityExpensesModel extends Model
{
    protected $table = 'utility_expenses';
    protected $primaryKey = 'u_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'type',
        'quantity',
        'unit',
        'expense',
        'cost_per_unit',
        'created_at',
        'billed_at',
    ];
}