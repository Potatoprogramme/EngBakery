<?php

namespace App\Controllers;

class RawMaterialsController extends BaseController
{
    public function RawMaterial(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('RawMaterial') .
                view('Template/Footer');
    }
    public function addRawMaterial()
    {
        $data = $this->request->getPost();

    }
}
