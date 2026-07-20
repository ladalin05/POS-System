<?php

namespace App\Http\Controllers\Other;

use App\DataTables\Other\CashAccountDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Other\StoreCashAccountRequest;
use App\Http\Requests\Other\UpdateCashAccountRequest;
use App\Models\Other\CashAccount;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Throwable;

class CashAccountController extends Controller
{

    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return CashAccount::query(); }
        };
    }

    public function index(CashAccountDataTable $dataTable)
    {
        return $dataTable->render('other.cash_accounts.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreCashAccountRequest::class);
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('messages.create_successfully'),
                    route: route('other.cash_accounts.index'),
                );
            }

            return $this->viewResponse(
                view:   'other.cash_accounts.form',
                action: route('other.cash_accounts.create'),
                data:   [
                    'title' => __('global.add_new'),
                    'form' => new CashAccount(),
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
            $form = CashAccount::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateCashAccountRequest::class);
                $this->service->update($formRequest->validated(), $request->id);

                return $this->redirectResponse(
                    message: __('messages.update_successfully'),
                    route: route('other.cash_accounts.index'),
                );
            }

            return $this->viewResponse(
                view:   'other.cash_accounts.form',
                action: route('other.cash_accounts.update', ['id' => $request->id]),
                data:   [
                    'title' => __('global.edit'),
                    'form' => $form,
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
            $form = CashAccount::findOrFail($id);
            $form->delete();

            return $this->redirectResponse(
                message: __('messages.delete_cash_account_successfully'),
                route: route('other.cash_accounts.index'),
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}