<?php

namespace App\Controllers;

class ApprovalController extends BaseController
{
     private function getSessionData()
    {
        $session = session();
        return [
            'user_id' => $session->get('id'),
            'email' => $session->get('email'),
            'username' => $session->get('username'),
            'employee_type' => $session->get('employee_type'),
            'name' => $session->get('name'),
            'is_logged_in' => $session->get('is_logged_in'),
        ];
    }

    public function index()
    {
        $data = $this->getSessionData();
        
        return view('Template/Header', $data)
            . view('Template/SideNav', $data)
            . view('Template/Notification', $data)
            . view('Approval/Approval', $data)
            . view('Template/Footer', $data);
    }

    // to approve user
    public function approveUser()
    {
        $data = $this->request->getJSON(true);

        $user_id = $data['user_id'];
        // $privilege_level = $data['privilege_level'];

        if ($this->usersModel->checkUserExists($user_id)) {
            $updateData = [
                'approved' => 1,
                // 'employee_type' => $privilege_level
            ];

            $this->usersModel->update($user_id, $updateData);

            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'User approved successfully.'
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
    }

    public function getPendingUsers()
    {
        $pendingUsers = $this->usersModel->getPendingUsers();

        return $this->response->setStatusCode(200)->setJSON([
            'success' => true,
            'data' => $pendingUsers,
            'message' => 'Pending users fetched successfully.'
        ]);
    }

    public function viewEmpDetails()
    {
        $data = $this->request->getJSON(true);
        $user_id = $data['user_id'];

        if ($this->usersModel->checkUserExists($user_id)) {
            $user = $this->usersModel->getUserDetails($user_id);

            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'data' => $user,
                'message' => 'User details fetched successfully.'
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
    }

    public function rejectUser()
    {
        $data = $this->request->getJSON(true);
        $user_id = $data['user_id'];

        if ($this->usersModel->checkUserExists($user_id)) {
            $this->usersModel->removeUser($user_id);
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'User rejected successfully.'
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
    }
}
