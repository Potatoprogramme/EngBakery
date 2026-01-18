<?php

namespace App\Controllers;

class OrdersController extends BaseController
{
    public function order(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('Template/notification') .
                view('Orders/Order') .
                view('Template/Footer');
    }

    public function orderHistory(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('Template/notification') .
                view('Orders/OrderHistory') .
                view('Template/Footer');
    }
}