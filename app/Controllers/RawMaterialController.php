<?php

namespace App\Controllers;

class RawMaterialController extends BaseController
{
    public function RawMaterial(): string
    {
        return view('Template/Header') . view('RawMaterial');
    }
    
}
