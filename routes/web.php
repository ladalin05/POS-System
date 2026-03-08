<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Other\WarehousesController;
use App\Http\Controllers\Other\BranchController;
use App\Http\Controllers\Other\CashAccountController;
use App\Http\Controllers\Other\CurrenciesController;
use App\Http\Controllers\Pos\PosController;
use App\Http\Controllers\Setting\UnitsController;
use App\Http\Controllers\Setting\BaseUnitController;
use App\Http\Controllers\Setting\FloorController;
use App\Http\Controllers\Setting\RoomController;
use App\Http\Controllers\Setting\UnitConvertController;
use App\Http\Controllers\Stocks\AdjustmentController;
use App\Http\Controllers\Stocks\ManageStockController;
use App\Http\Controllers\Stocks\StockTransferController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Sales\InvoicesController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\People\CustomerController;
use App\Http\Controllers\People\SuppliersController;
use App\Http\Controllers\Purchases\PurchasesController;
use App\Http\Controllers\Sales\SaleReturnController;
use App\Http\Controllers\Api\V1\PaymentController;


Route::get('/403', function () {
    return view('errors.403');
});
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'abilities'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::group([
        'prefix' => 'users-management',
        'as' => 'users-management.',
    ], function () {

        Route::group([
            'prefix' => 'users',
            'as' => 'users.',
        ], function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('add', [UserController::class, 'add'])->name('add');
            Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::post('save/{id?}', [UserController::class, 'save'])->name('save');
            Route::post('delete/{id}', [UserController::class, 'delete'])->name('delete');
            Route::match(['get', 'post'], 'permission/{id}', [UserController::class, 'permission'])->name('permission');
            Route::match(['get', 'post'], 'change-password/{id}', [UserController::class, 'changePassword'])->name('change-password');
            Route::get('account', [UserController::class, 'account'])->name('account');
            Route::get('account/change-password', [AccountController::class, 'changePassword'])->name('account.change-password');
        });

        Route::group([
            'prefix' => 'roles',
            'as' => 'roles.',
        ], function () {

            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('add', [RoleController::class, 'add'])->name('add');
            Route::get('edit/{id}', [RoleController::class, 'edit'])->name('edit');
            Route::post('save/{id?}', [RoleController::class, 'save'])->name('save');
            Route::post('delete/{id}', [RoleController::class, 'delete'])->name('delete');
        });
    });

    Route::group([
        'prefix' => 'products',
        'as' => 'products.',
    ], function () {

        Route::group([
            'prefix' => 'products',
            'as' => 'products.',
        ], function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [ProductController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit/{id}', [ProductController::class, 'update'])->name('edit');
            Route::post('delete/{id}', [ProductController::class, 'delete'])->name('delete');
            Route::get('view/{id}', [ProductController::class, 'view'])->name('view');
            Route::get('alert_qty/index', [ProductController::class, 'alert_quantity'])->name('alert_quantity');
        });

        Route::group([
            'prefix' => 'categories',
            'as' => 'categories.',
        ], function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [CategoryController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/update', [CategoryController::class, 'update'])->name('edit');
            Route::post('delete', [CategoryController::class, 'delete'])->name('delete');
        });
    });

    Route::group([
        'prefix' => 'setting',
        'as' => 'setting.',
    ], function () {
        Route::group([
            'prefix' => 'units', 
            'as' => 'units.'
        ], function () {
            Route::get('/', [UnitsController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [UnitsController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/update', [UnitsController::class, 'update'])->name('edit');
            Route::post('delete', [UnitsController::class, 'delete'])->name('delete');
        });

        Route::group([
            'prefix' => 'base-units', 
            'as' => 'base-units.'
        ], function () {
            Route::get('/', [BaseUnitController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [BaseUnitController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/update', [BaseUnitController::class, 'update'])->name('edit');
            Route::post('delete', [BaseUnitController::class, 'delete'])->name('delete');
        });

        Route::group([
            'prefix' => 'floor', 
            'as' => 'floor.'
        ], function () {
            Route::get('/', [FloorController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [FloorController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/update', [FloorController::class, 'update'])->name('edit');
            Route::post('delete', [FloorController::class, 'delete'])->name('delete');
        });

        Route::group([
            'prefix' => 'room', 
            'as' => 'room.'
        ], function () {
            Route::get('/', [RoomController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [RoomController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/update', [RoomController::class, 'update'])->name('edit');
            Route::post('delete', [RoomController::class, 'delete'])->name('delete');
        });
        
        Route::group([
            'prefix' => 'unit_convert', 
            'as' => 'unit_convert.'
        ], function () {
            Route::get('/', [UnitConvertController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [UnitConvertController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/update', [UnitConvertController::class, 'update'])->name('edit');
            Route::post('delete', [UnitConvertController::class, 'delete'])->name('delete');
        });

    });

    Route::group([
        'prefix' => 'stocks',
        'as' => 'stocks.',
    ], function () {
        Route::group([
            'prefix' => 'manage',
            'as' => 'manage.',
        ], function () {
            Route::get('/', [ManageStockController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [ManageStockController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/update', [ManageStockController::class, 'update'])->name('edit');
            Route::post('delete', [ManageStockController::class, 'delete'])->name('delete');
        });
        Route::group([
            'prefix' => 'adjustment',
            'as' => 'adjustment.',
        ], function () {
            Route::get('/', [AdjustmentController::class, 'index'])->name('index');
            Route::get('add', [AdjustmentController::class, 'add'])->name('add');
            Route::get('edit/{id}', [AdjustmentController::class, 'edit'])->name('edit');
            Route::post('save/{id?}', [AdjustmentController::class, 'save'])->name('save');
            Route::post('delete/{id}', [AdjustmentController::class, 'delete'])->name('delete');
            Route::post('{id}/approve', [AdjustmentController::class, 'approve'])->name('approve');
            Route::get('/modal_view/{id}', [AdjustmentController::class, 'modalView'])->name('modal_view');
            Route::get('add', [AdjustmentController::class, 'add'])->name('add');
            Route::get('ajaxQoh', [AdjustmentController::class, 'ajaxQoh'])->name('ajaxQoh');
            Route::get('ajaxProducts', [AdjustmentController::class, 'ajaxProducts'])->name('ajaxProducts');
            Route::get('ajaxUnits', [AdjustmentController::class, 'ajaxUnits'])->name('ajaxUnits');
            Route::get('ajaxProductUnits', [AdjustmentController::class, 'ajaxProductUnits'])->name('ajaxProductUnits');

        });
        Route::group([
            'prefix' => 'transfer',
            'as' => 'transfer.',
        ], function () {
            Route::get('/', [StockTransferController::class, 'index'])->name('index');
            Route::get('add', [StockTransferController::class, 'add'])->name('add');
            Route::get('edit/{id}', [StockTransferController::class, 'edit'])->name('edit');
            Route::post('delete/{id}', [StockTransferController::class, 'delete'])->name('delete');

        });
    });

    Route::group([
        'prefix' => 'sales',
        'as' => 'sales.',
    ], function () {
        Route::group([
            'prefix' => 'sales',
            'as' => 'sales.',
        ], function () {
            Route::get('/', [SalesController::class, 'index'])->name('index');
            Route::get('show/{id}', [SalesController::class, 'show'])->name('show');
            Route::post('delete/{id}', [SalesController::class, 'delete'])->name('delete');
            Route::get('stockcount/index', [SalesController::class, 'StockCount'])->name('StockCount');
        });
        
        Route::group([
            'prefix' => 'pos',
            'as' => 'pos.',
        ], function () {
            Route::get('/', [PosController::class, 'index'])->name('index');
            Route::get('/pos/init-data', [PosController::class,'initData'])->name('initData');
            Route::get('/pos/products', [PosController::class,'products'])->name('products');
            Route::post('/pos/store-order', [PosController::class,'storeOrder'])->name('storeOrder');
            Route::get('search-customer', [PosController::class, 'searchCustomer'])->name('searchCustomer');
            Route::get('getProductDataByCode', [PosController::class, 'getProductDataByCode'])->name('getProductDataByCode');
            Route::get('search-name', [PosController::class, 'searchProductByName'])->name('searchProductByName');
            Route::get('ajaxCategoryData', [PosController::class, 'ajaxCategoryData'])->name('ajaxCategoryData');
            Route::get('table-form', [PosController::class, 'addTable'])->name('addTable');
            Route::get('getWarehouses', [PosController::class, 'getWarehouses'])->name('getWarehouses');
            Route::get('today_sale', [PosController::class, 'todaySale'])->name('today_sale');
            Route::get('select-table/{id}', [PosController::class, 'selectTable'])->name('selectTable');
            Route::get('clear-table', [PosController::class, 'clearTable'])->name('clearTable');
            Route::post('save-suspend', [PosController::class, 'saveSuspend'])->name('saveSuspend');
            Route::get('move-room', [PosController::class, 'moveRoom'])->name('moveRoom');
            Route::get('opened_bills', [PosController::class, 'openedBills'])->name('opened_bills');
            Route::get('openedBillsItems/{id}', [PosController::class, 'openedBillsItems'])->name('opened_bills_items');
            Route::post('submit-sale', [PosController::class, 'submitSale'])->name('submitSale');
            Route::get('modal_bill/{id}', [PosController::class, 'modal_bill'])->name('modal_bill');
        });
        
        Route::group([
            'prefix' => 'invoices',
            'as' => 'invoices.',
        ], function () {
            Route::get('/', [InvoicesController::class, 'index'])->name('index');
            Route::get('show/{id}', [InvoicesController::class, 'show'])->name('show');
            Route::post('/delete/{id}', [InvoicesController::class, 'destroy'])->name('delete');
            Route::get('search-customer', [InvoicesController::class, 'searchCustomer'])->name('searchCustomer');
            Route::get('getProductDataByCode', [InvoicesController::class, 'getProductDataByCode'])->name('getProductDataByCode');
            Route::post('submit-sale', [InvoicesController::class, 'submitSale'])->name('submitSale');
        });
        
        Route::group([
            'prefix' => 'return',
            'as' => 'return.',
        ], function () {
            Route::get('/', [SaleReturnController::class, 'index'])->name('index');
            Route::get('/show/{id}', [SaleReturnController::class, 'show'])->name('show');
            Route::post('/delete/{id}', [SaleReturnController::class, 'destroy'])->name('delete');
        });
        
        Route::group([
            'prefix' => 'quotation',
            'as' => 'quotation.',
        ], function () {

            Route::get('/', [PosController::class, 'index'])->name('index');
            Route::get('search-customer', [PosController::class, 'searchCustomer'])->name('searchCustomer');
            Route::get('getProductDataByCode', [PosController::class, 'getProductDataByCode'])->name('getProductDataByCode');
            Route::post('submit-sale', [PosController::class, 'submitSale'])->name('submitSale');
        });
    });

    Route::group([
        'prefix' => 'reports',
        'as' => 'reports.',
    ], function () {
        Route::get('product-sales-report', [ReportController::class, 'productSales'])->name('product-sales-report');
        Route::get('product-sales', [ReportController::class, 'exportProductSales'])->name('product_sales');
        Route::get('product-sales/export', [ReportController::class, 'exportProductSales'])->name('product_sales.export');
    });

    Route::group([
        'prefix' => 'other',
        'as' => 'other.',
    ], function () {

        Route::group([
            'prefix' => 'cash_accounts',
            'as' => 'cash_accounts.',
        ], function () {
            Route::get('/', [CashAccountController::class, 'index'])->name('index');
            Route::get('add', [CashAccountController::class, 'add'])->name('add');
            Route::get('edit/{id}', [CashAccountController::class, 'edit'])->name('edit');
            Route::post('save/{id?}', [CashAccountController::class, 'save'])->name('save');
            Route::post('delete/{id}', [CashAccountController::class, 'delete'])->name('delete');
        });
        
        Route::group([
            'prefix' => 'branch',
            'as' => 'branch.',
        ], function () {
            Route::get('/', [BranchController::class, 'index'])->name('index');
            Route::get('add', [BranchController::class, 'add'])->name('add');
            Route::get('edit/{id}', [BranchController::class, 'edit'])->name('edit');
            Route::match(['get', 'post'], 'save/{id?}', [BranchController::class, 'save'])->name('save');
            Route::post('delete/{id}', [BranchController::class, 'delete'])->name('delete');
            Route::post('bulk-delete', [BranchController::class, 'bulkDelete'])->name('bulk-delete');
        });

        Route::group([
            'prefix' => 'warehouses',
            'as' => 'warehouses.',
        ], function () {
            Route::get('/', [WarehousesController::class, 'index'])->name('index');
            Route::get('add', [WarehousesController::class, 'add'])->name('add');
            Route::get('edit/{id}', [WarehousesController::class, 'edit'])->name('edit');
            Route::match(['get', 'post'], 'save/{id?}', [WarehousesController::class, 'save'])->name('save');
            Route::post('delete/{id}', [WarehousesController::class, 'delete'])->name('delete');
            Route::post('bulk-delete', [WarehousesController::class, 'bulkDelete'])->name('bulk-delete');
        });

        Route::group([
            'prefix' => 'currencies',
            'as' => 'currencies.',
        ], function () {
            Route::get('/', [CurrenciesController::class, 'index'])->name('index');
            Route::get('add', [CurrenciesController::class, 'add'])->name('add');
            Route::get('edit/{id}', [CurrenciesController::class, 'edit'])->name('edit');
            Route::match(['get', 'post'], 'save/{id?}', [CurrenciesController::class, 'save'])->name('save');
            Route::post('delete/{id}', [CurrenciesController::class, 'delete'])->name('delete');
            Route::post('bulk-delete', [CurrenciesController::class, 'bulkDelete'])->name('bulk-delete');
        });
    });
    
    Route::group([
        'prefix' => 'people',
        'as' => 'people.',
    ], function () {

        Route::group([
            'prefix' => 'customers',
            'as' => 'customers.',
        ], function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::get('add', [CustomerController::class, 'add'])->name('add');
            Route::get('edit/{id}', [CustomerController::class, 'edit'])->name('edit');
            Route::post('save/{id?}', [CustomerController::class, 'save'])->name('save');
            Route::post('delete/{id}', [CustomerController::class, 'delete'])->name('delete');
        });
        
        Route::group([
            'prefix' => 'suppliers',
            'as' => 'suppliers.',
        ], function () {
            Route::get('/', [SuppliersController::class, 'index'])->name('index');
            Route::get('add', [SuppliersController::class, 'add'])->name('add');
            Route::get('edit/{id}', [SuppliersController::class, 'edit'])->name('edit');
            Route::match(['get', 'post'], 'save/{id?}', [BranchController::class, 'save'])->name('save');
            Route::post('delete/{id}', [BranchController::class, 'delete'])->name('delete');
            Route::post('bulk-delete', [BranchController::class, 'bulkDelete'])->name('bulk-delete');
        });

    });

    Route::group([
        'prefix' => 'purchases',
        'as' => 'purchases.',
    ], function () {

        Route::get('/', [PurchasesController::class, 'index'])->name('index');
        Route::get('add', [PurchasesController::class, 'add'])->name('add');
        Route::get('edit/{id}', [PurchasesController::class, 'edit'])->name('edit');
        Route::post('save/{id?}', [PurchasesController::class, 'save'])->name('save');
        Route::post('delete/{id}', [PurchasesController::class, 'delete'])->name('delete');
        Route::post('{id}/approve', [PurchasesController::class, 'approve'])->name('approve');
        Route::get('modal_view/{id}', [PurchasesController::class, 'modal'])->name('modal_view');
        Route::get('ajaxProducts', [PurchasesController::class, 'ajaxProducts'])->name('ajaxProducts');
        Route::get('ajax-product-units', [PurchasesController::class, 'ajaxProductUnits'])->name('ajaxProductUnits');

    });


    



});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/filtter.php';
