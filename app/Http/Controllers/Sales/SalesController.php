<?php

namespace App\Http\Controllers\Sales;

use App\DataTables\Sales\StockCountDataTable;
use App\DataTables\Sales\SalesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Biller\Biller;
use App\Models\Other\Branch;
use App\Models\Sales\SaleItems;
use App\Models\Sales\Sales;
use Illuminate\Http\Request;
use Throwable;

class SalesController extends Controller
{
    public function index(SalesDataTable $dataTable)
    {
        return $dataTable->render('sales.index');
    }

    public function modal_view(Request $request)
    {
        try {
            $sale = Sales::findOrFail($request->id);
            $branch = Branch::find($sale->branch_id);
            $saleItems = SaleItems::where('sale_id', $request->id)
                ->with('unit')
                ->orderBy('id')
                ->get();

            return $this->modalResponse(
                title:  __('global.view'),
                view:   'sales.modal_view',
                data:   [
                    'sale' => $sale,
                    'saleItems' => $saleItems,
                    'branch' => $branch,
                ],
            );

        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $form = Sales::findOrFail($request->id);
            $form->delete();

            return $this->successResponse(__('messages.user_deleted'));

        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function StockCount(StockCountDataTable $dataTable)
    {
        return $dataTable->render('sales.stockcount.index');
    }
}