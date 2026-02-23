<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\BaseUnitDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting\BaseUnitModel;
use App\Models\Setting\Unit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BaseUnitController extends Controller
{
    /**
     * Display a listing of the base-unit conversions.
     */
    public function index(BaseUnitDataTable $dataTable)
    {
        return $dataTable->render('setting.base_unit.index');
    }

    /**
     * Show form for creating a conversion.
     */
    public function create()
    {
        $title = __('global.add_new');
        $form = null;
        $units = Unit::select('id', 'name')->orderBy('name')->get();

        return view('setting.base_unit.form', compact('title', 'form', 'units'));
    }

    /**
     * Store or update a conversion record.
     */
    public function save(Request $request, $id = null)
    {
        // Normalize boolean
        $request->merge(['is_active' => $request->has('is_active') ? 1 : 0]);

        $rules = [
            'from_unit_id' => ['required','integer','exists:units,id', Rule::notIn([$request->input('to_unit_id')])],
            'to_unit_id'   => ['required','integer','exists:units,id'],
            'numerator'    => ['required','numeric','min:0.000001'],
            'is_active'    => ['sometimes','boolean'],
            'id'           => ['sometimes','integer','exists:base_units,id'],
        ];

        $validator = Validator::make($request->all(), $rules, [
            'from_unit_id.not_in' => __('validation.different', ['attribute' => __('From Unit')]),
            'numerator.min' => __('validation.min.numeric', ['attribute' => 'numerator', 'min' => 0.000001]),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = [
                'from_unit_id' => (int) $request->from_unit_id,
                'to_unit_id'   => (int) $request->to_unit_id,
                'numerator'    => (float) $request->numerator,
                'is_active'    => $request->has('is_active') ? (bool) $request->input('is_active') : true,
            ];

            // Optional name (display) if you want to show "case -> can"
            if ($request->filled('name')) {
                $data['name'] = $request->input('name');
            } else {
                // auto-generate name for readability
                $from = Unit::find($data['from_unit_id']);
                $to = Unit::find($data['to_unit_id']);
                $data['name'] = ($from ? $from->name : 'Unit') . ' → ' . ($to ? $to->name : 'Base');
            }

            BaseUnitModel::updateOrCreate(['id' => $id], $data);

            return response()->json([
                'status' => 'success',
                'message' => !empty($id) ? __('messages.user_updated') : __('messages.user_saved'),
                'redirect' => route('setting.base_units.index'),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Edit form
     */
    public function edit($id)
    {
        $title = __('global.edit');
        $form = BaseUnitModel::findOrFail($id);
        $units = Unit::select('id', 'name')->orderBy('name')->get();

        return view('setting.base_unit.form', compact('title', 'form', 'units'));
    }

    /**
     * Delete conversion
     */
    public function delete($id)
    {
        try {
            if ($id == 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('messages.user_cannot_delete'),
                ]);
            }

            $form = BaseUnitModel::findOrFail($id);
            $form->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Toggle active/inactive
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $row = BaseUnitModel::findOrFail($id);
            $row->is_active = ! (bool) $row->is_active;
            $row->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Updated',
                'is_active' => (bool) $row->is_active,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: get conversion factor from a unit to a base unit
     * Example request: GET /setting/base_units/get-conversion?from=5&to=3
     * returns: { success: true, factor: 24, operator: '*' }
     */
    public function getConversion(Request $request)
    {
        $v = Validator::make($request->all(), [
            'from' => 'required|integer|exists:units,id',
            'to'   => 'required|integer|exists:units,id'
        ]);

        if ($v->fails()) {
            return response()->json(['success' => false, 'message' => $v->errors()->first()], 422);
        }

        $from = (int) $request->input('from');
        $to   = (int) $request->input('to');

        // Try direct match
        $conv = BaseUnitModel::where('from_unit_id', $from)
                             ->where('to_unit_id', $to)
                             ->where('is_active', 1)
                             ->first();

        if ($conv) {
            return response()->json([
                'success' => true,
                'factor' => (float) $conv->numerator,
                'operator' => '*', // currently stored as multiply
                'record' => $conv
            ]);
        }

        // Try reverse and invert
        $rev = BaseUnitModel::where('from_unit_id', $to)
                             ->where('to_unit_id', $from)
                             ->where('is_active', 1)
                             ->first();

        if ($rev && (float)$rev->numerator != 0) {
            $factor = 1 / (float) $rev->numerator;
            return response()->json([
                'success' => true,
                'factor' => $factor,
                'operator' => '/', // inverted
                'record' => $rev
            ]);
        }

        // Not found
        return response()->json([
            'success' => false,
            'message' => 'Conversion not found'
        ], 404);
    }

    /**
     * API: list active conversions (for dropdown)
     */
    public function listActive()
    {
        $rows = BaseUnitModel::with(['fromUnit','toUnit'])
                ->where('is_active', 1)
                ->orderBy('id','desc')
                ->get();

        $data = $rows->map(function($r) {
            return [
                'id' => $r->id,
                'from_unit_id' => $r->from_unit_id,
                'from_unit_name' => optional($r->fromUnit)->name,
                'to_unit_id' => $r->to_unit_id,
                'to_unit_name' => optional($r->toUnit)->name,
                'numerator' => (float) $r->numerator,
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }
}
