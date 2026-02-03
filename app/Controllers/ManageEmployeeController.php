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
}
