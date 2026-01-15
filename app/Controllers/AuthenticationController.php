<?php

namespace App\Controllers;

class AuthenticationController extends BaseController
{
    public function loginPage()
    {
        return view('LoginPage');
    }
}
