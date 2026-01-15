<?php

namespace App\Controllers;

class RawMaterialsController extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
    public function addRawMaterial()
    {
        $data = $this->request->getPost();

    }
}
