<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\BaseUnitDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\StoreBaseUnitRequest;
use App\Http\Requests\Setting\UpdateBaseUnitRequest;
use App\Models\Setting\BaseUnit;
use App\Models\Setting\Unit;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BaseUnitController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return BaseUnit::query(); }
        };
    }

    public function index(BaseUnitDataTable $dataTable)
    {
        return $dataTable->render('setting.base_unit.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreBaseUnitRequest::class);
                $data = $formRequest->validated();
                $data['created_by'] = Auth::id();

                $this->service->create($data);

                return $this->redirectResponse(
                    message: __('messages.create_success'),
                    route: route('setting.base-units.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'setting.base_unit.form',
                data:   [
                    'form' => new BaseUnit(),
                    'units' => Unit::pluck('name', 'id'),
                ],
                action: route('setting.base-units.create'),
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
            $baseUnit = BaseUnit::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateBaseUnitRequest::class);
                $data = $formRequest->validated();
                $data['updated_by'] = Auth::id();

                $this->service->update($data, $baseUnit->id);

                return $this->redirectResponse(
                    message: __('messages.update_success'),
                    route: route('setting.base-units.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'setting.base_unit.form',
                data:   [
                    'form' => $baseUnit,
                    'units' => Unit::pluck('name', 'id'),
                ],
                action: route('setting.base-units.update', ['id' => $baseUnit->id]),
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
            $baseUnit = BaseUnit::findOrFail($request->id);
            $baseUnit->delete();

            return $this->redirectResponse(
                message: __('messages.delete_success'),
                route: route('setting.base-units.index'),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
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
            'to'   => 'required|integer|exists:units,id',
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
                'record' => $conv,
            ]);
        }

        // Try reverse and invert
        $rev = BaseUnit::where('from_unit_id', $to)
            ->where('to_unit_id', $from)
            ->where('is_active', 1)
            ->first();

        if ($rev && (float) $rev->numerator != 0) {
            $factor = 1 / (float) $rev->numerator;
            return response()->json([
                'success' => true,
                'factor' => $factor,
                'operator' => '/', // inverted
                'record' => $rev,
            ]);
        }

        // Not found
        return response()->json([
            'success' => false,
            'message' => 'Conversion not found',
        ], 404);
    }

    /**
     * API: list active conversions (for dropdown)
     */
    public function listActive()
    {
        $rows = BaseUnit::with(['fromUnit', 'toUnit'])
            ->where('is_active', 1)
            ->orderBy('id', 'desc')
            ->get();

        $data = $rows->map(function ($r) {
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