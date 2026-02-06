<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

// Authentication Routes


$routes->get('/', 'AuthenticationController::loginPage'); // lowercased due to CI4 login route sensitivity
$routes->get('/registration', 'AuthenticationController::registrationPage');
$routes->post('/registration/submit', 'AuthenticationController::registerUser');
$routes->get('/login', 'AuthenticationController::loginPage'); // lowercased due to CI4 login route sensitivity
$routes->post('/Login/Manual', 'AuthenticationController::manualLogin');
$routes->get('/Logout', 'AuthenticationController::logout');
$routes->get('/Auth/Google', 'AuthenticationController::googleLogin');
$routes->get('/Auth/Google/Callback', 'AuthenticationController::googleCallback');

// Password Reset Routes
$routes->group('PasswordReset', function (RouteCollection $routes) {
    $routes->get('/', 'PasswordResetController::index');
    $routes->post('RequestOTP', 'PasswordResetController::requestOTP');
    $routes->post('VerifyOTP', 'PasswordResetController::verifyOTP');
    $routes->post('ResetPassword', 'PasswordResetController::resetPassword');
});

$routes->group('User', function (RouteCollection $routes) {
    $routes->get('Profile', 'UserController::index');
    $routes->get('GetUserData', 'UserController::getCurrentUserData');
    $routes->post('UpdateProfile', 'UserController::updateProfile');
    $routes->post('ChangePassword', 'UserController::changePassword');
    $routes->get('GetCurrentUser', 'AuthenticationController::getCurrentUser');
});

// Main Pages Routes
$routes->group('Dashboard', function (RouteCollection $routes) {
    $routes->get('/', 'DashboardController::dashboard');
});

$routes->group('RawMaterials', function (RouteCollection $routes) {
    $routes->get('/', 'RawMaterialsController::rawMaterial');
    $routes->get('GetCategories', 'RawMaterialsController::getCategories');
    $routes->get('GetAll', 'RawMaterialsController::getAll');
    $routes->get('GetMaterial/(:num)', 'RawMaterialsController::getMaterial/$1');
    $routes->get('GetLowStock', 'RawMaterialsController::getLowStock');
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
    $routes->post('ToggleProductStatus', 'ProductsController::toggleProductStatus');
    
});

$routes->group('Inventory', function (RouteCollection $routes) {
    $routes->get('/', 'InventoryController::inventory');
    $routes->get('History', 'InventoryController::inventoryHistory');
    $routes->get('CheckInventoryToday', 'InventoryController::checkInventoryToday');
    $routes->post('AddTodaysInventory', 'InventoryController::addTodaysInventory');
    $routes->get('FetchAllStockItems', 'InventoryController::fetchTodaysInventory');
    $routes->get('FetchHistory', 'InventoryController::fetchInventoryHistory');
    $routes->get('FetchByDate', 'InventoryController::fetchInventoryByDate');
    $routes->post('DeleteTodaysInventory', 'InventoryController::deleteTodaysInventory');
    $routes->post('UpdateStockItem/(:num)', 'InventoryController::updateStockItem/$1');
    $routes->post('Delete/(:num)', 'InventoryController::deleteStockItem/$1');
    $routes->get('GetAvailableProducts', 'InventoryController::getAvailableProducts');
    $routes->post('AddProductToInventory', 'InventoryController::addProductToInventory');
});

$routes->group('Order', function (RouteCollection $routes) {
    $routes->get('/', 'OrdersController::order');
    $routes->get('OrderHistory', 'OrdersController::orderHistory');
    $routes->get('GetProducts', 'OrdersController::getProducts');
    $routes->post('ProcessPayment', 'OrdersController::processPayment');
    $routes->get('GetOrderHistory', 'OrdersController::getOrderHistory');
    $routes->get('GetOrderDetails/(:num)', 'OrdersController::getOrderDetails/$1');
    $routes->get('GetTodaysSales', 'OrdersController::getTodaysSales');
    $routes->get('GetTodaysStockSummary', 'OrdersController::getTodaysStockSummary');
    $routes->post('VoidOrder/(:num)', 'OrdersController::voidOrder/$1');
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

$routes->group('Sales', function (RouteCollection $routes) {
    // Routes for Today's Sales
    $routes->get('/', 'SalesController::index');
    $routes->get('GetTodaysSales', 'SalesController::getTodaysSales');
    $routes->get('GetTodaysSummary', 'SalesController::getTodaysSummary');
    $routes->get('checkExistingRemittance', 'SalesController::checkExistingRemittance');
    $routes->get('getRemittancesForDate', 'SalesController::getRemittancesForDate');
    $routes->post('SaveRemittance', 'SalesController::saveRemittance');
    // Routes for Sales History
    $routes->get('History', 'SalesController::history');
    $routes->get('GetSalesHistory', 'SalesController::getSalesHistory');
    $routes->get('GetSummaryDetails', 'SalesController::getSummaryDetails'); // fetch information for the summary cards
    $routes->post('GetTransactionDetails', 'SalesController::getTransactionDetails'); // fetch detailed sales data for a transaction id
    // Routes for Remittance History
    $routes->get('RemittanceHistory', 'SalesController::remittanceHistory');
    $routes->get('GetRemittanceHistory', 'SalesController::getRemittanceHistory');
    $routes->get('GetRemittanceDetails/(:num)', 'SalesController::getRemittanceDetails/$1');
    $routes->post('DeleteRemittance/(:num)', 'SalesController::deleteRemittance/$1'); // Admin/Owner only
});

$routes->group('Approval', function (RouteCollection $routes) {
    $routes->get('/', 'ApprovalController::index');
    $routes->post('ApproveUser', 'ApprovalController::approveUser');
    $routes->get('GetPendingUsers', 'ApprovalController::getPendingUsers');
    $routes->post('ViewEmpDetails', 'ApprovalController::viewEmpDetails');
    $routes->post('RejectUser', 'ApprovalController::rejectUser');
});

$routes->group('ManageEmployee', function (RouteCollection $routes) {
    $routes->get('/', 'ManageEmployeeController::index');
    $routes->get('GetEmployees', 'ManageEmployeeController::getEmployees');
    $routes->get('Approval', 'ApprovalController::index');
    $routes->post('DeleteUser', 'ManageEmployeeController::deleteUser');
    $routes->post('ChangeUserRole', 'ManageEmployeeController::changeUserRole');
});

$routes->group('DeliveryLog', function (RouteCollection $routes) {
    $routes->get('/', 'DeliveryLogController::index');
});


$routes->group('Utility', function (RouteCollection $routes) {
    $routes->get('/', 'UtilityController::index');
});
