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

    public function getAllEmployees()
    {
        return $this->select('user_id, email, firstname, middlename, lastname, employee_type, username, gender, birthdate, phone_number, approved, created_at')
            ->where('approved', 1)->findAll();
    }

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

    public function checkUserExists($user_id)
    {
        return $this->find($user_id) !== null;
    }

    public function checkApprovedUserByEmail($email)
    {
        return $this->where('email', $email)->where('approved', 1)->first() !== null;
    }

    public function findByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getPendingUsers()
    {
        return $this->where('approved', 0)->findAll();
    }
    public function getUserDetails($user_id)
    {
        return $this->select('user_id, email, firstname, middlename, lastname, employee_type, username, gender, birthdate, phone_number, approved, created_at')
            ->find($user_id);
    }
    public function removeUser($user_id)
    {
        return $this->delete($user_id);
    }

    public function getAuthorizedUsers()
    {
        return $this->select('email')->findAll();
    }
}
