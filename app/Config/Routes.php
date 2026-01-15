<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('RawMaterials', function (RouteCollection $routes) {
    $routes->post('AddRawMaterial', 'RawMaterialsController::addRawMaterial');
});

$routes->group('MaterialCategory', function (RouteCollection $routes) {
    $routes->post('Add', 'MaterialCategoryController::addCategory');
});