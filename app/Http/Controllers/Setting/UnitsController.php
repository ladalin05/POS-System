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
   
    public function create(Request $request)
    {
        try {

            if ($request->isMethod('get')) {

                $title = __('global.add_new');
                $form = new Unit();
                $action = route('setting.units.add');

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('setting.units.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'code' => 'required|string|max:55',
                    'name' => 'required|string|max:55',
                    'operator' => 'nullable|string|max:1',
                    'unit_value' => 'nullable|string|max:55',
                    'operation_value' => 'nullable|string|max:55',
                    'base_unit' => 'nullable|exists:units,id',
                ]);

                Unit::create([
                    'code' => $request->code,
                    'name' => $request->name,
                    'base_unit' => $request->base_unit ?? null,
                    'operator' => $request->operator,
                    'unit_value' => $request->unit_value,
                    'operation_value' => $request->operation_value,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.create_unit_success'),
                    'redirect' => route('setting.units.index'),
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
                'message' => $e->getMessage()
            ], 500);

        }
    }
    
    public function update(Request $request)
    {
        try {

            $form = Unit::find($request->id);

            if ($request->isMethod('get')) {

                $title = __('global.edit');
                $action = route('setting.units.edit', ['id' => $request->id]);

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('setting.units.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'code' => 'required|string|max:55',
                    'name' => 'required|string|max:55',
                    'operator' => 'nullable|string|max:1',
                    'unit_value' => 'nullable|string|max:55',
                    'operation_value' => 'nullable|string|max:55',
                    'base_unit' => 'nullable|exists:units,id',
                ]);

                $form->update([
                    'code' => $request->code,
                    'name' => $request->name,
                    'base_unit' => $request->base_unit ?? null,
                    'operator' => $request->operator,
                    'unit_value' => $request->unit_value,
                    'operation_value' => $request->operation_value,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.update_success'),
                    'redirect' => route('setting.units.index'),
                    'modal' => 'action-modal',
                ]);
            }

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function delete(Request $request)
    {
        try {

            $form = Unit::find($request->id);

            if (!$form) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Unit not found',
                ], 404);
            }

            $form->delete();

            return response()->json([
                'status'  => 'success',
                'message' => __('messages.delete_unit_success'),
                'redirect' => route('setting.units.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
