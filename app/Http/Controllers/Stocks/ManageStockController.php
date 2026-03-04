<?php

namespace App\Http\Controllers\Stocks;

use App\Http\Controllers\Controller;
use App\DataTables\Stocks\ProductStockDataTable;

class ManageStockController extends Controller
{
    public function index(ProductStockDataTable $dataTable)
    {
        return $dataTable->render('stocks.product_stock.index');
    }
}
