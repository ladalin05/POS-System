<?php

namespace App\Http\Controllers\Adjustment;

use App\DataTables\Adjustment\AdjustmentDataTable;
use App\Models\Adjustment\AdjustmentItem;
use App\Http\Controllers\Controller;
use App\Models\Adjustment\Adjustment;
use App\Models\Adjustment\StockMove;
use App\Models\Other\Branch;
use App\Models\People\Customer;
use App\Models\Product\Product;
use App\Models\Setting\Unit;
use App\Models\Warehouses\Warehouses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdjustmentController extends Controller
{
    public function index(AdjustmentDataTable $dataTable)
    {


        return $dataTable->render('adjustment.index');
    }



    public function ajaxProducts(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $limit = (int) $request->input('limit', 100);

        $products = Product::select('id', 'name', 'code', 'quantity')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('code', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->limit($limit)
            ->get();

        return response()->json($products);
    }



    public function ajaxProductUnits(Request $request)
    {
        $productId = $request->integer('product_id');
        if (!$productId) {
            return response()->json([], 200);
        }

        $product = Product::with([
            'unit:id,name,code',
            'productUnits.unit:id,name,code',
        ])->findOrFail($productId);

        $out = [];

        // Base unit first (factor = 1)
        if ($product->unit) {
            $out[] = [
                'id' => (int) $product->unit->id,
                'name' => $product->unit->name,
                'code' => $product->unit->code,
                'qty' => 1.0,
                'is_base' => true,
            ];
        }

  
        foreach ($product->productUnits as $pu) {
            if (!$pu->unit)
                continue;
            $out[] = [
                'id' => (int) $pu->unit->id,
                'name' => $pu->unit->name,
                'code' => $pu->unit->code,
                'qty' => (float) ($pu->qty ?? 1),
                'is_base' => false,
            ];
        }

        return response()->json($out);
    }

    public function ajaxUnits(Request $request)
    {
        $units = Unit::select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        return response()->json($units);
    }

    public function ajaxQoh(Request $request)
    {
        $warehouseId = $request->integer('warehouse_id');
        $productIds = (array) $request->input('product_ids', []);

        $q = StockMove::select('product_id', DB::raw('SUM(quantity) as qoh'))
            ->when(!empty($productIds), fn($qq) => $qq->whereIn('product_id', $productIds))
            ->when($warehouseId, fn($qq) => $qq->where('warehouse_id', $warehouseId))
            ->groupBy('product_id');

        return response()->json($q->pluck('qoh', 'product_id'));
    }

    public function add($id = null)
    {
        $title = __('add_adjustment');
        $form = new Adjustment();
        $branches = Branch::select('id', 'name')->orderBy('name')->get();
        $warehouses = Warehouses::select('id', 'name')->orderBy('name')->get();
        $products = Product::select('id', 'code', 'name')->orderBy('name')->get();
        $units = Unit::select('id', 'name', 'code')->orderBy('name')->get();
        $stockmoves = collect();

        return view('adjustment.form', compact(
            'title',
            'branches',
            'warehouses',
            'products',
            'form',
            'units',
            'stockmoves'
        ));
    }

    public function edit($id)
    {
        $title = __('global.edit');
        $form = Adjustment::findOrFail($id);
        $branches = Branch::select('id', 'name')->orderBy('name')->get();
        $warehouses = Warehouses::select('id', 'name')->orderBy('name')->get();
        $products = Product::select('id', 'code', 'name')->orderBy('name')->get();
        $units = Unit::select('id', 'name', 'code')->orderBy('name')->get();

        $stockmoves = StockMove::select('id', 'product_id', 'quantity')
            ->where('transaction', 'adjustment')
            ->where('transaction_id', $form->id)
            ->get();

        return view('adjustment.form', compact(
            'title',
            'branches',
            'warehouses',
            'products',
            'form',
            'units',
            'stockmoves'
        ));
    }


    public function nextReference(Carbon $whenLocal): string
    {

        $prefix = 'ML/' . $whenLocal->format('Y/m') . '/';
        $lastRef = Adjustment::where('reference_no', 'like', $prefix . '%')
            ->orderByDesc('reference_no')
            ->value('reference_no');
        $next = 1;
        if ($lastRef && preg_match('/(\d+)$/', $lastRef, $m)) {
            $next = (int) $m[1] + 1;
        }
        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);

    }




    public function save(Request $request, $id = null)
    {
        DB::beginTransaction();

        try {
            // Accept either 'items' or 'products'
            $rawRows = $request->input('items');
            $productsIn = $request->input('products', []);
            $itemsIn = $request->input('items', []);
            if (empty($rawRows))
                $rawRows = $request->input('products');

            if (empty($rawRows) || !is_array($rawRows)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please add at least one product.',
                ], 422);
            }
            $normalize = function ($rows) {
                return collect($rows)->map(function ($row) {
                    if (array_key_exists('product_unit_id', $row)) {
                        $v = trim((string) ($row['product_unit_id'] ?? ''));
                        $row['product_unit_id'] = ctype_digit($v) ? (int) $v : null;
                    }
                    return $row;
                })->all();
            };
            if (!empty($productsIn)) {
                $request->merge(['products' => $normalize($productsIn)]);
            }
            if (!empty($itemsIn)) {
                $request->merge(['items' => $normalize($itemsIn)]);
            }

            $request->validate([
                'reference_no' => 'nullable|string',
                'warehouse_id' => 'required|integer',
                'branch_id' => 'required|integer',
                'date' => 'required|date',
                'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
                'products.*.product_unit_id' => 'nullable|integer|exists:units,id',
                'products.*.product_unit_code' => 'nullable|string|max:50',
                'items.*.product_unit_id' => 'nullable|integer|exists:units,id',
                'items.*.product_unit_code' => 'nullable|string|max:50',
            ]);

            // Local time -> UTC for storage
            $whenLocal = $request->filled('date')
                ? Carbon::parse($request->input('date'), 'Asia/Phnom_Penh')
                : now('Asia/Phnom_Penh');

            $whenUtc = $whenLocal->copy()->utc();

            // Reference number
            if ($id) {
                $existing = Adjustment::find($id);
                $referenceNo = $request->filled('reference_no')
                    ? $request->reference_no
                    : ($existing?->reference_no ?? $this->nextReference($whenLocal));
            } else {
                $referenceNo = $request->filled('reference_no')
                    ? $request->reference_no
                    : $this->nextReference($whenLocal);
            }

            // Attachment
            $attachmentPath = null;
            if ($request->hasFile('document')) {
                $attachmentPath = $request->file('document')->store('adjustments', 'public');
            }

            // Save/update header
            $adjustment = Adjustment::updateOrCreate(
                ['id' => $id],
                [

                    'reference_no' => $referenceNo,
                    'warehouse_id' => $request->warehouse_id,
                    'branch_id' => $request->branch_id,
                    'date' => $whenUtc,
                    'note' => $request->note,
                    'attachment' => $attachmentPath ?? ($id ? Adjustment::find($id)?->attachment : null),
                    'created_by' => auth()->id(),
                ]
            );

            // === Sync items (update existing by id, create new, delete removed) ===
            $keptIds = [];

            foreach ($rawRows as $row) {
                if (empty($row['product_id']))
                    continue;

                // normalize type
                $type = $row['type'] ?? 'addition';
                if ($type === 'add')
                    $type = 'addition';
                if ($type === 'subtract')
                    $type = 'subtraction';

                $data = [
                    'product_id' => (int) ($row['product_id']),
                    'option_id' => $row['option_id'] ?? null,
                    'qoh' => (float) ($row['qoh'] ?? 0),
                    'new_qoh' => (float) ($row['new_qoh'] ?? 0),
                    'quantity' => (float) ($row['quantity'] ?? 0),
                    'unit_quantity' => (float) ($row['unit_quantity'] ?? 1),
                    'product_unit_id' => (int) ($row['product_unit_id'] ?? 0),
                    'product_unit_code' => (string) ($row['product_unit_code'] ?? '0'),
                    'type' => $type,
                    'branch_id' => $request->branch_id,
                    'warehouse_id' => $request->warehouse_id,
                    'expiry' => $row['expiry'] ?? null,
                    'serial_no' => $row['serial_no'] ?? null,
                    'real_unit_cost' => (float) ($row['real_unit_cost'] ?? 0),
                ];

                $itemId = !empty($row['id']) ? (int) $row['id'] : null;

                if ($itemId) {
                    // update existing row (scoped to this adjustment, avoids tampering)
                    AdjustmentItem::where('id', $itemId)
                        ->where('adjustment_id', $adjustment->id)
                        ->update($data);

                    $keptIds[] = $itemId;
                } else {
                    // create new row
                    $new = AdjustmentItem::create($data + ['adjustment_id' => $adjustment->id]);
                    $keptIds[] = $new->id;
                }
            }


            $q = AdjustmentItem::where('adjustment_id', $adjustment->id);
            if (!empty($keptIds)) {
                $q->whereNotIn('id', $keptIds);
            }

            $q->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $id ? __('messages.user_updated') : __('messages.user_saved'),
                'redirect' => route('adjustment.index'),
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
            if ($id == 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('messages.user_cannot_delete'),
                ]);
            }

            $form = Adjustment::findOrFail($id);
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

    public function approve($id)
    {
        DB::beginTransaction();

        try {
            $adjustment = Adjustment::with(['items'])->findOrFail($id);
            $exists = StockMove::where('transaction', 'adjustment')
                ->where('transaction_id', $adjustment->id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This adjustment has already been approved (stock moves exist).',
                ], 422);
            }

            if ($adjustment->items->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No items to approve.',
                ], 422);
            }

            $approverId = Auth::id();
            $moveDate = $adjustment->date;

            $rows = [];


            $productIds = $adjustment->items->pluck('product_id')->filter()->unique()->all();
            $products = Product::whereIn('id', $productIds)
                ->get(['id', 'code', 'type'])
                ->keyBy('id');

            foreach ($adjustment->items as $it) {
                $qty = (float) ($it->quantity ?? 0) * (float) ($it->unit_quantity ?? 1);

                if ($it->type === 'subtraction') {
                    $qty = -abs($qty);
                } else {
                    $qty = abs($qty);
                }

                $prod = $products->get($it->product_id);

                $rows[] = [
                    'transaction' => 'adjustment',
                    'transaction_id' => (int) $adjustment->id,
                    'product_id' => (int) $it->product_id,
                    'product_type' => $prod->type ?? null,
                    'product_code' => $prod->code ?? null,
                    'date' => $moveDate,
                    'quantity' => $qty,
                    'unit_quantity' => (float) ($it->unit_quantity ?? 1),
                    'unit_code' => $it->product_unit_code ?? null,
                    'unit_id' => $it->product_unit_id ?? null,

                    'option_id' => $it->option_id ?? 0,
                    'warehouse_id' => (int) $adjustment->warehouse_id,
                    'expiry' => $it->expiry,
                    'real_unit_cost' => (float) ($it->real_unit_cost ?? 0),
                    'serial_no' => $it->serial_no,
                    'reference_no' => $adjustment->reference_no,

                    'user_id' => $approverId,

                    'created_at' => now()->utc(),
                    'updated_at' => now()->utc(),
                ];
            }


            $stockmove = StockMove::insert($rows);

            if ($stockmove) {
                $product = Product::find($it->product_id);
                $product->quantity += $qty;
                $product->save();
            }

            // Optional: mark the Adjustment as approved if you track status
            if (Schema::hasColumn('adjustments', 'status')) {
                $adjustment->status = 'approved';
            }
            if (Schema::hasColumn('adjustments', 'approved_by')) {
                $adjustment->approved_by = $approverId;
            }
            if (Schema::hasColumn('adjustments', 'approved_at')) {
                $adjustment->approved_at = now()->utc();
            }
            $adjustment->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Approved. Stock moves created.',
                'redirect' => route('adjustment.index'),
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
        $form = Adjustment::findOrFail($id);

        $items = AdjustmentItem::with(['product:id,name,code'])
            ->where('adjustment_id', $id)
            ->orderBy('id')
            ->get();

        // NOTE: remove the undefined $branch variable from compact
        return view('adjustment.modal_view', compact('form', 'items'));
    }


}
