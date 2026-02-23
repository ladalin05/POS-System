<?php

namespace App\Http\Controllers\People;

use App\DataTables\People\CustomerDataTable;
use App\Http\Controllers\Controller;
use App\Models\People\Customer;
use App\Models\People\GroupCustomer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(CustomerDataTable $dataTable)
    {
        return $dataTable->render('people.customer.index');
    }

    public function add()
    {
        $title = __('global.add_new');
        $form = new Customer();
        $group_customer = GroupCustomer::select('id', 'group_name as name')->get();

        return view('people.customer.form', compact('title', 'form', 'group_customer'));
    }
    public function edit($id)
    {
        $title = __('global.edit');
        $form = Customer::find($id);
        $group_customer = GroupCustomer::select('id', 'group_name as name')->get();

        return view('people.customer.form', compact('title', 'form', 'group_customer'));
    }
    // save user
    public function save(Request $request, $id = null)
    {
        try {
            $request->validate([
                'customer_group_id' => 'required|exists:group_customers,id',
                'code' => 'required|string|max:255',
                'company' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:50',
                'address' => 'required|string|max:255',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'email_address' => 'nullable|email|max:255',
                'vat_number' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:50',
                'country' => 'nullable|string|max:100',
                'credit_day' => 'nullable|integer',
                'credit_amount' => 'nullable|numeric',
                // 'price_group_id' => 'nullable|exists:price_groups,id',
                // 'salesman_id' => 'nullable|exists:users,id',
                'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
            ]);

            $data = $request->only([
                'customer_group_id',
                'code',
                'company',
                'name',
                'phone',
                'address',
                'city',
                'state',
                'email_address',
                'vat_number',
                'postal_code',
                'country',
                'credit_day',
                'credit_amount',
                // 'price_group_id',
                // 'salesman_id',
            ]);

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $path = $file->store('attachments/customers', 'public');
                $data['attachment'] = $path;
            }

            Customer::updateOrCreate(['id' => $id], $data);

            return response()->json([
                'status' => 'success',
                'message' => $id ? __('messages.customer_updated') : __('messages.customer_saved'),
                'redirect' => route('people.customer.index'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function delete($id)
    {
        try {
            if ($id == 1) {
                return json([
                    'status' => 'error',
                    'message' => __('messages.user_cannot_delete'),
                ]);
            }
            $form = Customer::find($id);
            $form->delete();
            return json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Exception $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getCustomer($id)
    {
        $c = Customer::findOrFail($id);
        $item = [
            'id' => $c->id,
            'text' => $c->name,
            // any extra fields your UI needs:
            'phone' => $c->phone,
            'email' => $c->email,
        ];

        // callback(data[0]) expects an array:
        return response()->json([$item]);
    }

    /**
     * select2 ajax suggestions: return { results: [...] }
     */
    public function suggestions(Request $request, $term = null)
    {
        // read either the URL segment or query-param ‘term’
        $q = $term ?? $request->query('term', '');

        $customers = Customer::where('name', 'like', "%{$q}%")
            ->limit(10)
            ->get();

        // map to { id, text } for Select2 v3
        $results = $customers->map(fn($c) => [
            'id' => $c->id,
            'text' => $c->name,
        ]);

        return response()->json([
            'results' => $results,
        ]);
    }
}
