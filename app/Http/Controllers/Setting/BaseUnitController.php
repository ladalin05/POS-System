<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\BaseUnitDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting\BaseUnit;
use App\Models\Setting\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BaseUnitController extends Controller
{

    public function index(BaseUnitDataTable $dataTable)
    {
        return $dataTable->render('setting.base_unit.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('get')) {

                $title = __('global.add_new');
                $form = new BaseUnit();
                $action = route('setting.base_units.add');

                $units = Unit::pluck('name', 'id');

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('setting.base_units.form', compact('title', 'form', 'action','units'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'from_unit_id' => 'required|exists:units,id',
                    'to_unit_id' => 'required|exists:units,id|different:from_unit_id',
                    'numerator' => 'required|integer|min:1',
                    'is_active' => 'required|boolean',
                ]);

                BaseUnit::create([
                    'from_unit_id' => $request->from_unit_id,
                    'to_unit_id' => $request->to_unit_id,
                    'numerator' => $request->numerator,
                    'is_active' => $request->is_active,
                    'created_by' => Auth::user()->id,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.create_success'),
                    'redirect' => route('setting.base_units.index'),
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

            $form = BaseUnit::find($request->id);

            if ($request->isMethod('get')) {

                $title = __('global.edit');
                $action = route('setting.base_units.edit', ['id' => $request->id]);

                $units = Unit::pluck('name', 'id');

                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('setting.base_units.form', compact('title', 'form', 'action','units'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'from_unit_id' => 'required|exists:units,id',
                    'to_unit_id' => 'required|exists:units,id|different:from_unit_id',
                    'numerator' => 'required|integer|min:1',
                    'is_active' => 'required|boolean',
                ]);

                $form->update([
                    'from_unit_id' => $request->from_unit_id,
                    'to_unit_id' => $request->to_unit_id,
                    'numerator' => $request->numerator,
                    'is_active' => $request->is_active,
                    'updated_by' => Auth::user()->id,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.update_success'),
                    'redirect' => route('setting.base_units.index'),
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

            $form = BaseUnit::find($request->id);

            if (!$form) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'BaseUnit not found',
                ], 404);
            }

            $form->delete();

            return response()->json([
                'status'  => 'success',
                'message' => __('messages.delete_success'),
                'redirect' => route('setting.base_units.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle active/inactive
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $row = BaseUnit::findOrFail($id);
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
        $conv = BaseUnit::where('from_unit_id', $from)
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
        $rev = BaseUnit::where('from_unit_id', $to)
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
        $rows = BaseUnit::with(['fromUnit','toUnit'])
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
