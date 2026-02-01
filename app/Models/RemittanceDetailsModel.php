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
        'outlet_name',
        'remittance_date',
        'shift_start',
        'shift_end',
        'amount_enclosed',
        'total_online_revenue',
        'cash_out',
        'cashout_reason',
        'bakery_sales',
        'coffee_sales',
        'grocery_sales',
        'total_sales',
        'variance_amount',
        'is_short',
    ];

    public function getAllRemittances(): array
    {
        return $this->select("remittance_details.*, CONCAT(users.firstname, ' ', COALESCE(users.middlename, ''), ' ', users.lastname) AS cashier_name")
            ->join('users', 'users.user_id = remittance_details.cashier', 'left')
            ->orderBy('remittance_date', 'DESC')
            ->findAll();
    }

    public function getRemittanceDetails(int $remittanceId): ?array
    {
        return $this->select('remittance_details.*, CONCAT(users.firstname, \' \', COALESCE(users.middlename, \'\'), \' \', users.lastname) AS cashier_name, users.email AS cashier_email')
            ->join('users', 'users.user_id = remittance_details.cashier', 'left')
            ->where('remittance_details.remittance_id', $remittanceId)
            ->first();
    }
}
