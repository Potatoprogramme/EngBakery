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
        'billing_period',
        'quantity',
        'unit',
        'expense',
        'days',
        'cost_per_unit',
        'cost_per_day',
        'created_at',
        'billed_at',
    ];
}
