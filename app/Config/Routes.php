<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

// Authentication Routes
$routes->get('/login', 'AuthenticationController::loginPage'); // lowercased due to CI4 login route sensitivity
$routes->post('/Login/Manual', 'AuthenticationController::manualLogin');
$routes->get('/Logout', 'AuthenticationController::logout');
$routes->get('/Auth/Google', 'AuthenticationController::googleLogin');
$routes->get('/Auth/Google/Callback', 'AuthenticationController::googleCallback');

// Main Pages Routes
$routes->group('Dashboard', function (RouteCollection $routes) {
    $routes->get('/', 'DashboardController::dashboard');
});

$routes->group('RawMaterials', function (RouteCollection $routes) {
    // Main view with Add Material modal
    $routes->get('/', 'RawMaterialsController::rawMaterial');
    // API routes for AJAX
    $routes->get('GetCategories', 'RawMaterialsController::getCategories');
    $routes->get('GetAll', 'RawMaterialsController::getAll');
    $routes->post('AddRawMaterial', 'RawMaterialsController::addRawMaterial');
    $routes->post('delete/(:num)', 'RawMaterialsController::delete/$1');
});

$routes->group('MaterialCategory', function (RouteCollection $routes) {
    $routes->get('TestView', 'MaterialCategoryController::testView');
    $routes->post('Add', 'MaterialCategoryController::addCategory');// access via ajax request (accepts JSON data)
    $routes->post('Delete', 'MaterialCategoryController::deleteCategory');
});