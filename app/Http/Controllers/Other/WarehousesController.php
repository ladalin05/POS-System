<?php

namespace App\Http\Controllers\Other;

use App\DataTables\Other\WarehousesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Other\Branch;
use App\Models\Other\Warehouses;
use Illuminate\Http\Request;

use function Laravel\Prompts\select;

class WarehousesController extends Controller
{


    public function index(WarehousesDataTable $dataTable)
    {
        return $dataTable->render('other.warehouses.index');
    }
    public function add(Request $request, $id = null)
    {

        try {
            if ($request->isMethod('get')) {
                $form = $id ? Warehouses::findOrFail($id) : new Warehouses();
                $branch = Branch::select('id', 'name')->get();

                $title = $id ? __('global.edit') : __('global.add_new');

                $action = route('other.warehouses.add', ['id' => $id]);

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('other.warehouses.form', compact('title', 'form', 'branch', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }
            if (request()->isMethod('post')) {
                $rules = [
                    'code' => 'required|string|max:255',
                    'branch_id' => 'required',
                    'name' => 'required|string|max:255',
                    'phone' => 'required|string|max:100',
                    'address' => 'required|string|max:1000',
                    'email' => 'required|email|max:255',
                ];
                $data = $request->validate($rules);

                Warehouses::updateOrCreate(['id' => $id], $data);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.user_saved'),
                    'redirect' => route('other.warehouses.index'),
                    'modal' => 'action-modal',

                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => __('messages.405'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $Warehouses = Warehouses::findOrFail($id);
            $Warehouses->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No items selected.',
                ]);
            }

            Warehouses::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
