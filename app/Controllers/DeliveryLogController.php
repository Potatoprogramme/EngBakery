<?php

namespace App\Controllers;

class DeliveryLogController extends BaseController
{
    public function index(): string
    {
        $data = $this->getSessionData();

        if ($redirect = $this->redirectIfNotLoggedIn()) {
            return $redirect;
        }

        return view('Template/Header', $data) .
            view('Template/SideNav', $data) .
            view('Template/MaintenancePage', $data) .
            view('Template/Footer');
    }
}
