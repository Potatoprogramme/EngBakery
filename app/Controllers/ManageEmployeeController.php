<?php

namespace App\Controllers;

class ManageEmployeeController extends BaseController
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
            . view('ManageEmployee/Employee', $data)
            . view('Template/Footer', $data);
    }
}
