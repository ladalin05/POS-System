<?php

namespace App\Http\Controllers\People;

use App\DataTables\People\CustomerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\People\StoreCustomerRequest;
use App\Http\Requests\People\UpdateCustomerRequest;
use App\Models\People\Customer;
use App\Models\People\GroupCustomer;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Throwable;

class CustomerController extends Controller
{
    private BaseService $service;

    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return Customer::query(); }
        };
    }

    public function index(CustomerDataTable $dataTable)
    {
        return $dataTable->render('people.customer.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $formRequest = app(StoreCustomerRequest::class);
                $data = $formRequest->validated();

                if ($request->hasFile('attachment')) {
                    $data['attachment'] = $request->file('attachment')->store('attachments/customers', 'public');
                }

                $this->service->create($data);

                return $this->redirectResponse(
                    message: __('messages.customer_saved'),
                    route: route('people.customers.index'),
                );
            }

            return $this->viewResponse(
                view:   'people.customer.form',
                action: route('people.customers.create'),
                data:   [
                    'title' => __('global.add_new'),
                    'form'  => new Customer(),
                    'group_customer' => GroupCustomer::select('id', 'group_name as name')->get(),
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
            $form = Customer::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateCustomerRequest::class);
                $data = $formRequest->validated();

                if ($request->hasFile('attachment')) {
                    $data['attachment'] = $request->file('attachment')->store('attachments/customers', 'public');
                }

                $this->service->update($data, $request->id);

                return $this->redirectResponse(
                    message: __('messages.customer_updated'),
                    route: route('people.customers.index'),
                );
            }

            return $this->viewResponse(
                view:   'people.customer.form',
                action: route('people.customers.update', ['id' => $request->id]),
                data:   [
                    'title' => __('global.edit'),
                    'form'  => $form,
                    'group_customer' => GroupCustomer::select('id', 'group_name as name')->get(),
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

            $form = Customer::findOrFail($id);
            $form->delete();

            return $this->redirectResponse(
                message: __('messages.user_deleted'),
                route: route('people.customers.index'),
            );
        } catch (Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function getCustomer($id)
    {
        $c = Customer::findOrFail($id);

        $item = [
            'id' => $c->id,
            'text' => $c->name,
            'phone' => $c->phone,
            'email' => $c->email,
        ];

        return response()->json([$item]);
    }

    public function suggestions(Request $request, $term = null)
    {
        $q = $term ?? $request->query('term', '');

        $customers = Customer::where('name', 'like', "%{$q}%")
            ->limit(10)
            ->get();

        $results = $customers->map(fn($c) => [
            'id' => $c->id,
            'text' => $c->name,
        ]);

        return response()->json([
            'results' => $results,
        ]);
    }
}