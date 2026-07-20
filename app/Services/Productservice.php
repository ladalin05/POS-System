<?php

namespace App\Services;

use App\Models\Other\Branch;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\ProductUnit;
use App\Models\Setting\Unit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductService extends BaseService
{
    protected string $timezone = 'Asia/Phnom_Penh';

    /**
     * Primary model for the inherited BaseService methods
     * (create, update, getAll, getById, setStatus all resolve through this).
     */
    protected function getQuery()
    {
        return Product::query();
    }

    // -----------------------------------------------------------------
    // Form data
    // -----------------------------------------------------------------

    public function getFormOptions(): array
    {
        return [
            'categories' => Category::select('name', 'id')->get(),
            'units'      => Unit::select('name', 'id')->get(),
            'branch'     => Branch::select('name', 'id')->get(),
        ];
    }

    // -----------------------------------------------------------------
    // List (inherited BaseService::getAll — pagination/search/filter/sort for free)
    // -----------------------------------------------------------------

    public function list(array $params = []): LengthAwarePaginator
    {
        return $this->getAll($params);
    }

    // -----------------------------------------------------------------
    // Save (create/update)
    // -----------------------------------------------------------------

    /**
     * @param array $validated Validated product payload.
     * @throws Throwable on any failure inside the transaction.
     */
    public function save(array $validated, Request $request, ?int $id = null): Product
    {
        if (($validated['product_type'] ?? null) === 'Service') {
            $validated['unit_id']         = null;
            $validated['sale_unit']       = null;
            $validated['purchase_unit']   = null;
            $validated['alert_quantity']  = null;
            $validated['product_units']   = [];
        }

        [$startAt, $endAt] = $this->resolvePromotionDates($validated);

        $now = now($this->timezone);
        if (!empty($validated['promotion']) && $endAt && $now->gt($endAt)) {
            $validated['promotion'] = false;
        }

        return DB::transaction(function () use ($validated, $request, $id, $startAt, $endAt) {
            $data = [
                'type'         => $validated['product_type'],
                'name'         => $validated['name'],
                'code'         => $validated['product_code'],
                'category_id'  => $validated['category_id'],
                'price'        => $validated['price'],
                'cost'         => $validated['cost'] ?? null,

                'unit_id'         => $validated['unit_id'] ?? null,
                'sale_unit'       => $validated['sale_unit'] ?? null,
                'purchase_unit'   => $validated['purchase_unit'] ?? null,
                'alert_quantity'  => $validated['alert_quantity'] ?? null,

                'brand'           => $validated['brand'] ?? null,
                'product_details' => $validated['product_details'] ?? null,

                'promotion'   => (bool) ($validated['promotion'] ?? false),
                'promo_price' => $validated['promo_price'] ?? null,
                'promo_qty'   => $validated['promo_qty'] ?? null,
                'start_date'  => $startAt,
                'end_date'    => $endAt,
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $this->storeImage($request);
            }

            // Resolved through getQuery() rather than Product::updateOrCreate()
            // directly, so this service stays consistent with the
            // BaseService-driven methods (list/find/delete) below.
            $product = $this->getQuery()->updateOrCreate(['id' => $id], $data);

            $this->syncProductUnits($product, $validated);

            return $product;
        });
    }

    protected function resolvePromotionDates(array $validated): array
    {
        $startAt = null;
        $endAt   = null;

        if (!empty($validated['start_date'])) {
            $startAt = Carbon::createFromFormat('Y-m-d', $validated['start_date'], $this->timezone)->startOfDay();
        }

        if (!empty($validated['end_date'])) {
            $endAt = Carbon::createFromFormat('Y-m-d', $validated['end_date'], $this->timezone)->endOfDay();
        }

        return [$startAt, $endAt];
    }

    protected function storeImage(Request $request): string
    {
        $image    = $request->file('image');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
        $image->move(public_path('uploads/products'), $filename);

        return 'uploads/products/' . $filename;
    }

    protected function syncProductUnits(Product $product, array $validated): void
    {
        ProductUnit::where('product_id', $product->id)->delete();

        if (($validated['product_type'] ?? null) !== 'Standard') {
            return;
        }

        foreach (($validated['product_units'] ?? []) as $row) {
            if (empty($row['unit_id'])) {
                continue;
            }

            ProductUnit::create([
                'product_id' => $product->id,
                'unit_id'    => (int) $row['unit_id'],
                'qty'        => (float) ($row['qty'] ?? 0),
                'price'      => isset($row['price']) ? (float) $row['price'] : null,
            ]);
        }
    }

    // -----------------------------------------------------------------
    // Delete
    // -----------------------------------------------------------------

    /**
     * @throws \Exception if attempting to delete the protected default product.
     */
    public function delete(int $id): void
    {
        if ($id === 1) {
            throw new \Exception(__('messages.user_cannot_delete'));
        }

        $product = $this->getQuery()->findOrFail($id);
        $product->delete();
    }

    // -----------------------------------------------------------------
    // View
    // -----------------------------------------------------------------

    public function find(int $id): Product
    {
        return $this->getQuery()->with(['category', 'unit'])->findOrFail($id);
    }
}