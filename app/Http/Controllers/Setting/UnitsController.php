<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\UnitsDataTable;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\Setting\Unit;

class UnitsController extends Controller
{
    public function index(UnitsDataTable $dataTable)
    {
        return $dataTable->render('setting.units.index');
    }
   
    public function add()
    {
        $title = __('global.add_new');
        $form = new Unit();
        return view('setting.units.form', compact('title', 'form'));
    }
    public function edit($id)
    {
        $title = __('global.edit');
        $form = Unit::find($id);
        $methods = (object) [
            (object) ['id' => 'GET', 'name' => 'GET'],
            (object) ['id' => 'POST', 'name' => 'POST'],
            (object) ['id' => 'PUT', 'name' => 'PUT'],
            (object) ['id' => 'PATCH', 'name' => 'PATCH'],
            (object) ['id' => 'DELETE', 'name' => 'DELETE'],
        ];
        return view('setting.units.form', compact('title', 'form', 'methods'));
    }
    // save user
    public function save(Request $request, $id = null)
    {
        try {
            $request->validate([
                'code' => 'required',
                'name' => 'required',
                'operator' => 'nullable|string',
                'unit_value' => 'nullable|string',
                'operation_value' => 'nullable|string'
            ]);
            $data = [
                'code' => $request->code,
                'name' => $request->name,
                'operator' => $request->operator,
                'unit_value' => $request->unit_value,
                'operation_value' => $request->operation_value,
            ];
            Unit::updateOrCreate(['id' => $id], $data);
            return json([
                'status' => 'success',
                'message' => !empty($id) ? __('messages.user_updated') : __('messages.user_saved'),
                'redirect' => route('setting.units.index'),
            ]);
        } catch (Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function delete($id)
    {
        try {
            $form = Unit::find($id);
            $form->delete();
            return json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
