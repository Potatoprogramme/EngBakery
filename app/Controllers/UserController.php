<?php

namespace App\Controllers;

use App\Libraries\OwnerNotificationPreferences;

class UserController extends BaseController
{
    public function index()
    {
        $data = $this->getSessionData();

        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

        return view('Template/Header', $data)
            . view('Template/SideNav', $data)
            . view('Template/Notification', $data)
            . view('AccountSettings/Account', $data)
            . view('Template/Footer', $data);
    }

    public function getCurrentUserData()
    {
        $user = $this->getSessionData();

        if (!$user['user_id']) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $currentUserData = $this->usersModel->find($user['user_id']);

        if (!$currentUserData) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        // Remove sensitive password field
        unset($currentUserData['password']);

        if (($currentUserData['employee_type'] ?? '') === 'owner') {
            $currentUserData['notification_settings'] = OwnerNotificationPreferences::getForUser((int) $user['user_id']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $currentUserData
        ]);
    }

    public function getNotificationSettings()
    {
        $user = $this->getSessionData();

        if (!$user['user_id']) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        if (!$this->isOwner()) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Only owner accounts can manage email notification settings.'
            ]);
        }

        $settings = OwnerNotificationPreferences::getForUser((int) $user['user_id']);

        return $this->response->setJSON([
            'success' => true,
            'data' => $settings
        ]);
    }

    public function updateNotificationSettings()
    {
        $user = $this->getSessionData();

        if (!$user['user_id']) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        if (!$this->isOwner()) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Only owner accounts can manage email notification settings.'
            ]);
        }

        $data = $this->request->getPost();

        if (!$this->validateData($data, [
            'low_stock_enabled' => 'required|in_list[0,1]',
            'inventory_enabled' => 'required|in_list[0,1]',
            'remittance_enabled' => 'required|in_list[0,1]',
            'material_stock_logs_enabled' => 'required|in_list[0,1]',
            'beginning_quantity_adjustments_enabled' => 'required|in_list[0,1]',
        ])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => $this->validator->listErrors(),
            ]);
        }

        $validatedData = $this->validator->getValidated();
        $saved = OwnerNotificationPreferences::upsertForUser((int) $user['user_id'], $validatedData);

        if (!$saved) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to update notification settings. Please try again.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Notification settings updated successfully.',
            'data' => OwnerNotificationPreferences::getForUser((int) $user['user_id'])
        ]);
    }

    public function updateProfile()
    {
        $user = $this->getSessionData();

        if (!$user['user_id']) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get POST data
        $data = $this->request->getPost();

        // Validate input data
        if (!$this->validateData($data, [
            'firstname' => 'required|alpha_space|min_length[2]|max_length[50]',
            'middlename' => 'permit_empty|alpha_space|max_length[50]',
            'lastname' => 'required|alpha_space|min_length[2]|max_length[50]',
            'birthdate' => 'required|valid_date',
            'gender' => 'required|in_list[male,female]',
            'phone_number' => 'required|numeric|min_length[10]|max_length[15]',
            'username' => 'required|alpha_numeric_punct|min_length[3]|max_length[50]',
            'email' => 'required|valid_email|max_length[100]',
        ])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => $this->validator->listErrors()
            ]);
        }

        $validatedData = $this->validator->getValidated();

        // Check if username is already taken by another user
        $existingUsername = $this->usersModel->where('username', $validatedData['username'])
            ->where('user_id !=', $user['user_id'])
            ->first();

        if ($existingUsername) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Username is already taken'
            ]);
        }

        // Check if email is already taken by another user
        $existingEmail = $this->usersModel->where('email', $validatedData['email'])
            ->where('user_id !=', $user['user_id'])
            ->first();

        if ($existingEmail) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Email is already taken'
            ]);
        }

        // Prepare update data (excluding fields that shouldn't be updated)
        $updateData = [
            'firstname' => $validatedData['firstname'],
            'middlename' => $validatedData['middlename'] ?? '',
            'lastname' => $validatedData['lastname'],
            'birthdate' => $validatedData['birthdate'],
            'gender' => $validatedData['gender'],
            'phone_number' => $validatedData['phone_number'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
        ];

        // Update user profile
        if (!$this->usersModel->update($user['user_id'], $updateData)) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to update profile. Please try again.'
            ]);
        }

        // Update session data if username or email changed
        $session = session();
        $session->set('username', $validatedData['username']);
        $session->set('email', $validatedData['email']);
        $session->set('name', trim($validatedData['firstname'] . ' ' . ($validatedData['middlename'] ?? '') . ' ' . $validatedData['lastname']));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    public function changePassword()
    {
        $user = $this->getSessionData();

        if (!$user['user_id']) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get POST data
        $data = $this->request->getPost();

        // Validate input data
        if (!$this->validateData($data, [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]|max_length[255]',
            'confirm_password' => 'required|matches[new_password]',
        ])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => $this->validator->listErrors()
            ]);
        }

        $validatedData = $this->validator->getValidated();

        // Get current user data
        $currentUser = $this->usersModel->find($user['user_id']);

        if (!$currentUser) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        // Verify current password
        if (!password_verify($validatedData['current_password'], $currentUser['password'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Current password is incorrect'
            ]);
        }

        // Update password
        $updateData = [
            'password' => password_hash($validatedData['new_password'], PASSWORD_BCRYPT)
        ];

        if (!$this->usersModel->update($user['user_id'], $updateData)) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to update password. Please try again.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }
}
