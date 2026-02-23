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
    public function api(Request $request)
    {
        $endpoint = str_replace('api/v1/', '', $request->path());
        $path = base_path("public/uploads/{$endpoint}.json");
        if (!file_exists($path)) {
            return response()->json([
                'code' => 404,
                'discrption' => 'Not Found',
            ]);
        }
        $data = file_get_contents($path);
        return response()->json([
            'code' => 200,
            'discrption' => 'success',
            'data' => json_decode($data)
        ]);
    }
    public function add()
    {
        $title = __('global.add_new');
        $form = new Category();
        return view('product.categories.form', compact('title', 'form'));
    }
    public function edit($id)
    {
        $title = __('global.edit');
        $form = Category::find($id);
        $methods = (object) [
            (object) ['id' => 'GET', 'name' => 'GET'],
            (object) ['id' => 'POST', 'name' => 'POST'],
            (object) ['id' => 'PUT', 'name' => 'PUT'],
            (object) ['id' => 'PATCH', 'name' => 'PATCH'],
            (object) ['id' => 'DELETE', 'name' => 'DELETE'],
        ];
        return view('product.categories.form', compact('title', 'form', 'methods'));
    }
    // save user
    public function save(Request $request, $id = null)
    {
        try {
            $request->validate([
                // 'url' => 'required',
                'code' => 'required',
                'name' => 'required',
                
                // 'method' => 'required',
                // 'project_id' => 'required',
            ]);
            // $project = Product::find($request->project_id);
            $data = [
                'code' => $request->code,
                'name' => $request->name,
            ];
            // if($request->file('file')) {
            // $path = base_path("public/uploads/{$project->slug}/{$request->url}.json");
            // if(!is_dir(dirname($path))) {
            //     mkdir(dirname($path), 0777, true);
            // }
            // $request->file('file')->move(dirname($path), basename($path));
            // }
            Category::updateOrCreate(['id' => $id], $data);
            return json([
                'status' => 'success',
                'message' => !empty($id) ? __('messages.user_updated') : __('messages.user_saved'),
                'redirect' => route('product.categories.index'),
            ]);
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function delete($id)
    {
        try {
            if ($id == 1) {
                return json([
                    'status' => 'error',
                    'message' => __('messages.user_cannot_delete'),
                ]);
            }
            $form = Category::find($id);
            $form->delete();
            return json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
