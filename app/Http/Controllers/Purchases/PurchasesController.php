<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\DataTables\Purchases\PurchasesDataTable;
use App\Models\Adjustment\StockMove;
use App\Models\Other\Branch;
use App\Models\People\Suppliers;
use App\Models\Warehouses\Warehouses as Warehouse;
use App\Models\Product\Product;
use App\Models\Setting\Unit;
use App\Models\Purchases\PurchaseItem;
use App\Models\Purchases\Purchases;
use App\Models\Product\ProductUnit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PurchasesController extends Controller
{
    public function index(PurchasesDataTable $dataTable)
    {
        return $dataTable->render('purchases.index');
    }

    public function add()
    {
        $title = __('global.add_new');
        $form = new Purchases();
        $branches = Branch::select('id', 'name')->orderBy('name')->get();
        $warehouses = Warehouse::select('id', 'name')->orderBy('name')->get();
        $suppliers = Suppliers::select('id', 'name')->orderBy('name')->get();
        $products = Product::select('id', 'code', 'name')->orderBy('name')->get();
        $units = Unit::select('id', 'name', 'code')->orderBy('name')->get();

        return view('purchases.form', compact('title', 'form', 'branches', 'warehouses', 'suppliers', 'products', 'units'));
    }

    public function edit($id)
    {
        $title = __('global.edit');
        $form = Purchases::with('items')->findOrFail($id);
        $branches = Branch::select('id', 'name')->orderBy('name')->get();
        $warehouses = Warehouse::select('id', 'name')->orderBy('name')->get();
        $suppliers = Suppliers::select('id', 'name')->orderBy('name')->get();
        $products = Product::select('id', 'code', 'name')->orderBy('name')->get();
        $units = Unit::select('id', 'name', 'code')->orderBy('name')->get();

        return view('purchases.form', compact('title', 'form', 'branches', 'warehouses', 'suppliers', 'products', 'units'));
    }

    public function nextReference(Carbon $whenLocal): string
    {
        $prefix = 'PU/' . $whenLocal->format('Y/m') . '/';
        $lastRef = Purchases::where('reference_no', 'like', $prefix . '%')
            ->orderByDesc('reference_no')
            ->value('reference_no');

        $next = 1;
        if ($lastRef && preg_match('/(\d+)$/', $lastRef, $m)) {
            $next = (int) $m[1] + 1;
        }
        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Return products for quick-add and select lists
     */
    public function ajaxProducts(Request $request)
    {
        $products = Product::select('id', 'code', 'name')->orderBy('name')->get();
        return response()->json($products);
    }

    /**
     * Return product units (conversion info) for a product_id
     */
    public function ajaxProductUnits(Request $request)
    {
        $productId = (int) $request->query('product_id', 0);
        if (!$productId) {
            return response()->json([], 200);
        }

        // Get the product (with its base unit)
        $product = Product::with('unit:id,name,code')->find($productId);
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }

        // Get conversion units (ProductUnit)
        $rows = ProductUnit::where('product_id', $productId)
            ->with(['unit:id,name,code'])
            ->orderByDesc('qty')
            ->get();

        $data = collect();

     
        if ($product->unit) {
            $data->push([
                'id' => null,
                'unit_id' => $product->unit_id,
                'unit_name' => $product->unit->name,
                'unit_code' => $product->unit->code,
                'qty' => 1, // base unit always 1
                'price' => $product->cost ? (float) $product->cost : null,
                'is_base' => true,
            ]);
        }

        // 2️⃣ Add conversion units from product_units table
        foreach ($rows as $r) {
            $data->push([
                'id' => $r->id,
                'unit_id' => $r->unit_id,
                'unit_name' => optional($r->unit)->name,
                'unit_code' => optional($r->unit)->code,
                'qty' => (float) $r->qty,
                'price' => $r->price ? (float) $r->price : null,
                'is_base' => false,
            ]);
        }

        return response()->json($data->values());
    }


    /**
     * Save purchase (create/update)
     */





    public function save(Request $request, $id = null)
    {
        DB::beginTransaction();
        try {
            $rowsIn = $request->input('items', $request->input('products', []));
            if (empty($rowsIn) || !is_array($rowsIn)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please add at least one product.',
                ], 422);
            }


            $rowsIn = collect($rowsIn)->map(function ($r) {
                $r['product_id'] = (int) ($r['product_id'] ?? 0);
                $r['net_unit_cost'] = (float) ($r['net_unit_cost'] ?? 0);
                $r['quantity'] = (float) ($r['quantity'] ?? 0);
                $r['discount'] = (float) ($r['discount'] ?? 0);
                return $r;
            })->all();

            $request->validate([
                'date' => ['required', 'date'],
                'reference_no' => ['nullable', 'string', 'max:191'],
                'si_reference_no' => ['nullable', 'string', 'max:191'],
                'branch_id' => ['required', 'integer', 'exists:branches,id'],
                'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
                'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
                'order_tax' => ['nullable', 'numeric', 'min:0'],
                'order_discount' => ['nullable', 'numeric', 'min:0'],
                'payment_term' => ['nullable', 'string', 'max:191'],
                'document' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],

                'items' => ['required', 'array', 'min:1'],
                'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
                'items.*.net_unit_cost' => ['required', 'numeric', 'min:0'],
                'items.*.quantity' => ['required', 'numeric', 'min:0.0001'],
                'items.*.discount' => ['nullable', 'numeric', 'min:0'],
                'items.*.unit_id' => ['nullable', 'integer', 'exists:units,id'],
            ]);


            $whenLocal = $request->filled('date')
                ? Carbon::parse($request->input('date'), 'Asia/Phnom_Penh')
                : now('Asia/Phnom_Penh');
            $whenUtc = $whenLocal->copy()->utc();


            $referenceNo = $request->filled('reference_no')
                ? $request->reference_no
                : $this->nextReference($whenLocal);


            $attachmentPath = null;
            if ($request->hasFile('document')) {
                $attachmentPath = $request->file('document')->store('purchases', 'public');
            }


            $total = 0;
            foreach ($rowsIn as $r) {
                $line = ($r['net_unit_cost'] * $r['quantity']) - ($r['discount'] ?? 0);
                $total += $line;
            }
            $orderDiscount = (float) $request->input('order_discount', 0);
            $orderTax = (float) $request->input('order_tax', 0);
            $grandTotal = $total - $orderDiscount + $orderTax;


            $purchase = Purchases::updateOrCreate(
                ['id' => $id],
                [
                    'date' => $whenUtc,
                    'reference_no' => $referenceNo,
                    'si_reference_no' => $request->input('si_reference_no'),
                    'branch_id' => $request->branch_id,
                    'warehouse_id' => $request->warehouse_id,
                    'supplier_id' => $request->supplier_id,
                    'order_tax' => $orderTax,
                    'order_discount' => $orderDiscount,
                    'total' => $total,
                    'grand_total' => $grandTotal,
                    'payment_term' => $request->input('payment_term'),
                    'attachment' => $attachmentPath ?? ($id ? (Purchases::find($id)?->attachment) : null),
                    'note' => $request->input('note'),
                    'created_by' => auth()->id(),
                ]
            );


            $keptIds = [];
            foreach ($rowsIn as $r) {
                $data = [
                    'unit_id' => $r['unit_id'],
                    'product_id' => $r['product_id'],
                    'net_unit_cost' => $r['net_unit_cost'],
                    'quantity' => $r['quantity'],
                    'discount' => $r['discount'] ?? 0,
                    'subtotal' => ($r['net_unit_cost'] * $r['quantity']) - ($r['discount'] ?? 0),
                ];

                $itemId = !empty($r['id']) ? (int) $r['id'] : null;

                if ($itemId) {
                    PurchaseItem::where('id', $itemId)
                        ->where('purchase_id', $purchase->id)
                        ->update($data);
                    $keptIds[] = $itemId;
                } else {
                    $new = PurchaseItem::create($data + ['purchase_id' => $purchase->id]);
                    $keptIds[] = $new->id;
                }
            }

            $q = PurchaseItem::where('purchase_id', $purchase->id);
            if (!empty($keptIds))
                $q->whereNotIn('id', $keptIds);
            $q->delete();


            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $id ? __('messages.user_updated') : __('messages.user_saved'),
                'redirect' => route('purchases.index'),
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
            $form = Purchases::findOrFail($id);
            $form->delete();

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

    /**
     * Approve purchase: create stock moves (base qty) and update product quantity (base)
     */
    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $purchase = Purchases::with('items')->findOrFail($id);

            $exists = StockMove::where('transaction', 'purchase')
                ->where('transaction_id', $purchase->id)
                ->exists();
            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This purchase is already approved (stock moves exist).',
                ], 422);
            }
            if ($purchase->items->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No items to approve.',
                ], 422);
            }

            $moveDate = $purchase->date;
            $approver = Auth::id();

            $productIds = $purchase->items->pluck('product_id')->unique()->all();
            $products = Product::whereIn('id', $productIds)
                ->get(['id', 'code', 'type', 'quantity'])->keyBy('id');

            // preload product-unit qty mapping to avoid repeated queries:
            // map key = productId_unitId -> qty
            $unitQtyMap = [];
            $neededPairs = [];
            foreach ($purchase->items as $it) {
                if (!empty($it->unit_id)) {
                    $key = $it->product_id . '_' . $it->unit_id;
                    $neededPairs[$key] = ['product_id' => $it->product_id, 'unit_id' => $it->unit_id];
                }
            }
            if (!empty($neededPairs)) {
                foreach ($neededPairs as $key => $pair) {
                    $qty = ProductUnit::where('product_id', $pair['product_id'])
                        ->where('unit_id', $pair['unit_id'])
                        ->value('qty');
                    $unitQtyMap[$key] = $qty ? (float) $qty : 1.0;
                }
            }

            $rows = [];
            $productQtyDelta = []; // product_id => total base qty to add to product quantity

            foreach ($purchase->items as $it) {
                // original entered quantity
                $enteredQty = (float) $it->quantity;

                // determine conversion factor (units per base unit or base-per-unit depending on your design)
                $conversion = 1.0;
                if (!empty($it->unit_id)) {
                    $key = $it->product_id . '_' . $it->unit_id;
                    $conversion = $unitQtyMap[$key] ?? 1.0;
                }

                // baseQty = enteredQty * conversion (so 1 case * 24 = 24 base pcs)
                $baseQty = $enteredQty * $conversion;

                $prod = $products->get($it->product_id);

                $rows[] = [
                    'transaction' => 'purchase',
                    'transaction_id' => (int) $purchase->id,
                    // keep `quantity` as the entered quantity (e.g. 1 case)
                    'quantity' => $enteredQty,
                    // store converted/base quantity in unit_quantity (e.g. 24 pcs)
                    'unit_quantity' => (float) $baseQty,
                    'product_id' => (int) $it->product_id,
                    'product_type' => $prod->type ?? null,
                    'product_code' => $prod->code ?? null,
                    'date' => $moveDate,
                    'unit_code' => optional(Unit::find($it->unit_id))->code ?? null,
                    'unit_id' => $it->unit_id ?? null,
                    'option_id' => $it->option_id ?? 0,
                    'warehouse_id' => (int) $purchase->warehouse_id,
                    'expiry' => $it->expiry ?? null,
                    'real_unit_cost' => (float) $it->net_unit_cost,
                    'serial_no' => $it->serial_no ?? null,
                    'reference_no' => $purchase->reference_no,
                    'user_id' => $approver,
                    'created_at' => now()->utc(),
                    'updated_at' => now()->utc(),
                ];

                // accumulate product delta in base units
                $productQtyDelta[$it->product_id] = ($productQtyDelta[$it->product_id] ?? 0) + $baseQty;
            }

            // insert stock moves
            StockMove::insert($rows);

            // update product quantities (in base unit)
            foreach ($productQtyDelta as $pid => $delta) {
                $product = Product::find($pid);
                if ($product && isset($product->quantity)) {
                    $product->quantity = (float) $product->quantity + (float) $delta;
                    $product->save();
                }
            }

            if (Schema::hasColumn('purchases', 'status')) {
                $purchase->status = 'approved';
            }
            if (Schema::hasColumn('purchases', 'approved_by')) {
                $purchase->approved_by = $approver;
            }
            if (Schema::hasColumn('purchases', 'approved_at')) {
                $purchase->approved_at = now()->utc();
            }
            $purchase->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Approved. Stock moves created.',
                'redirect' => route('purchases.index'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function modal($id)
    {
        $form = Purchases::findOrFail($id);
        $items = PurchaseItem::with(['product:id,name,code'])
            ->where('purchase_id', $id)
            ->orderBy('id')
            ->get();

        return view('purchases.modal_view', compact('form', 'items'));
    }
}
