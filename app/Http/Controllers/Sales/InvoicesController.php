<?php

namespace App\Http\Controllers\Sales;

use App\DataTables\Sales\StockCountDataTable;
use App\DataTables\Sales\InvoiceDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales\Invoice;
use App\Models\Sales\InvoiceDetail;

class InvoicesController extends Controller
{

    public function index(InvoiceDataTable $dataTable)
    {
        return $dataTable->render('sales.invoices.index');
    }

    public function modal_view(Request $request)
    {
        $invoice = Invoice::with(['customer', 'warehouse', 'currency'])
            ->findOrFail($request->id);

        $invoiceDetails = InvoiceDetail::where('invoice_id', $invoice->id)
            ->with('product')
            ->orderBy('id')
            ->get();

        return $this->modalResponse(
            title: ($invoice->invoice_no ?? '#' . $invoice->id),
            view:  'sales.invoices.invoice',
            data: [
                'invoice'        => $invoice,
                'invoiceDetails' => $invoiceDetails,
            ],
        );
    }

    public function delete(Request $request)
    {
        try {
            $form = Invoice::findOrFail($request->id);
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