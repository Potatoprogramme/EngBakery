<?php
namespace App\Models;

use CodeIgniter\Model;

class RemittanceDenominationsModel extends Model
{
    protected $table = 'remittance_denominations';
    protected $primaryKey = 'denomination_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'remittance_id',
        'denomination',
        'quantity',
        'cash_on_hand',
    ];
}