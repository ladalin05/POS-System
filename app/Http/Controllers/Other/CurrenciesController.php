<?php

namespace App\Http\Controllers\Other;

use App\DataTables\Other\CurrenciesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Other\StoreCurrencyRequest;
use App\Http\Requests\Other\UpdateCurrencyRequest;
use App\Models\Other\Currencies;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Throwable;

class CurrenciesController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Currencies::query(); }
        };
    }

    public function index(CurrenciesDataTable $dataTable)
    {
        return $dataTable->render('other.currencies.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreCurrencyRequest::class);
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('messages.create_successfully'),
                    route: route('other.currencies.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'other.currencies.form',
                data:   ['form' => new Currencies()],
                action: route('other.currencies.create'),
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
            $form = Currencies::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateCurrencyRequest::class);
                $this->service->update($formRequest->validated(), $request->id);

                return $this->redirectResponse(
                    message: __('messages.update_successfully'),
                    route: route('other.currencies.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.edit'),
                view:   'other.currencies.form',
                data:   ['form' => $form],
                action: route('other.currencies.update', ['id' => $request->id]),
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
            $form = Currencies::findOrFail($id);
            $form->delete();

            return $this->redirectResponse(
                message: __('messages.delete_currency_successfully'),
                route: route('other.currencies.index'),
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

            Currencies::whereIn('id', $ids)->delete();

            return $this->redirectResponse(
                message: __('messages.delete_currency_successfully'),
                route: route('other.currencies.index'),
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}