<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('RawMaterials', function ($routes) {
    // $routes->get('/', 'Home::index');
    $routes->get('/Login', 'AuthenticationController::loginPage');
    $routes->get('/Dashboard', 'DashboardController::dashboard');
    $routes->get('/RawMaterial', 'RawMaterialsController::rawMaterial');
    $routes->post('AddRawMaterial', 'RawMaterialsController::addRawMaterial');
});
$routes->group('MaterialCategory', function ($routes) {
    $routes->get('TestView', 'MaterialCategoryController::testView');
    $routes->post('Add', 'MaterialCategoryController::addCategory');
});