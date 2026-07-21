<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreCategoryRequest;
use App\Http\Requests\Product\UpdateCategoryRequest;
use App\DataTables\Product\CategoryDataTable;
use App\Models\Product\Category;
use App\Services\BaseService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Category::query(); }
        };
    }

    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('product.categories.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreCategoryRequest::class);
                $data = $formRequest->validated();

                if ($request->hasFile('category_image')) {
                    $data['image'] = uploadImage($request->file('category_image'), null, 'images/category');
                }

                $this->service->create($data);

                return $this->redirectResponse(
                    message: __('messages.create_category_success'),
                    route: route('products.categories.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'product.categories.form',
                data:   ['form' => new Category()],
                action: route('products.categories.create'),
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
            $category = Category::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateCategoryRequest::class);
                $data = $formRequest->validated();

                if ($request->hasFile('category_image')) {
                    $data['image'] = updateImage($request->file('category_image'), $category->image, 'images/category');
                }

                $this->service->update($data, $category->id);

                return $this->redirectResponse(
                    message: __('messages.update_category_success'),
                    route: route('products.categories.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'product.categories.form',
                data:   ['form' => $category],
                action: route('products.categories.update', ['id' => $category->id]),
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
            $category = Category::findOrFail($request->id);
            $category->delete();

            return $this->redirectResponse(
                message: __('messages.delete_category_success'),
                route: route('products.categories.index'),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}