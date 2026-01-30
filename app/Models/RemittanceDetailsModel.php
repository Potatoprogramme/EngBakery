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
        'cash_on_hand',
        'amount_enclosed',
        'cash_out',
        'cashout_reason',
        'bakery_sales',
        'coffee_sales',
        'total_sales', 
        'overage_shortage',
    ];

    public function saveRemittance($data)
    {
        try {
            $this->insert($data);
            return [
                'success' => true,
                'message' => 'Remittance details saved successfully.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error saving remittance details: ' . $e->getMessage()
            ];
        }
    }

}