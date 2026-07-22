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

    public function modal_view(Request $request)
    {
        $saleReturn = SaleReturn::with(['customer', 'warehouse', 'sale'])
            ->findOrFail($request->id);

        $saleReturnItems = $saleReturn->items()
            ->with('product')
            ->orderBy('id')
            ->get();

        return $this->modalResponse(
            title: ($saleReturn->reference_no ?? '#' . $saleReturn->id),
            view:  'sales.sale_returns.sale_return',
            data: [
                'saleReturn'      => $saleReturn,
                'saleReturnItems' => $saleReturnItems,
            ],
        );
    }

    public function destroy($id)
    {
        try {
            $saleReturn = SaleReturn::findOrFail($id);
            $saleReturn->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sale Return deleted successfully.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}