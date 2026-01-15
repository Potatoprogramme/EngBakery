<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function dashboard(): string
    {
       return  view('Template/Header').
                view('Template/SideNav') . 
                view('Dashboard') .
                view('Template/Footer');
    }
    
}
