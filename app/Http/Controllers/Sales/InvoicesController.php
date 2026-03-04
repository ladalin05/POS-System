<?php

namespace App\Http\Controllers\Sales;

use App\DataTables\Sales\StockCountDataTable;
use App\DataTables\Sales\InvoiceDataTable;
use App\Http\Controllers\Controller;
use App\Models\Other\Branch;
use App\Models\Sales\SaleItems;
use App\Models\Sales\Sales;

class InvoicesController extends Controller
{

    public function index(InvoiceDataTable $dataTable)
    {
        return $dataTable->render('sales.invoices.index');
    }


    public function modal_view($id)
    {
        $sale = Sales::findOrFail($id);
        $branch = Branch::find($sale->branch_id);
        // filter by sale_id
        $saleItems = SaleItems::where('sale_id', $id)
            ->with('unit')
            ->orderBy('id')
            ->get();

        return view('sales.modal_view', compact('sale', 'saleItems', 'branch'));
    }
    public function delete($id)
    {
        try {
            $form = Sales::findOrFail($id);
            $form->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function StockCount(StockCountDataTable $dataTable)
    {

        return $dataTable->render('sales.stockcount.index');
    }


}
