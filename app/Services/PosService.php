<?php

namespace App\Services;

use App\Models\Other\CashAccount;
use App\Models\Other\Warehouses;
use App\Models\People\Customer;
use App\Models\Product\Brand;
use App\Models\Product\Category;
use App\Models\Setting\Unit;
use App\Models\Product\Product;
use App\Models\Suspend\Suspend;
use App\Models\Suspend\SuspendItem;
use App\Models\Sales\SaleItems;
use App\Models\Sales\Sales;
use App\Models\Stocks\ProductStock;
use App\Models\Stocks\StockMove;
use App\Models\Sales\Payment;
use App\Models\Other\Biller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class PosService extends BaseService
{

    protected function getQuery()
    {
        return Sales::query();
    }

    // -----------------------------------------------------------------
    // Main screen
    // -----------------------------------------------------------------

    public function getIndexData(): array
    {

        $brands       = Brand::orderBy('name')->get();
        $warehouses   = Warehouses::orderBy('name')->get();
        $customers    = Customer::orderBy('name')->get();
        $billers      = Biller::orderBy('name')->get();
        $cashAccounts = CashAccount::orderBy('name')->get();
        $units        = Unit::orderBy('name')->get();
        $categories = Category::whereNull('parent_id')
                        ->with('children')
                        ->orderBy('name')
                        ->get();

        $products = $this->productQuery()->limit(60)->get();

        return compact(
            'categories', 'brands', 'warehouses', 'customers',
            'billers', 'cashAccounts', 'units', 'products'
        );
    }

    // -----------------------------------------------------------------
    // Catalog
    // -----------------------------------------------------------------

    public function fetchProducts(Request $request)
    {
        $warehouseId = $request->integer('warehouse_id') ?: 1;
        $query = $this->productQuery($warehouseId);

        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $categoryId = $request->category_id;
            $childIds = Category::where('parent_id', $categoryId)->pluck('id');
            $ids = $childIds->push($categoryId);

            $query->where(function ($q) use ($ids) {
                $q->whereIn('products.category_id', $ids)
                  ->orWhereIn('products.sub_category_id', $ids);
            });
        }

        if ($request->filled('brand_id')) {
            $query->where('products.brand_id', $request->brand_id);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('products.product_name', 'like', "%{$term}%")
                  ->orWhere('products.sku', 'like', "%{$term}%")
                  ->orWhere('products.barcode', 'like', "%{$term}%");
            });
        }

        return $query->limit(100)->get();
    }

    public function findByBarcode(Request $request, string $code)
    {
        $warehouseId = $request->integer('warehouse_id') ?: 1;

        return $this->productQuery($warehouseId)
            ->where(function ($q) use ($code) {
                $q->where('products.barcode', $code)
                  ->orWhere('products.sku', $code);
            })
            ->first();
    }

    public function productQuery(?int $warehouseId = null)
    {
        $warehouseId = $warehouseId ?: 1;

        return Product::query()
            ->select([
                'products.id',
                'products.product_name',
                'products.sku',
                'products.barcode',
                'products.image',
                'products.price',
                'products.tax_type',
                'products.tax_value',
                'products.category_id',
                'products.sub_category_id',
                'products.brand_id',
                'products.unit_id',
                DB::raw('COALESCE(product_stock.stock, 0) as stock'),
            ])
            ->leftJoin('product_stock', function ($join) use ($warehouseId) {
                $join->on('product_stock.product_id', '=', 'products.id')
                     ->where('product_stock.warehouse_id', '=', $warehouseId);
            })
            ->where('product_stock.stock', '>', 0)
            ->whereNull('products.deleted_at')
            ->orderBy('products.product_name');
    }

    // -----------------------------------------------------------------
    // Checkout
    // -----------------------------------------------------------------

    public function checkout(array $validated, Request $request)
    {
        $warehouseId = $validated['warehouse_id'] ?: 1;
        $discount    = $validated['discount'] ?? 0;

        return DB::transaction(function () use ($validated, $warehouseId, $discount, $request) {

            $subtotal = 0;
            $taxTotal = 0;
            $lines    = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                $stockRow = ProductStock::where('product_id', $item['product_id'])
                    ->where('warehouse_id', $warehouseId)
                    ->lockForUpdate()
                    ->first();

                $available = $stockRow->stock ?? 0;
                if ($available < $item['qty']) {
                    throw new Exception("Insufficient stock for {$product->product_name} (have {$available}, need {$item['qty']}).");
                }

                $lineSubtotal = round($item['qty'] * $item['unit_price'], 2);
                $taxRate      = $product->tax_value ?? 0.0;
                $lineTax      = $product->tax_type === 'Inclusive'
                    ? round($lineSubtotal - ($lineSubtotal / (1 + $taxRate / 100)), 2)
                    : round($lineSubtotal * ($taxRate / 100), 2);

                $subtotal += $lineSubtotal;
                $taxTotal += $lineTax;

                $lines[] = [
                    'product'    => $product,
                    'qty'        => $item['qty'],
                    'unit_id'    => $item['unit_id'] ?? $product->unit_id,
                    'unit_price' => $item['unit_price'],
                    'subtotal'   => $lineSubtotal,
                ];
            }

            $grandTotal = ($subtotal - $discount) + $taxTotal;
            $paid       = $validated['paid_amount'];
            $balance    = max($grandTotal - $paid, 0);

            $referenceNo = 'SAL-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);

            $sale = Sales::create([
                'date'            => now(),
                'reference_no'    => $referenceNo,
                'customer_id'     => $validated['customer_id'] ?? null,
                'biller_id'       => $validated['biller_id'] ?? null,
                'warehouse_id'    => $warehouseId,
                'total'           => $subtotal,
                'tax'             => $taxTotal,
                'returned'        => 0,
                'discount'        => $discount,
                'shipping'        => 0,
                'grand_total'     => $grandTotal,
                'paid'            => $paid,
                'balance'         => $balance,
                'return_amount'   => 0,
                'delivery_status' => 'delivered',
                'payment_status'  => $balance <= 0 ? 'paid' : ($paid > 0 ? 'partial' : 'pending'),
                'status'          => 'completed',
                'note'            => $request->input('note'),
                'created_by'      => Auth::user()->username ?? Auth::user()->name_en ?? 'system',
            ]);

            foreach ($lines as $line) {
                SaleItems::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $line['product']->id,
                    'unit_id'    => (string) $line['unit_id'],
                    'qty'        => $line['qty'],
                    'unit_price' => $line['unit_price'],
                    'subtotal'   => $line['subtotal'],
                    'name'       => $line['product']->product_name,
                    'code'       => $line['product']->sku,
                ]);

                // Decrement stock for this warehouse.
                ProductStock::where('product_id', $line['product']->id)
                    ->where('warehouse_id', $warehouseId)
                    ->decrement('stock', $line['qty']);

                $unit = Unit::find($line['unit_id']);

                StockMove::create([
                    'transaction'    => 'sale',
                    'transaction_id' => $sale->id,
                    'product_id'     => $line['product']->id,
                    'product_type'   => $line['product']->product_type,
                    'product_code'   => $line['product']->sku,
                    'date'           => now(),
                    'quantity'       => -1 * $line['qty'],
                    'unit_quantity'  => 1,
                    'unit_code'      => $unit->code ?? null,
                    'unit_id'        => $line['unit_id'],
                    'option_id'      => 0,
                    'warehouse_id'   => $warehouseId,
                    'real_unit_cost' => $line['unit_price'],
                    'reference_no'   => $sale->reference_no,
                    'user_id'        => Auth::id(),
                    'actual_date'    => now(),
                    'created_by'     => Auth::id(),
                ]);
            }

            Payment::create([
                'date'              => now(),
                'sale_id'           => $sale->id,
                'reference_no'      => $sale->reference_no,
                'amount'            => $paid,
                'discount'          => 0,
                'amount_usd'        => $paid,
                'rate_usd'          => 1,
                'amount_khr'        => $paid * 4000,
                'rate_khr'          => 4000,
                'paying_by'         => optional(CashAccount::find($request->input('cash_account_id')))->name ?? 'Cash',
                'allow_overpayment' => null,
                'note'              => null,
                'created_by'        => Auth::id(),
            ]);

            return $sale->load('items', 'payments');
        });
    }

    // -----------------------------------------------------------------
    // Sales history — new, powered entirely by BaseService now that
    // getQuery() resolves to Sales::query().
    // -----------------------------------------------------------------

    public function listSales(array $params = []): LengthAwarePaginator
    {
        return $this->getAll($params);
    }

    public function getSale(string $id): Sales
    {
        return $this->getById(Sales::class, $id);
    }

    public function getSaleByGlobalId(string $globalId): Sales
    {
        return $this->getByGlobalId(Sales::class, $globalId);
    }

    public function updateSale(array $params, string $id): Sales
    {
        return $this->update($params, $id);
    }

    // -----------------------------------------------------------------
    // Hold / resume order (suspends + suspend_items)
    // -----------------------------------------------------------------

    public function holdOrder(array $validated): Suspend
    {
        $subtotal = collect($validated['items'])->sum(fn ($i) => $i['qty'] * $i['unit_price']);
        $discount = $validated['discount'] ?? 0;

        $suspend = Suspend::create([
            'customer_id'  => $validated['customer_id'] ?? null,
            'warehouse_id' => $validated['warehouse_id'] ?? 1,
            'salesman_id'  => $validated['biller_id'] ?? Auth::id(),
            'total'        => $subtotal,
            'discount'     => $discount,
            'shipping'     => 0,
            'tax'          => 0,
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);

            SuspendItem::create([
                'suspend_id' => $suspend->id,
                'product_id' => $product->id,
                'name'       => $product->product_name,
                'price'      => $item['unit_price'],
                'qty'        => $item['qty'],
                'unit_id'    => $item['unit_id'] ?? $product->unit_id,
                'code'       => $product->sku,
                'subtotal'   => $item['qty'] * $item['unit_price'],
            ]);
        }

        return $suspend;
    }

    public function listHolds()
    {
        return Suspend::with('items')->latest()->get();
    }

    public function resumeHold(int $id)
    {
        $suspend = Suspend::with('items')->findOrFail($id);
        $items   = $suspend->items;

        $suspend->items()->delete();
        $suspend->delete();

        return $items;
    }
}