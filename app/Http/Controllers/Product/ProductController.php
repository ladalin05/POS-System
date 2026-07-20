<?php

namespace App\Http\Controllers\Product;

use App\DataTables\Product\AlertQuantityDataTable;
use App\DataTables\Product\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Throwable;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('product.products.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreProductRequest::class);
                $this->productService->save($formRequest->validated(), $formRequest);

                return $this->redirectResponse(
                    message: __('messages.create_product_successfully'),
                    route: route('products.products.index'),
                );
            }

            return $this->viewResponse(
                view:   'product.products.form',
                action: route('products.products.create'),
                data:   [
                    'title' => __('global.create_new_product'),
                    'product' => new Product(),
                    $this->productService->getFormOptions()
                ],
            );
            
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $product  = $this->productService->find($request->id);

            if($request->isMethod('post')) {
                $formRequest = app(UpdateProductRequest::class);
                $this->productService->save($formRequest->validated(), $formRequest, $request->id);

                return $this->redirectResponse(
                    message: __('messages.update_product_successfully'),
                    route: route('products.products.index'),
                );
            }

            return $this->viewResponse(
                view:   'product.products.form',
                action: route('products.products.update', ['id' => $request->id]),
                data:   [
                    'title' => __('global.update_product'),
                    'product' => $product,
                    $this->productService->getFormOptions(),
                ],
            );
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $this->productService->delete((int) $id);

            return $this->successResponse(__('messages.user_deleted'));
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function view($id)
    {
        $product = $this->productService->find($id);

        return view('product.products.view', compact('product'));
    }

    public function alert_quantity(AlertQuantityDataTable $dataTable)
    {
        return $dataTable->render('product.products.alert_qty.index');
    }
}