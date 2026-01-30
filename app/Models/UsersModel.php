<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'email',
        'firstname',
        'middlename',
        'lastname',
        'extension',
        'employee_type'
    ];
}
