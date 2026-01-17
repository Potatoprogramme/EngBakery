<?php

namespace App\Controllers;

class InventoryController extends BaseController
{
    public function inventory(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('Inventory/Inventory') .
                view('Template/Footer');
    }

    public function addInventory(): string
    {
        return  view('Template/Header').
                view('Template/SideNav') . 
                view('Inventory/AddInventory') .
                view('Template/Footer');
    }
}
