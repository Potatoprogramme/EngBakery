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

    public function getArchivedUsers()
    {
        $sessionData = $this->getSessionData();
        $privilege_level = $sessionData['employee_type'];

        if ($privilege_level !== 'owner') {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'You do not have permission to view archived users.'
            ]);
        }

        $archivedUsers = $this->usersModel
            ->onlyDeleted()
            ->select('user_id, email, firstname, middlename, lastname, employee_type, username, approved, created_at, deleted_at')
            ->orderBy('deleted_at', 'DESC')
            ->findAll();

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'data' => $archivedUsers,
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
        $user_id = isset($data['user_id']) ? (int) $data['user_id'] : 0;

        $sessionData = $this->getSessionData();

        $privilege_level = $sessionData['employee_type'];

        if ($privilege_level !== 'owner') {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => false,
                'message' => 'You do not have permission to delete users.'
            ]);
        }

        if ($user_id <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid user ID.'
            ]);
        }

        if ($this->usersModel->checkUserExists($user_id)) {
            $this->usersModel->removeUser($user_id);

            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'User archived successfully.'
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
    }

    public function restoreUser()
    {
        $data = $this->request->getJSON(true);
        $user_id = isset($data['user_id']) ? (int) $data['user_id'] : 0;

        $sessionData = $this->getSessionData();
        $privilege_level = $sessionData['employee_type'];

        if ($privilege_level !== 'owner') {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'You do not have permission to restore users.'
            ]);
        }

        if ($user_id <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid user ID.'
            ]);
        }

        $archivedUser = $this->usersModel->onlyDeleted()->find($user_id);

        if (!$archivedUser) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Archived user not found.'
            ]);
        }

        $restored = $this->usersModel->update($user_id, ['deleted_at' => null]);

        if (!$restored) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to restore user.'
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'message' => 'User restored successfully.'
        ]);
    }

    public function hardDeleteUser()
    {
        $data = $this->request->getJSON(true);
        $user_id = isset($data['user_id']) ? (int) $data['user_id'] : 0;

        $sessionData = $this->getSessionData();
        $privilege_level = $sessionData['employee_type'];

        if ($privilege_level !== 'owner') {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'You do not have permission to permanently delete users.'
            ]);
        }

        if ($user_id <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid user ID.'
            ]);
        }

        $archivedUser = $this->usersModel->onlyDeleted()->find($user_id);

        if (!$archivedUser) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Archived user not found.'
            ]);
        }

        $deleted = $this->usersModel->delete($user_id, true);

        if (!$deleted) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to permanently delete user.'
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'message' => 'User permanently deleted.'
        ]);
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
