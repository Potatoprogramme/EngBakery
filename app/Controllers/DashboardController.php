<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function Dashboard(): string
    {
        return view('Template/Header') . view('Dashboard');
    }
    
}
