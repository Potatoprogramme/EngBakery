<?php

namespace App\Controllers;

class ManageEmployeeController extends BaseController
{
    public function index()
    {
        $data = $this->getSessionData();

        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

        if ($redirect = $this->redirectIfNotOwnerAndAdmin()) {
            return $redirect;
        }

        return view('Template/Header', $data)
            . view('Template/SideNav', $data)
            . view('Template/Notification', $data)
            . view('ManageEmployee/Employee', $data)
            . view('Template/Footer', $data);
    }

    public function getEmployees()
    {
        $employees = $this->usersModel->getAllEmployees();

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'data' => $employees,
        ]);
    }

    /**
     * Summary of deleteUser
     * Delete a user by user_id
     * blame - JC
     */
    public function deleteUser()
    {
        $data = $this->request->getJSON(true);
        $user_id = $data['user_id'];

        $sessionData = $this->getSessionData();

        $privilege_level = $sessionData['employee_type'];

        if ($privilege_level !== 'owner') {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => false,
                'message' => 'You do not have permission to delete users.'
            ]);
        }

        if ($this->usersModel->checkUserExists($user_id)) {
            $this->usersModel->delete($user_id);

            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
    }

    /**
     * Summary of changeUserRole
     * Update user role/privilege
     * blame - JC
     */
    public function changeUserRole()
    {
        $data = $this->request->getJSON(true);
        $sessionData = $this->getSessionData();
        $user_id = $data['user_id'];
        $new_role = $data['new_role'];

        $privilege_level = $sessionData['employee_type'];

        if ($privilege_level !== 'owner' && $privilege_level !== 'admin') {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'You do not have permission to change employee roles.'
            ]);
        }

        $updateData = [
            'employee_type' => $new_role,
        ];

        $this->usersModel->update($user_id, $updateData);

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'message' => 'Employee role changed successfully.',
        ]);
    }
}
