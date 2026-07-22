<?php

namespace App\Http\Controllers\Setting;

use App\DataTables\Setting\FloorDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\StoreFloorRequest;
use App\Http\Requests\Setting\UpdateFloorRequest;
use App\Models\Setting\Floor;
use App\Services\BaseService;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Floor::query(); }
        };
    }

    public function index(FloorDataTable $dataTable)
    {
        return $dataTable->render('setting.floor.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreFloorRequest::class);
                $data = $formRequest->validated();

                $this->service->create($data);

                return $this->redirectResponse(
                    message: __('messages.create_success'),
                    route: route('setting.floor.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'setting.floor.form',
                data:   ['form' => new Floor()],
                action: route('setting.floor.create'),
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
            $floor = Floor::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateFloorRequest::class);
                $data = $formRequest->validated();

                $this->service->update($data, $floor->id);

                return $this->redirectResponse(
                    message: __('messages.update_success'),
                    route: route('setting.floor.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'setting.floor.form',
                data:   ['form' => $floor],
                action: route('setting.floor.update', ['id' => $floor->id]),
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
            $floor = Floor::findOrFail($request->id);
            $floor->delete();

            return $this->redirectResponse(
                message: __('messages.delete_success'),
                route: route('setting.floor.index'),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}