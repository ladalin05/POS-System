<?php

namespace App\Http\Controllers\Stocks;

use App\Http\Controllers\Controller;
use App\DataTables\Stocks\StockTransferDataTable;

class StockTransferController extends Controller
{
    public function index(StockTransferDataTable $dataTable)
    {
        return $dataTable->render('stocks.stock_transfer.index');
    }
}
