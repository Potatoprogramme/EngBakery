<?php

namespace App\Controllers;

class AuthenticationController extends BaseController
{
    public function loginPage(): string
    {
        return view('LoginPage');
    }
}
