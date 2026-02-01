<?php

namespace App\Models;

use CodeIgniter\Model;

class RemittanceItemsModel extends Model
{
    protected $table = 'remittance_items';
    protected $primaryKey = 'remit_item_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'remittance_id',
        'transaction_id',
        'created_at'
    ];
}
