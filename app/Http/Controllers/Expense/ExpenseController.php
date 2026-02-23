<?php

namespace App\Http\Controllers\Expense;

use App\DataTables\Expense\ExpenseDataTable;
use App\Http\Controllers\Controller;
use App\Models\Expense\Expense;
use App\Models\Expense\ExpenseCategory;
use App\Models\Expense\ExpenseItem;
use App\Models\Other\Branch;
use App\Models\Other\CashAccount;
use App\Models\Warehouses\Warehouses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(ExpenseDataTable $dataTable)
    {
        return $dataTable->render('expense.add_expense.index');
    }

    public function expense_categories()
    {
        return response()->json(
            ExpenseCategory::select('id', 'name', 'code', 'description')
                ->orderBy('name')
                ->get()
        );
    }

    public function add()
    {
        $title = __('global.add_new');
        $form = new Expense();

        $branches = Branch::select('id', 'name')->orderBy('name')->get();
        $warehouses = Warehouses::select('id', 'name')->orderBy('name')->get();
        $cash = CashAccount::select('id', 'name')->orderBy('name')->get();

        return view('expense.add_expense.form', compact('title', 'form', 'branches', 'warehouses', 'cash'));
    }

    public function edit($id)
    {
        $title = __('global.edit');
        $form = Expense::with('items')->findOrFail($id);

        $branches = Branch::select('id', 'name')->orderBy('name')->get();
        $warehouses = Warehouses::select('id', 'name')->orderBy('name')->get();
        $cash = CashAccount::select('id', 'name')->orderBy('name')->get();

        return view('expense.add_expense.form', compact('title', 'form', 'branches', 'warehouses', 'cash'));
    }


    public function nextReference(Carbon $whenLocal): string
    {
        $prefix = 'EXP/' . $whenLocal->format('Y/m') . '/';

        $last = Expense::where('reference_no', 'like', $prefix . '%')
            ->orderByDesc('reference_no')
            ->value('reference_no');

        $next = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $next = ((int) $m[1]) + 1;
        }

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }





    public function save(Request $request, $id = null)
    {
        DB::beginTransaction();

        try {
            $rowsIn = $request->input('items', $request->input('products', []));
            if (empty($rowsIn) || !is_array($rowsIn)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please add at least one expense item.',
                ], 422);
            }


            $request->validate([
                'date' => ['required', 'date'],
                'reference_no' => ['nullable', 'string', 'max:255'],
                'branch_id' => ['required', 'integer', 'exists:branches,id'],
                'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
                'paid_by' => ['required', 'integer', 'exists:cash_accounts,id'],
                'document' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],

                'items' => ['required', 'array', 'min:1'],
                'items.*.id' => ['nullable', 'integer'],
                'items.*.expense_category_id' => ['nullable', 'integer', 'exists:expense_categories,id'],
                'items.*.expense_name' => ['nullable', 'string', 'max:255'],
                'items.*.expense_code' => ['nullable', 'string', 'max:50'],
                'items.*.description' => ['nullable', 'string'],
                'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
                'items.*.quantity' => ['required', 'numeric', 'min:0.001'],
            ]);

          
            foreach ($rowsIn as $i => $row) {
                $hasName = isset($row['expense_name']) && trim((string) $row['expense_name']) !== '';
                $hasCat = !empty($row['expense_category_id']);
                if (!$hasName && !$hasCat) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Row #" . ($i + 1) . ": please select a category or enter a name."
                    ], 422);
                }
            }

  
            $whenLocal = $request->filled('date')
                ? Carbon::parse($request->input('date'), 'Asia/Phnom_Penh')
                : now('Asia/Phnom_Penh');
            $whenUtc = $whenLocal->copy()->utc();

           
            $existing = $id ? Expense::findOrFail($id) : null;

         
            $referenceNo = $request->filled('reference_no')
                ? $request->reference_no
                : ($existing?->reference_no ?? $this->nextReference($whenLocal));

        
            $attachmentPath = $existing?->attachment;
            if ($request->hasFile('document')) {
                $attachmentPath = $request->file('document')->store('expenses', 'public');
            }

  
            $expense = Expense::updateOrCreate(
                ['id' => $id],
                [
                    'date' => $whenUtc,
                    'reference_no' => $referenceNo,
                    'branch_id' => (int) $request->branch_id,
                    'warehouse_id' => (int) $request->warehouse_id,
                    'paid_by' => (int) $request->paid_by,
                    'note' => $request->note,
                    'attachment' => $attachmentPath,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]
            );


            $catIds = collect($rowsIn)->pluck('expense_category_id')->filter()->unique()->all();
            $catsById = $catIds
                ? ExpenseCategory::whereIn('id', $catIds)
                    ->get(['id', 'name', 'code', 'description'])
                    ->keyBy('id')
                : collect();

            $keptIds = [];
            $lineNo = 1;
            $grand = 0.0;

            foreach ($rowsIn as $row) {

                $itemId = !empty($row['id']) ? (int) $row['id'] : null;
                $catId = array_key_exists('expense_category_id', $row) && $row['expense_category_id'] !== ''
                    ? (int) $row['expense_category_id']
                    : null;


                if ($itemId && is_null($catId)) {
                    $existingItem = $expense->items()->whereKey($itemId)->first();
                    if ($existingItem) {
                        $catId = $existingItem->expense_category_id;
                    }
                }

                $name = trim((string) ($row['expense_name'] ?? ''));
                $code = trim((string) ($row['expense_code'] ?? ''));
                $desc = trim((string) ($row['description'] ?? ''));

                // Derive from category if missing
                if ($name === '' && $catId && $catsById->has($catId)) {
                    $cat = $catsById[$catId];
                    $name = $cat->name ?? $name;
                    $code = $code !== '' ? $code : ($cat->code ?? '');
                    $desc = $desc !== '' ? $desc : ($cat->description ?? ($cat->name ?? ''));
                }

                $unit_cost = (float) ($row['unit_cost'] ?? 0);
                $qty = (float) ($row['quantity'] ?? 0);

                if ($name === '' || $qty <= 0) {

                    continue;
                }

                $subtotal = round($unit_cost * $qty, 2);
                $grand += $subtotal;

                $data = [
                    'expense_category_id' => $catId,
                    'expense_name' => $name,
                    'expense_code' => $code,
                    'description' => $desc,
                    'unit_cost' => $unit_cost,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                    'line_no' => $lineNo++,
                ];

                if ($itemId) {
                    $expense->items()->whereKey($itemId)->update($data);
                    $keptIds[] = $itemId;
                } else {
                    $new = $expense->items()->create($data);
                    $keptIds[] = $new->id;
                }
            }





            $delQ = ExpenseItem::where('expense_id', $expense->id);
            if (!empty($keptIds)) {
                $delQ->whereNotIn('id', $keptIds);
            }
            $delQ->delete();

            // Update grand total
            $expense->grand_total = $grand;
            $expense->saveQuietly();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $id ? __('messages.user_updated') : __('messages.user_saved'),
                'redirect' => route('expense.add_expense.index'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $expense->delete();

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

    public function modal($id)
    {
        $form = Expense::with(['branch:id,name,logo', 'warehouse:id,name', 'cashAccount:id,name', 'items'])->findOrFail($id);
        $items = $form->items;
        return view('expense.add_expense.modal_view', compact('form', 'items'));
    }
}
