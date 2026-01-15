<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/login', 'LoginPageController::LoginPage');
$routes->get('/dashboard', 'DashboardController::Dashboard');
$routes->get('/raw_material', 'RawMaterialController::RawMaterial');