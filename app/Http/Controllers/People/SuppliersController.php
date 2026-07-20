<?php

namespace App\Http\Controllers\People;

use App\DataTables\People\SuppliersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\People\StoreSupplierRequest;
use App\Http\Requests\People\UpdateSupplierRequest;
use App\Models\People\Suppliers;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Throwable;

class SuppliersController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Suppliers::query(); }
        };
    }

    public function index(SuppliersDataTable $dataTable)
    {
        return $dataTable->render('people.suppliers.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreSupplierRequest::class);
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('messages.suppliers_saved'),
                    route: route('people.suppliers.index'),
                );
            }

            return $this->viewResponse(
                view:   'people.suppliers.form',
                action: route('people.suppliers.create'),
                data:   [
                    'title' => __('global.add_new'),
                    'form'  => new Suppliers(),
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
            $form = Suppliers::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateSupplierRequest::class);
                $this->service->update($formRequest->validated(), $request->id);

                return $this->redirectResponse(
                    message: __('messages.suppliers_updated'),
                    route: route('people.suppliers.index'),
                );
            }

            return $this->viewResponse(
                view:   'people.suppliers.form',
                action: route('people.suppliers.update', ['id' => $request->id]),
                data:   [
                    'title' => __('global.edit'),
                    'form'  => $form,
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
            if ($id == 1) {
                return $this->errorResponse(
                    message: __('messages.user_cannot_delete'),
                    code: 422,
                );
            }

            $form = Suppliers::findOrFail($id);
            $form->delete();

            return $this->redirectResponse(
                message: __('messages.user_deleted'),
                route: route('people.suppliers.index'),
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}