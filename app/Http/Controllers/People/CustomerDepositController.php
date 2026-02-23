<?php

namespace App\Http\Controllers\People;

use App\DataTables\People\CustomerDepositDataTable;
use App\Http\Controllers\Controller;
use App\Models\Other\Branch;
use App\Models\Other\CashAccount;
use App\Models\People\Customer;
use App\Models\People\CustomerDeposit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerDepositController extends Controller
{
    public function index(CustomerDepositDataTable $dataTable)
    {
        return $dataTable->render('people.customer_deposit.index');
    }

    public function add()
    {
        $title = __('global.add_new');
        $form = new CustomerDeposit();

        $customer = Customer::select('id', 'name')->orderBy('name')->get();
        $paid_by = CashAccount::select('id', 'name')->orderBy('name')->get();
        $branches = Branch::select('id', 'name')->orderBy('name')->get();

        return view('people.customer_deposit.form', compact('title', 'form', 'customer', 'paid_by', 'branches'));
    }

    public function edit($id)
    {
        $title = __('global.edit');
        $form = CustomerDeposit::findOrFail($id);

        $customer = Customer::select('id', 'name')->orderBy('name')->get();

        $paid_by = CashAccount::select('id', 'name')->orderBy('name')->get();
        $branches = Branch::select('id', 'name')->orderBy('name')->get();

        return view('people.customer_deposit.form', compact('title', 'form', 'customer', 'paid_by', 'branches'));
    }

    public function nextReference(Carbon $whenLocal): string
    {

        $prefix = 'ML/' . $whenLocal->format('Y/m') . '/';
        $lastRef = CustomerDeposit::where('reference_no', 'like', $prefix . '%')
            ->orderByDesc('reference_no')
            ->value('reference_no');
        $next = 1;
        if ($lastRef && preg_match('/(\d+)$/', $lastRef, $m)) {
            $next = (int) $m[1] + 1;
        }
        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);

    }
    // save
    public function save(Request $request, $id = null)
    {
        try {
            // Normalize amount
            $request->merge([
                'amount' => $request->filled('amount') ? (float) $request->input('amount') : null,
            ]);

            // Validate
            $request->validate([
                'customer_id' => ['required', 'exists:customers,id'],
                'reference_no' => ['nullable', 'string', 'max:50'], // allow auto-gen
                'branch_id' => ['required', 'exists:branches,id'],
                'amount' => ['nullable', 'numeric', 'min:0'],
                'paid_by' => ['required', 'exists:cash_accounts,id'],
                'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,docx', 'max:2048'],
                'note' => ['nullable', 'string'],
                'date' => ['nullable', 'date'],
            ]);

            // Local time -> UTC (optional: store both or just UTC)
            $whenLocal = $request->filled('date')
                ? Carbon::parse($request->input('date'), 'Asia/Phnom_Penh')
                : now('Asia/Phnom_Penh');
            $whenUtc = $whenLocal->copy()->utc();

            // Determine reference number
            if ($id) {
                $existing = CustomerDeposit::find($id);
                $referenceNo = $request->filled('reference_no')
                    ? $request->reference_no
                    : ($existing?->reference_no ?? $this->nextReference($whenLocal));
            } else {
                $referenceNo = $request->filled('reference_no')
                    ? $request->reference_no
                    : $this->nextReference($whenLocal);
            }

            // Build data payload (keys only), then override reference_no & dates
            $data = $request->only([
                'customer_id',
                'branch_id',
                'amount',
                'paid_by',
                'note',
            ]);
            $data['reference_no'] = $referenceNo;

            // Choose how you store the date (pick one style)
            // Option A: store UTC timestamp in 'date'
            $data['date'] = $whenUtc;

            // Handle file upload
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('attachments/customersDeposit', 'public');
                $data['attachment'] = $path;
            }

            CustomerDeposit::updateOrCreate(['id' => $id], $data);

            return response()->json([
                'status' => 'success',
                'message' => $id ? __('messages.updated') : __('messages.saved'),
                'redirect' => route('people.customer_deposit.index'),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function delete($id)
    {
        try {
            $form = CustomerDeposit::findOrFail($id);
            $form->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
