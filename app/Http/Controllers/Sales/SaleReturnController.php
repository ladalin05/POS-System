<?php

namespace App\Http\Controllers\Sales;

use App\Models\Sales\SaleReturn;
use Illuminate\Http\Request;
use App\DataTables\Sales\SaleReturnDataTable;
use App\Http\Controllers\Controller;

class SaleReturnController extends Controller
{
    public function index(SaleReturnDataTable $dataTable)
    {
        return $dataTable->render('sales.sale_returns.index');
    }

    public function show($id)
    {
        $saleReturn = SaleReturn::with(['items.product', 'customer'])
            ->findOrFail($id);

        return view('sales.sale_returns.show', compact('saleReturn'));
    }

    public function destroy($id)
    {
        $saleReturn = SaleReturn::findOrFail($id);
        $saleReturn->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sale Return deleted successfully.'
        ]);
    }
}