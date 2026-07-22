<?php

namespace App\Http\Controllers\Stocks;

use App\DataTables\Stocks\AdjustmentDataTable;
use App\Http\Requests\Stocks\StoreAdjustmentRequest;
use App\Http\Requests\Stocks\UpdateAdjustmentRequest;
use App\Http\Controllers\Controller;
use App\Models\Stocks\Adjustment;
use App\Models\Stocks\AdjustmentItem;
use App\Models\Stocks\StockMove;
use App\Models\Product\Product;
use App\Models\Setting\Unit;
use App\Services\AdjustmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdjustmentController extends Controller
{
    protected AdjustmentService $adjustmentService;

    public function __construct(AdjustmentService $adjustmentService)
    {
        $this->adjustmentService = $adjustmentService;
    }

    public function index(AdjustmentDataTable $dataTable)
    {
        return $dataTable->render('stocks.adjustment.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreAdjustmentRequest::class);
                $this->adjustmentService->save($formRequest->validated(), $formRequest);

                return $this->redirectResponse(
                    message: __('messages.user_saved'),
                    route: route('stocks.adjustment.index'),
                );
            }

            return $this->viewResponse(
                view:   'stocks.adjustment.form',
                action: route('stocks.adjustment.create'),
                data:   [
                    'title' => __('add_adjustment'),
                    'form' => new Adjustment(),
                    ...$this->adjustmentService->getFormOptions(),
                ],
            );

        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $form = $this->adjustmentService->find($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateAdjustmentRequest::class);
                $this->adjustmentService->save($formRequest->validated(), $formRequest, $request->id);

                return $this->redirectResponse(
                    message: __('messages.user_updated'),
                    route: route('stocks.adjustment.index'),
                );
            } 
            return $this->viewResponse(
                view:   'stocks.adjustment.form',
                action: route('stocks.adjustment.update', ['id' => $request->id]),
                data:   [
                    'title' => __('global.edit'),
                    'form' => $form,
                    ...$this->adjustmentService->getFormOptions($form->id),
                ],
            );

        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $this->adjustmentService->delete((int) $id);

            return $this->successResponse(__('messages.user_deleted'));
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function approve($id)
    {
        try {
            $this->adjustmentService->approve((int) $id);

            return $this->successResponse('Approved. Stock moves created.');
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function modal($id)
    {
        $form = $this->adjustmentService->find($id);

        $items = AdjustmentItem::with(['product:id,product_name,sku'])
            ->where('adjustment_id', $id)
            ->orderBy('id')
            ->get();

        return view('adjustment.modal_view', compact('form', 'items'));
    }

    public function ajaxProducts(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $products = Product::select('id', 'product_name', 'sku')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('product_name', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%");
                });
            })
            ->orderBy('product_name')
            ->limit(100)
            ->get();

        return response()->json($products);
    }

    public function ajaxProductUnits(Request $request)
    {
        $productId = $request->product_id;
        if (!$productId) {
            return response()->json([], 200);
        }

        $product = Product::with([
            'unit:id,name,code',
            'productUnits.unit:id,name,code',
        ])->findOrFail($productId);

        $out = [];

        if ($product->unit) {
            $out[] = [
                'id' => (int) $product->unit->id,
                'name' => $product->unit->name,
                'code' => $product->unit->code,
                'qty' => 1.0,
                'is_base' => true,
            ];
        }

        foreach ($product->productUnits as $pu) {
            if (!$pu->unit) continue;
            $out[] = [
                'id' => (int) $pu->unit->id,
                'name' => $pu->unit->name,
                'code' => $pu->unit->code,
                'qty' => (float) ($pu->qty ?? 1),
                'is_base' => false,
            ];
        }

        return response()->json($out);
    }

    public function ajaxUnits(Request $request)
    {
        $units = Unit::select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        return response()->json($units);
    }

    public function ajaxQoh(Request $request)
    {
        $warehouseId = $request->integer('warehouse_id');
        $productIds = (array) $request->input('product_ids', []);

        $q = StockMove::select('product_id', DB::raw('SUM(quantity) as qoh'))
            ->when(!empty($productIds), fn($qq) => $qq->whereIn('product_id', $productIds))
            ->when($warehouseId, fn($qq) => $qq->where('warehouse_id', $warehouseId))
            ->groupBy('product_id');

        return response()->json($q->pluck('qoh', 'product_id'));
    }
}