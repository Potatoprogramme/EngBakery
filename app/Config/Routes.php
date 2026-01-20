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
    $routes->get('/', 'RawMaterialsController::rawMaterial');
    $routes->get('GetCategories', 'RawMaterialsController::getCategories');
    $routes->get('GetAll', 'RawMaterialsController::getAll');
    $routes->get('GetMaterial/(:num)', 'RawMaterialsController::getMaterial/$1');
    $routes->post('AddRawMaterial', 'RawMaterialsController::addRawMaterial');
    $routes->post('UpdateRawMaterial', 'RawMaterialsController::updateRawMaterial');
    $routes->post('CheckMaterialName', 'RawMaterialsController::checkMaterialName');
    $routes->post('Delete/(:num)', 'RawMaterialsController::delete/$1');
});

$routes->group('Products', function (RouteCollection $routes) {
    $routes->get('/', 'ProductsController::products');
    $routes->get('GetAll', 'ProductsController::getAllProducts');
    $routes->get('GetProduct/(:num)', 'ProductsController::getProduct/$1');
    $routes->post('AddProduct', 'ProductsController::addProduct');
    $routes->post('UpdateProduct', 'ProductsController::updateProduct');
    $routes->post('DeleteProduct/(:num)', 'ProductsController::deleteProduct/$1');
});

$routes->group('Inventory', function (RouteCollection $routes) {
    $routes->get('/', 'InventoryController::inventory');
    $routes->get('CheckInventoryToday', 'InventoryController::checkInventoryToday');
    $routes->post('AddTodaysInventory', 'InventoryController::addTodaysInventory');
    $routes->get('FetchAllStockItems', 'InventoryController::fetchTodaysInventory');
    $routes->post('DeleteTodaysInventory', 'InventoryController::deleteTodaysInventory');
    $routes->post('UpdateStockItem/(:num)', 'InventoryController::updateStockItem/$1');
});

$routes->group('Order', function (RouteCollection $routes) {
    $routes->get('/', 'OrdersController::order');
    $routes->get('OrderHistory', 'OrdersController::orderHistory');
});

$routes->group('MaterialCategory', function (RouteCollection $routes) {
    $routes->get('TestView', 'MaterialCategoryController::testView');
    $routes->post('Add', 'MaterialCategoryController::addCategory');// access via ajax request (accepts JSON data)
    $routes->post('Delete', 'MaterialCategoryController::deleteCategory');
    $routes->post('Update', 'MaterialCategoryController::updateCategory');
    $routes->get('FetchAll', 'MaterialCategoryController::fetchAllCategories');
});

// Daily Stock Routes may be removed later if not needed
$routes->group('DailyStock', function (RouteCollection $routes) {
    $routes->get('TestView', 'DailyStockController::testView');
    $routes->get('CheckIfInventoryExists', 'DailyStockController::checkIfInventoryExists');
    $routes->post('CreateParticular', 'DailyStockController::createParticular');
});

