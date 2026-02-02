<?php

namespace App\Controllers;

class ManageEmployeeController extends BaseController
{
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
