<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

// Models
use App\Models\MaterialCategoryModel;
use App\Models\RawMaterialsModel;
use App\Models\RawMaterialStockModel;
use App\Models\DailyStockModel;
use App\Models\ProductModel;
use App\Models\ProductRecipeModel;
use App\Models\ProductCostModel;
use App\Models\ProductCombinedRecipeModel;
use App\Models\DailyStockItemsModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\TransactionsModel;
use App\Models\UsersModel;
use App\Models\RemittanceDetailsModel;
use App\Models\RemittanceItemsModel;
use App\Models\RemittanceDenominationsModel;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    // Preload Models Here
    protected $materialCategoryModel;
    protected $rawMaterialsModel;
    protected $rawMaterialStockModel;
    protected $dailyStockModel;
    protected $productModel;
    protected $productRecipeModel;
    protected $productCostModel;
    protected $productCombinedRecipeModel;
    protected $dailyStockItemsModel;
    protected $orderModel;
    protected $orderItemModel;
    protected $transactionsModel;
    protected $usersModel;
    protected $remittanceDetailsModel;
    protected $remittanceItemsModel;
    protected $remittanceDenominationsModel;

    // Database connection
    protected $db;


    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->materialCategoryModel = new MaterialCategoryModel();
        $this->rawMaterialsModel = new RawMaterialsModel();
        $this->rawMaterialStockModel = new RawMaterialStockModel();
        $this->dailyStockModel = new DailyStockModel();
        $this->productModel = new ProductModel();
        $this->productRecipeModel = new ProductRecipeModel();
        $this->productCostModel = new ProductCostModel();
        $this->productCombinedRecipeModel = new ProductCombinedRecipeModel();
        $this->dailyStockItemsModel = new DailyStockItemsModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->transactionsModel = new TransactionsModel();
        $this->usersModel = new UsersModel();
        $this->remittanceDetailsModel = new RemittanceDetailsModel();
        $this->remittanceItemsModel = new RemittanceItemsModel();
        $this->remittanceDenominationsModel = new RemittanceDenominationsModel();
        // Initialize database connection once
        $this->db = \Config\Database::connect();


        // $this->session = service('session');
    }

    /**
     * Get session data for current user
     * @return array
     */
    protected function getSessionData(): array
    {
        $session = session();
        return [
            'user_id' => $session->get('id'),
            'email' => $session->get('email'),
            'username' => $session->get('username'),
            'employee_type' => $session->get('employee_type'),
            'name' => $session->get('name'),
            'is_logged_in' => $session->get('is_logged_in'),
        ];
    }

    /**
     * Check if user is logged in
     * @return bool
     */
    protected function isLoggedIn(): bool
    {
        return session()->get('is_logged_in') === true;
    }

    /**
     * Check if user is staff
     * @return bool
     */
    protected function isStaff(): bool
    {
        return session()->get('employee_type') === 'staff';
    }

    /**
     * Check if user is admin
     * @return bool
     */
    protected function isAdmin(): bool
    {
        return session()->get('employee_type') === 'admin';
    }

    /**
     * Check if user is owner
     * @return bool
     */
    protected function isOwner(): bool
    {
        return session()->get('employee_type') === 'owner';
    }

    /**
     * Redirect if user is not logged in
     * @param string $message Optional error message
     * @return \CodeIgniter\HTTP\RedirectResponse|bool Returns redirect response if not logged in, false otherwise
     */
    protected function redirectIfNotLoggedIn(string $message = 'Please log in to access this page.')
    {
        if (!$this->isLoggedIn()) {
            session()->destroy(); // Clear session data to prevent unauthorized access
            return redirect()->to(base_url('login'))->with('error_message', $message);
        }
        return false;
    }

    /**
     * Redirect if user is not admin
     * @param string $message Optional error message
     * @return \CodeIgniter\HTTP\RedirectResponse|bool Returns redirect response if not admin, false otherwise
     */
    protected function redirectIfNotAdmin(string $message = 'Access denied. Admin privileges required.')
    {
        if (!$this->isAdmin()) {
            return redirect()->back()->with('error_message', $message);
        }
        return false;
    }
    
    /**
     * Redirect if user is not owner and admin
     * @param string $message Optional error message
     * @return \CodeIgniter\HTTP\RedirectResponse|bool Returns redirect response if not owner and admin, false otherwise
     */
    protected function redirectIfNotOwnerAndAdmin(string $message = 'Access denied. Admin privileges required.')
    {
        if (!$this->isAdmin() && !$this->isOwner()) {
            return redirect()->back()->with('error_message', $message);
        }
        return false;
    }
}