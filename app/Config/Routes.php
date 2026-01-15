<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'AuthenticationController::loginPage');
$routes->get('/Dashboard', 'DashboardController::dashboard');

$routes->group('RawMaterials', function ($routes) {
    $routes->post('AddRawMaterial', 'RawMaterialsController::addRawMaterial');
});

$routes->group('MaterialCategory', function ($routes) {
    $routes->get('TestView', 'MaterialCategoryController::testView');
    $routes->post('Add', 'MaterialCategoryController::addCategory');
});