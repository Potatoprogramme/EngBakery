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
        'employee_type',
        'username',
        'password',
        'gender',
        'birthdate',
        'phone_number',
        'approved',
        'created_at',
    ];

    public function createUser($data)
    {
        try {
            // Password should already be hashed in controller
            $result = $this->insert($data);

            if ($result === false) {
                log_message('error', 'Failed to insert user: ' . json_encode($this->errors()));
                return false;
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception creating user: ' . $e->getMessage());
            return false;
        }
    }
}
