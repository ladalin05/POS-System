<?php

namespace App\Services;

use App\Models\Stocks\Adjustment;
use App\Models\Stocks\AdjustmentItem;
use App\Models\Stocks\StockMove;
use App\Models\Other\Branch;
use App\Models\Product\Product;
use App\Models\Setting\Unit;
use App\Models\Warehouses\Warehouses;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdjustmentService
{
    public function find(int $id): Adjustment
    {
        return Adjustment::findOrFail($id);
    }

    public function getFormOptions(?int $adjustmentId = null): array
    {
        return [
            'branches'    => Branch::select('id', 'name')->orderBy('name')->get(),
            'warehouses'  => Warehouses::select('id', 'name')->orderBy('name')->get(),
            'products'    => Product::select('id', 'sku', 'product_name')->orderBy('product_name')->get(),
            'units'       => Unit::select('id', 'name', 'code')->orderBy('name')->get(),
            'stockmoves'  => $adjustmentId
                ? StockMove::select('id', 'product_id', 'quantity')
                    ->where('transaction', 'adjustment')
                    ->where('transaction_id', $adjustmentId)
                    ->get()
                : collect(),
        ];
    }

    /**
     * Create or update an Adjustment header plus sync its line items.
     * $id === null => create, otherwise update the existing record.
     */
    public function save(array $data, FormRequest $formRequest, ?int $id = null): Adjustment
    {
        $rawRows = $this->extractRows($formRequest);

        DB::beginTransaction();

        try {
            $existing = $id ? Adjustment::findOrFail($id) : null;

            $whenLocal = !empty($data['date'])
                ? Carbon::parse($data['date'], 'Asia/Phnom_Penh')
                : now('Asia/Phnom_Penh');
            $whenUtc = $whenLocal->copy()->utc();

            $referenceNo = $data['reference_no']
                ?? $existing?->reference_no
                ?? $this->nextReference($whenLocal);

            $attachmentPath = null;
            if ($formRequest->hasFile('document')) {
                $attachmentPath = $formRequest->file('document')->store('adjustments', 'public');
            }

            $header = [
                'reference_no' => $referenceNo,
                'warehouse_id' => $data['warehouse_id'],
                'branch_id'    => $data['branch_id'],
                'date'         => $whenUtc,
                'note'         => $data['note'] ?? null,
                'attachment'   => $attachmentPath ?? $existing?->attachment,
            ];

            if ($existing) {
                $existing->update($header);
                $adjustment = $existing;
            } else {
                $adjustment = Adjustment::create($header + ['created_by' => Auth::id()]);
            }

            $this->syncItems($adjustment, $rawRows, $data);

            DB::commit();

            return $adjustment;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        $adjustment = Adjustment::findOrFail($id);

        if ($adjustment->id === 1) {
            throw new \RuntimeException(__('messages.user_cannot_delete'));
        }

        $adjustment->delete();
    }

    public function approve(int $id): Adjustment
    {
        DB::beginTransaction();

        try {
            $adjustment = Adjustment::with(['items'])->findOrFail($id);

            $exists = StockMove::where('transaction', 'adjustment')
                ->where('transaction_id', $adjustment->id)
                ->exists();

            if ($exists) {
                throw new \RuntimeException('This adjustment has already been approved (stock moves exist).');
            }

            if ($adjustment->items->isEmpty()) {
                throw new \RuntimeException('No items to approve.');
            }

            $approverId = Auth::id();
            $moveDate = $adjustment->date;

            $productIds = $adjustment->items->pluck('product_id')->filter()->unique()->all();
            $products = Product::whereIn('id', $productIds)
                ->get(['id', 'code', 'type', 'quantity'])
                ->keyBy('id');

            $rows = [];
            $quantityDeltas = [];

            foreach ($adjustment->items as $it) {
                $qty = (float) ($it->quantity ?? 0) * (float) ($it->unit_quantity ?? 1);
                $qty = $it->type === 'subtraction' ? -abs($qty) : abs($qty);

                $prod = $products->get($it->product_id);

                $rows[] = [
                    'transaction'      => 'adjustment',
                    'transaction_id'   => (int) $adjustment->id,
                    'product_id'       => (int) $it->product_id,
                    'product_type'     => $prod->type ?? null,
                    'product_code'     => $prod->code ?? null,
                    'date'             => $moveDate,
                    'quantity'         => $qty,
                    'unit_quantity'    => (float) ($it->unit_quantity ?? 1),
                    'unit_code'        => $it->product_unit_code ?? null,
                    'unit_id'          => $it->product_unit_id ?? null,
                    'option_id'        => $it->option_id ?? 0,
                    'warehouse_id'     => (int) $adjustment->warehouse_id,
                    'expiry'           => $it->expiry,
                    'real_unit_cost'   => (float) ($it->real_unit_cost ?? 0),
                    'serial_no'        => $it->serial_no,
                    'reference_no'     => $adjustment->reference_no,
                    'user_id'          => $approverId,
                    'created_at'       => now()->utc(),
                    'updated_at'       => now()->utc(),
                ];

                $quantityDeltas[$it->product_id] = ($quantityDeltas[$it->product_id] ?? 0) + $qty;
            }

            StockMove::insert($rows);

            foreach ($quantityDeltas as $productId => $delta) {
                $product = $products->get($productId) ?? Product::find($productId);
                if ($product) {
                    $product->quantity += $delta;
                    $product->save();
                }
            }

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

            return $adjustment;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
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

    protected function extractRows(FormRequest $formRequest): array
    {
        $rawRows = $formRequest->input('items');
        if (empty($rawRows)) {
            $rawRows = $formRequest->input('products');
        }

        if (empty($rawRows) || !is_array($rawRows)) {
            throw new \InvalidArgumentException('Please add at least one product.');
        }

        return $this->normalizeRows($rawRows);
    }

    protected function normalizeRows(array $rows): array
    {
        return collect($rows)->map(function ($row) {
            if (array_key_exists('product_unit_id', $row)) {
                $v = trim((string) ($row['product_unit_id'] ?? ''));
                $row['product_unit_id'] = ctype_digit($v) ? (int) $v : null;
            }
            return $row;
        })->all();
    }

    protected function syncItems(Adjustment $adjustment, array $rawRows, array $data): void
    {
        $keptIds = [];

        foreach ($rawRows as $row) {
            if (empty($row['product_id'])) {
                continue;
            }

            $type = $row['type'] ?? 'addition';
            if ($type === 'add') $type = 'addition';
            if ($type === 'subtract') $type = 'subtraction';

            $itemData = [
                'product_id'        => (int) $row['product_id'],
                'option_id'         => $row['option_id'] ?? null,
                'qoh'               => (float) ($row['qoh'] ?? 0),
                'new_qoh'           => (float) ($row['new_qoh'] ?? 0),
                'quantity'          => (float) ($row['quantity'] ?? 0),
                'unit_quantity'     => (float) ($row['unit_quantity'] ?? 1),
                'product_unit_id'   => (int) ($row['product_unit_id'] ?? 0),
                'product_unit_code' => (string) ($row['product_unit_code'] ?? '0'),
                'type'              => $type,
                'branch_id'         => $data['branch_id'],
                'warehouse_id'      => $data['warehouse_id'],
                'expiry'            => $row['expiry'] ?? null,
                'serial_no'         => $row['serial_no'] ?? null,
                'real_unit_cost'    => (float) ($row['real_unit_cost'] ?? 0),
            ];

            $itemId = !empty($row['id']) ? (int) $row['id'] : null;

            if ($itemId) {
                AdjustmentItem::where('id', $itemId)
                    ->where('adjustment_id', $adjustment->id)
                    ->update($itemData);

                $keptIds[] = $itemId;
            } else {
                $new = AdjustmentItem::create($itemData + ['adjustment_id' => $adjustment->id]);
                $keptIds[] = $new->id;
            }
        }

        $deleteQuery = AdjustmentItem::where('adjustment_id', $adjustment->id);
        if (!empty($keptIds)) {
            $deleteQuery->whereNotIn('id', $keptIds);
        }
        $deleteQuery->delete();
    }
}