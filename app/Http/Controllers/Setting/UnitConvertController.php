<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\UnitConvertDataTable;
use App\Http\Controllers\Controller;
use App\Models\Setting\Unit;
use App\Models\Setting\UnitConvert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UnitConvertController extends Controller
{
    
    public function index(UnitConvertDataTable $dataTable)
    {
        return $dataTable->render('setting.unit_convert.index');
    }
    
    public function create(Request $request)
    {
        try {

            if ($request->isMethod('get')) {

                $title = __('global.add_new');
                $form = new UnitConvert();
                $action = route('setting.unit_converts.add');

                $units = Unit::pluck('name', 'id');

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('setting.unit_converts.form', compact('title','form','action','units'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'unit_from_id' => 'required|exists:units,id',
                    'unit_to_id' => 'required|exists:units,id|different:unit_from_id',
                    'numerator' => 'required|numeric|min:0',
                    'operator' => 'required|string|max:2',
                    'name' => 'nullable|string|max:255',
                    'is_active' => 'required|boolean',
                ]);

                UnitConvert::create([
                    'unit_from_id' => $request->unit_from_id,
                    'unit_to_id' => $request->unit_to_id,
                    'numerator' => $request->numerator,
                    'operator' => $request->operator,
                    'name' => $request->name,
                    'is_active' => $request->is_active,
                    'created_by' => Auth::user()->id,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.create_success'),
                    'redirect' => route('setting.unit_converts.index'),
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

            $form = UnitConvert::find($request->id);

            if ($request->isMethod('get')) {

                $title = __('global.edit');
                $action = route('setting.unit_converts.edit',['id'=>$request->id]);

                $units = Unit::pluck('name','id');

                return response()->json([
                    'title'=>$title,
                    'status'=>'success',
                    'message'=>'success',
                    'html'=>view('setting.unit_converts.form',compact('title','form','action','units'))->render(),
                    'modal'=>'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'unit_from_id' => 'required|exists:units,id',
                    'unit_to_id' => 'required|exists:units,id|different:unit_from_id',
                    'numerator' => 'required|numeric|min:0',
                    'operator' => 'required|string|max:2',
                    'name' => 'nullable|string|max:255',
                    'is_active' => 'required|boolean',
                ]);

                $form->update([
                    'unit_from_id' => $request->unit_from_id,
                    'unit_to_id' => $request->unit_to_id,
                    'numerator' => $request->numerator,
                    'operator' => $request->operator,
                    'name' => $request->name,
                    'is_active' => $request->is_active,
                    'updated_by' => Auth::user()->id,
                ]);

                return response()->json([
                    'status'=>'success',
                    'message'=>__('messages.update_success'),
                    'redirect'=>route('setting.unit_converts.index'),
                    'modal'=>'action-modal',
                ]);
            }

        } catch (\Exception $e) {

            return response()->json([
                'status'=>'error',
                'message'=>$e->getMessage()
            ],500);

        }
    }

    public function delete(Request $request)
    {
        try {

            $form = UnitConvert::find($request->id);

            if (!$form) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'UnitConvert not found',
                ], 404);
            }

            $form->delete();

            return response()->json([
                'status'  => 'success',
                'message' => __('messages.delete_success'),
                'redirect' => route('setting.unit_converts.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = (array) $request->input('ids', []);
            if (empty($ids)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No items selected.',
                ], 422);
            }

            UnitConvert::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: get conversion factor (try direct, then reverse invert)
     * GET params: from, to
     */
    public function getConversion(Request $request)
    {
        $v = Validator::make($request->all(), [
            'from' => 'required|integer|exists:units,id',
            'to' => 'required|integer|exists:units,id',
        ]);

        if ($v->fails()) {
            return response()->json(['success' => false, 'message' => $v->errors()->first()], 422);
        }

        $from = (int) $request->input('from');
        $to = (int) $request->input('to');

        // direct
        $conv = UnitConvert::where('unit_from_id', $from)
            ->where('unit_to_id', $to)
            ->where('is_active', 1)
            ->first();

        if ($conv) {
            return response()->json([
                'success' => true,
                'factor' => (float) $conv->numerator,
                'operator' => $conv->operator ?? '*',
                'record' => $conv,
            ]);
        }

        // try reverse (to -> from) and invert
        $rev = UnitConvert::where('unit_from_id', $to)
            ->where('unit_to_id', $from)
            ->where('is_active', 1)
            ->first();

        if ($rev && (float) $rev->numerator != 0) {
            $factor = 1 / (float) $rev->numerator;
            return response()->json([
                'success' => true,
                'factor' => $factor,
                'operator' => '/', // indicate inverted
                'record' => $rev,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Conversion not found'], 404);
    }

    /**
     * API: list active conversions
     */
    public function listActive()
    {
        $rows = UnitConvert::with(['fromUnit', 'toUnit'])
            ->where('is_active', 1)
            ->orderByDesc('id')
            ->get();

        $data = $rows->map(function ($r) {
            return [
                'id' => $r->id,
                'from_unit_id' => $r->unit_from_id,
                'from_unit_name' => optional($r->fromUnit)->name,
                'to_unit_id' => $r->unit_to_id,
                'to_unit_name' => optional($r->toUnit)->name,
                'numerator' => (float) $r->numerator,
                'operator' => $r->operator ?? '*',
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }
}
