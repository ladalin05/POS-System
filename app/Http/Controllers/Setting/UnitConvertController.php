<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\UnitConvertDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\StoreUnitConvertRequest;
use App\Http\Requests\Setting\UpdateUnitConvertRequest;
use App\Models\Setting\Unit;
use App\Models\Setting\UnitConvert;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UnitConvertController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return UnitConvert::query(); }
        };
    }

    public function index(UnitConvertDataTable $dataTable)
    {
        return $dataTable->render('setting.unit_convert.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreUnitConvertRequest::class);
                $data = $formRequest->validated();
                $data['created_by'] = Auth::id();

                $this->service->create($data);

                return $this->redirectResponse(
                    message: __('messages.create_success'),
                    route: route('setting.unit_converts.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'setting.unit_converts.form',
                data:   [
                    'form' => new UnitConvert(),
                    'units' => Unit::pluck('name', 'id'),
                ],
                action: route('setting.unit_converts.add'),
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
            $unitConvert = UnitConvert::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateUnitConvertRequest::class);
                $data = $formRequest->validated();
                $data['updated_by'] = Auth::id();

                $this->service->update($data, $unitConvert->id);

                return $this->redirectResponse(
                    message: __('messages.update_success'),
                    route: route('setting.unit_converts.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'setting.unit_converts.form',
                data:   [
                    'form' => $unitConvert,
                    'units' => Unit::pluck('name', 'id'),
                ],
                action: route('setting.unit_converts.edit', ['id' => $unitConvert->id]),
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
            $unitConvert = UnitConvert::findOrFail($request->id);
            $unitConvert->delete();

            return $this->redirectResponse(
                message: __('messages.delete_success'),
                route: route('setting.unit_converts.index'),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
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