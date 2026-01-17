<?php

namespace App\Controllers;

class ProductsController extends BaseController
{
    public function products(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('Template/notification') .
                view('Products/Product') .
                view('Template/Footer');
    }
    public function addProduct()
    {
        $data = $this->request->getPost();

    }
}
