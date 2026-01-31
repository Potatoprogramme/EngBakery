<?php

namespace App\Controllers;

class ApprovalController extends BaseController
{
    public function index()
    {
        return view('Template/Header')
            . view('Template/SideNav')
            . view('Approval/Approval')
            . view('Template/Footer');
    }
}
