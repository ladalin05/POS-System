<?php

namespace App\Http\Controllers\Other;

use App\DataTables\Other\WarehousesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Other\StoreWarehouseRequest;
use App\Http\Requests\Other\UpdateWarehouseRequest;
use App\Models\Other\Branch;
use App\Models\Other\Warehouses;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Throwable;

class WarehousesController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Warehouses::query(); }
        };
    }

    public function index(WarehousesDataTable $dataTable)
    {
        return $dataTable->render('other.warehouses.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreWarehouseRequest::class);
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('messages.create_successfully'),
                    route: route('other.warehouses.index'),
                );
            }

            return $this->viewResponse(
                view:   'other.warehouses.form',
                action: route('other.warehouses.create'),
                data:   [
                    'title'  => __('global.add_new'),
                    'form'   => new Warehouses(),
                    'branch' => Branch::select('id', 'name')->get(),
                ],
            );

        } catch (Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function update(Request $request)
    {
        try {
            $form = Warehouses::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateWarehouseRequest::class);
                $this->service->update($formRequest->validated(), $request->id);

                return $this->redirectResponse(
                    message: __('messages.update_successfully'),
                    route: route('other.warehouses.index'),
                );
            }

            return $this->viewResponse(
                view:   'other.warehouses.form',
                action: route('other.warehouses.update', ['id' => $request->id]),
                data:   [
                    'title'  => __('global.edit'),
                    'form'   => $form,
                    'branch' => Branch::select('id', 'name')->get(),
                ],
            );

        } catch (Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function delete($id)
    {
        try {
            $form = Warehouses::findOrFail($id);
            $form->delete();

            return $this->redirectResponse(
                message: __('messages.delete_warehouse_successfully'),
                route: route('other.warehouses.index'),
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return $this->errorResponse(
                    message: 'No items selected.',
                    code: 422,
                );
            }

            Warehouses::whereIn('id', $ids)->delete();

            return $this->redirectResponse(
                message: __('messages.delete_warehouse_successfully'),
                route: route('other.warehouses.index'),
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}