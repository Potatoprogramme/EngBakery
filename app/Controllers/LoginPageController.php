<?php

namespace App\Controllers;

class LoginPageController extends BaseController
{
    public function LoginPage(): string
    {
        return view('loginPage');
    }
}
