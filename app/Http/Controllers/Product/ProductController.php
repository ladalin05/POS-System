<?php

namespace App\Http\Controllers\Product;

use App\DataTables\Product\AlertQuantityDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use App\DataTables\Product\ProductDataTable;
use App\Models\Other\Branch;
use App\Models\Product\Product;
use App\Models\Product\ProductUnit;
use App\Models\Setting\Unit;
use App\Models\Product\Category;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('product.products.index');
    }

    public function add()
    {
        $title = __('global.add_new');
        $form = new Product();

        $categories = Category::select('name', 'id')->get();
        $units = Unit::select('name', 'id')->get();
        $branch = Branch::select('name', 'id')->get();



        return view('product.products.form', compact('title', 'form', 'categories', 'units', 'branch'));
    }

    public function edit($id)
    {
        $title = __('global.edit');
        $form = Product::findOrFail($id);

        $categories = Category::select('name', 'id')->get();
        $units = Unit::select('name', 'id')->get();
        $branch = Branch::select('name', 'id')->get();



        return view('product.products.form', compact('title', 'form', 'categories', 'units', 'branch'));
    }

    // save (create/update)
    public function save(Request $request, $id = null)
    {

        $request->merge([
            'promotion' => $request->boolean('promotion'),
        ]);


        $isDateOnly = true;


        $rules = [
            'product_type' => ['required', Rule::in(['Standard', 'Service'])],
            'name' => ['required', 'string', 'max:255'],
            'product_code' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0'],

            'unit_id' => ['required_if:product_type,Standard', 'nullable', 'integer', 'exists:units,id'],
            'sale_unit' => ['nullable', 'integer', 'exists:units,id'],
            'purchase_unit' => ['nullable', 'integer', 'exists:units,id'],
            'alert_quantity' => ['nullable', 'numeric', 'min:0'],
            'brand' => ['required'],

            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'product_details' => ['nullable', 'string'],

            // Promotion
            'promotion' => ['nullable', 'boolean'],
            'promo_price' => ['nullable', 'numeric', 'min:0'],
            'promo_qty' => ['nullable'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],

            // Unit grid
            'product_units' => ['nullable', 'array'],
            'product_units.*.unit_id' => ['nullable', 'integer', 'exists:units,id'],
            'product_units.*.qty' => ['nullable', 'numeric', 'min:0'],
            'product_units.*.price' => ['nullable', 'numeric', 'min:0'],
        ];

        $validated = $request->validate($rules);


        if (($validated['product_type'] ?? null) === 'Service') {
            $validated['unit_id'] = null;
            $validated['sale_unit'] = null;
            $validated['purchase_unit'] = null;
            $validated['alert_quantity'] = null;
            $validated['product_units'] = [];
        }


        $tz = 'Asia/Phnom_Penh';
        $startAt = null;
        $endAt = null;

        if (!empty($validated['start_date'])) {
            $startAt = $isDateOnly
                ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $validated['start_date'], $tz)->startOfDay()
                : \Illuminate\Support\Carbon::parse($validated['start_date'], $tz);
        }
        if (!empty($validated['end_date'])) {
            $endAt = $isDateOnly
                ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $validated['end_date'], $tz)->endOfDay()
                : \Illuminate\Support\Carbon::parse($validated['end_date'], $tz);
        }


        $now = now($tz);
        $autoExpired = false;
        if (!empty($validated['promotion']) && $endAt && $now->gt($endAt)) {
            $validated['promotion'] = false;
            $autoExpired = true;
            // $validated['promo_price'] = null;
        }


        if (empty($validated['promotion']) && !$autoExpired) {
            // $validated['promo_price'] = null;

            // $startAt = null;
            // $endAt   = null;
        }

        DB::beginTransaction();
        try {
            $data = [
                'type' => $validated['product_type'],
                'name' => $validated['name'],
                'code' => $validated['product_code'],
                'category_id' => $validated['category_id'],
                'price' => $validated['price'],
                'cost' => $validated['cost'] ?? null,

                'unit_id' => $validated['unit_id'] ?? null,
                'sale_unit' => $validated['sale_unit'] ?? null,
                'purchase_unit' => $validated['purchase_unit'] ?? null,
                'alert_quantity' => $validated['alert_quantity'] ?? null,

                'brand' => $validated['brand'] ?? null,
                'product_details' => $validated['product_details'] ?? null,

                'promotion' => (bool) ($validated['promotion'] ?? false),
                'promo_price' => $validated['promo_price'] ?? null,
                'promo_qty' => $validated['promo_qty'] ?? null,
                'start_date' => $startAt,
                'end_date' => $endAt,
            ];


            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
                $image->move(public_path('uploads/products'), $filename);
                $data['image'] = 'uploads/products/' . $filename;
            }


            $product = Product::updateOrCreate(['id' => $id], $data);


            ProductUnit::where('product_id', $product->id)->delete();
            if ($validated['product_type'] === 'Standard') {
                foreach (($validated['product_units'] ?? []) as $row) {
                    if (empty($row['unit_id']))
                        continue;
                    ProductUnit::create([
                        'product_id' => $product->id,
                        'unit_id' => (int) $row['unit_id'],
                        'qty' => (float) ($row['qty'] ?? 0),
                        'price' => isset($row['price']) ? (float) $row['price'] : null,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $id ? __('messages.user_updated') : __('messages.user_saved'),
                'redirect' => route('products.products.index'),
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
                return json([
                    'status' => 'error',
                    'message' => __('messages.user_cannot_delete'),
                ]);
            }

            $form = Product::findOrFail($id);
            $form->delete();

            return json([
                'status' => 'success',
                'message' => __('messages.user_deleted'),
            ]);
        } catch (\Throwable $e) {
            return json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function view($id)
    {
        // Adjust relation/key names if different in your models
        $product = Product::with(['category', 'unit'])->findOrFail($id);
        return view('product.products.view', compact('product'));
    }









    public function alert_quantity(AlertQuantityDataTable $dataTable)
    {

        return $dataTable->render('product.products.alert_qty.index');
        // return view('product.products.alert_qty.index', compact('products'));
    }



}
