<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\UnitsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\StoreUnitRequest;
use App\Http\Requests\Setting\UpdateUnitRequest;
use App\Models\Setting\Unit;
use App\Services\BaseService;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Unit::query(); }
        };
    }

    public function index(UnitsDataTable $dataTable)
    {
        return $dataTable->render('setting.units.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreUnitRequest::class);
                $data = $formRequest->validated();

                $this->service->create($data);

                return $this->redirectResponse(
                    message: __('messages.create_unit_success'),
                    route: route('setting.units.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'setting.units.form',
                data:   ['form' => new Unit()],
                action: route('setting.units.create'),
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
            $unit = Unit::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateUnitRequest::class);
                $data = $formRequest->validated();

                $this->service->update($data, $unit->id);

                return $this->redirectResponse(
                    message: __('messages.update_success'),
                    route: route('setting.units.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'setting.units.form',
                data:   ['form' => $unit],
                action: route('setting.units.update', ['id' => $unit->id]),
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
            $unit = Unit::findOrFail($request->id);
            $unit->delete();

            return $this->redirectResponse(
                message: __('messages.delete_unit_success'),
                route: route('setting.units.index'),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}