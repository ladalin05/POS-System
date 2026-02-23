<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\UnitConvertDataTable;
use App\Http\Controllers\Controller;
use App\Models\Setting\Unit;
use App\Models\Setting\UnitConvert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UnitConvertController extends Controller
{
    /**
     * List table view
     */
    public function index(UnitConvertDataTable $dataTable)
    {
        return $dataTable->render('setting.unit_convert.index');
    }

    /**
     * Create / Edit form (GET) and Save (POST)
     */
    public function save(Request $request, $id = null)
    {
        try {
            
            if ($request->isMethod('get')) {
                $form = $id ? UnitConvert::findOrFail($id) : new UnitConvert();
                $title = $id ? __('global.edit') : __('global.add_new');
                $action = route('setting.unit_convert.save', ['id' => $id]);
                $units = Unit::select('id', 'name')->orderBy('name')->get();

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('setting.unit_convert.form', compact('title', 'form', 'units', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {
                // Validation rules
                $rules = [
                    'unit_from_id' => ['required', 'integer', 'exists:units,id'],
                    'unit_to_id' => ['required', 'integer', 'exists:units,id'],
                    'numerator' => ['required'],
                    'operator' => ['nullable', 'string', Rule::in(['*', '/'])],
                ];

                $messages = [
                    'unit_from_id.required' => 'From unit is required.',
                    'unit_to_id.required' => 'To unit is required.',
                    'numerator.required' => 'Numerator / factor is required.',
                    'numerator.gt' => 'Numerator must be greater than 0.',
                ];

                $validator = Validator::make($request->all(), $rules, $messages);

                // extra rule: from != to
                $validator->after(function ($v) use ($request) {
                    if ($request->filled('unit_from_id') && $request->filled('unit_to_id')) {
                        if ((int) $request->unit_from_id === (int) $request->unit_to_id) {
                            $v->errors()->add('unit_from_id', 'From unit and To unit must be different.');
                        }
                    }
                });

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $validator->errors()->first(),
                        'errors' => $validator->errors(),
                    ], 422);
                }

                // Prepare data
                $data = [
                    'unit_from_id' => (int) $request->unit_from_id,
                    'unit_to_id' => (int) $request->unit_to_id,
                    'numerator' => (float) $request->numerator,
                    'operator' => $request->filled('operator') ? $request->operator : '*',
                    'is_active' => $request->has('is_active') ? (bool) $request->input('is_active') : true,
                ];

                // Optional human name
                if ($request->filled('name')) {
                    $data['name'] = $request->input('name');
                } else {
                    $from = Unit::find($data['unit_from_id']);
                    $to = Unit::find($data['unit_to_id']);
                    $data['name'] = ($from ? $from->name : 'Unit') . ' → ' . ($to ? $to->name : 'Base');
                }

                UnitConvert::updateOrCreate(['id' => $id], $data);
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => __('messages.user_saved'),
                        'redirect' => route('setting.unit_convert.index'),
                        'modal' => 'action-modal',
                    ]);
                }
                return redirect()
                    ->route('setting.unit_convert.index')
                    ->with('success', __('messages.user_saved'));

            }



        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete single record
     */
    public function delete($id)
    {
        try {
            $row = UnitConvert::findOrFail($id);
            $row->delete();

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
