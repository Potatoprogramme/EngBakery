<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/login', 'LoginPageController::LoginPage');
$routes->get('/dashboard', 'DashboardController::Dashboard');
$routes->get('/raw_material', 'RawMaterialController::RawMaterial');
$routes->group('RawMaterials', function (RouteCollection $routes) {
    $routes->post('AddRawMaterial', 'RawMaterialsController::addRawMaterial');
});

$routes->group('MaterialCategory', function (RouteCollection $routes) {
    $routes->post('Add', 'MaterialCategoryController::addCategory');
});