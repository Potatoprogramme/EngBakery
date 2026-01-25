<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

// Models
use App\Models\MaterialCategoryModel;
use App\Models\RawMaterialsModel;
use App\Models\DailyStockModel;
use App\Models\ProductModel;
use App\Models\ProductRecipeModel;
use App\Models\ProductCostModel;
use App\Models\ProductCombinedRecipeModel;
use App\Models\DailyStockItemsModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\DailySalesModel;

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
    protected $dailyStockModel;
    protected $productModel;
    protected $productRecipeModel;
    protected $productCostModel;
    protected $productCombinedRecipeModel;
    protected $dailyStockItemsModel;
    protected $orderModel;
    protected $orderItemModel;
    protected $dailySalesModel;

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
        $this->dailyStockModel = new DailyStockModel();
        $this->productModel = new ProductModel();
        $this->productRecipeModel = new ProductRecipeModel();
        $this->productCostModel = new ProductCostModel();
        $this->productCombinedRecipeModel = new ProductCombinedRecipeModel();
        $this->dailyStockItemsModel = new DailyStockItemsModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->dailySalesModel = new DailySalesModel();

        // Initialize database connection once
        $this->db = \Config\Database::connect();


        // $this->session = service('session');
    }
}