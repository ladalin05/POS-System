<?php

namespace App\Http\Controllers\Product;
use App\DataTables\Product\CategoryDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Category;
class CategoryController extends Controller
{
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('product.categories.index');
    }

    public function create(Request $request)
    {
        try{

            if($request->isMethod('get')){
                $title = __('global.add_new');
                $form = new Category();
                $action = route('products.categories.add');
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('product.categories.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if($request->isMethod('post')){
                $request->validate([
                    'name' => 'required|string|max:255',
                    'slug' => 'required|string|max:255',
                ]);

                
                $category_image = null;
                if ($request->hasFile('category_image')) {
                    $category_image = uploadImage($request->file('category_image'), null, 'images/category');
                }

                Category::create([
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'parent_id' => $request->parent_id ?? null,
                    'image' => $category_image,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.create_category_success'),
                    'redirect' => route('products.categories.index'),
                    'modal' => 'action-modal',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => __('messages.405'),
            ]);

        } catch(\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try{

            $form = Category::find($request->id);
            
            if($request->isMethod('get')){
                $title = __('global.edit');
                $action = route('products.categories.edit',  ['id' => $request->id]);
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('product.categories.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if($request->isMethod('post')){
                $request->validate([
                    'name' => 'required|string|max:255',
                    'slug' => 'required|string|max:255',
                ]);
                
                $category_image = null;
                if ($request->hasFile('category_image')) {
                    $category_image = updateImage($request->file('category_image'), $form->image, 'images/category');
                }

                $form->update([
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'parent_id' => $request->parent_id ?? null,
                    'image' => $category_image,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.update_category_success'),
                    'redirect' => route('products.categories.index'),
                    'modal' => 'action-modal',
                ]);
            }
            
        } catch(\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function delete(Request $request)
    {
        try {

            $form = Category::find($request->id);

            if (!$form) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Category not found',
                ], 404);
            }

            $form->delete();

            return response()->json([
                'status'  => 'success',
                'message' => __('messages.delete_category_success'),
                'redirect' => route('products.categories.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
