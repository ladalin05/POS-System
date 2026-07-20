<?php

namespace App\Http\Controllers\Stocks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stocks\StoreProductStockRequest;
use App\Http\Requests\Stocks\UpdateProductStockRequest;
use App\DataTables\Stocks\ProductStockDataTable;
use App\Models\Stocks\ProductStock;
use App\Services\ProductStockService;
use Illuminate\Http\Request;

class ManageStockController extends Controller
{
    private ProductStockService $service;

    public function __construct()
    {
        $this->service = new ProductStockService();
    }

    public function index(ProductStockDataTable $dataTable)
    {
        return $dataTable->render('stocks.product_stock.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreProductStockRequest::class);
                $this->service->storeBulk($formRequest->validated());

                return $this->redirectResponse(
                    message: __('messages.create_stock_success'),
                    route: route('stocks.manage.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'stocks.product_stock.form',
                data:   ['form' => new ProductStock()],
                action: route('stocks.manage.create'),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function update(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateProductStockRequest::class);
                $this->service->updateBulk($formRequest->validated());

                return $this->redirectResponse(
                    message: __('messages.update_stock_success'),
                    route: route('stocks.manage.index'),
                );
            }

            $form = ProductStock::findOrFail($request->id);

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'stocks.product_stock.form',
                data:   ['form' => $form],
                action: route('stocks.manage.update', ['id' => $request->id]),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function delete(Request $request)
    {
        try {
            $form = ProductStock::findOrFail($request->id);
            $form->delete();

            return $this->redirectResponse(
                message: __('messages.delete_stock_success'),
                route: route('stocks.manage.index'),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}