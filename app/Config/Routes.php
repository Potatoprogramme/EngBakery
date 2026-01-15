<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

// Authentication Routes
$routes->get('/Login', 'AuthenticationController::loginPage');
$routes->get('/Logout', 'AuthenticationController::logout');
$routes->get('/Auth/Google', 'AuthenticationController::googleLogin');
$routes->get('/Auth/Google/Callback', 'AuthenticationController::googleCallback');

// Main Pages Routes
$routes->group('Dashboard', function (RouteCollection $routes) {
    $routes->get('/', 'DashboardController::dashboard');
});

$routes->group('RawMaterials', function (RouteCollection $routes) {
    $routes->get('/', 'RawMaterialsController::rawMaterial');
    $routes->post('AddRawMaterial', 'RawMaterialsController::addRawMaterial');
});

$routes->group('MaterialCategory', function (RouteCollection $routes) {
    $routes->get('/', 'MaterialCategoryController::materialCategory');
    $routes->post('Add', 'MaterialCategoryController::addCategory');
});