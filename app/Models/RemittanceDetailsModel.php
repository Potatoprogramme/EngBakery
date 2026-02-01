<?php
namespace App\Models;

use CodeIgniter\Model;

class RemittanceDetailsModel extends Model
{
    protected $table = 'remittance_details';
    protected $primaryKey = 'remittance_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'cashier',
        'remittance_date',
        'shift_start',
        'shift_end',
        'amount_enclosed',
        'total_online_revenue',
        'cash_out',
        'cashout_reason',
        'bakery_sales',
        'coffee_sales',
        'total_sales', 
        'overage_shortage',
    ];
}