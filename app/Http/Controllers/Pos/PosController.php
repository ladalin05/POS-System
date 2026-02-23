<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Adjustment\StockMove;
use App\Models\Other\Branch;
use App\Models\Other\CashAccount;
use App\Models\Other\Currencies;
use App\Models\Sales\SaleItems;
use App\Models\Suspend\SuspendItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product\Product;
use App\Models\People\Customer;
use App\Models\Product\Category;
use App\Models\Product\ProductUnit;
use App\Models\Sales\Sales;
use App\Models\Setting\Floor;
use App\Models\Setting\Room;
use App\Models\Setting\Unit;
use App\Models\Suspend\Suspend;
use App\Models\Warehouses\Warehouses;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class PosController extends Controller
{

    public function index(Request $request)
    {
        // If a room_id query param is present, save the room as the selected table in session
        if ($request->has('room_id')) {
            $roomId = (int) $request->query('room_id');
            $room = Room::find($roomId);
            if ($room) {
                // store full array so we have id + name available
                session(['selected_table' => ['id' => $room->id, 'name' => $room->name]]);
                // Redirect to same route *without* the query param to keep URL clean
                return redirect()->route('pos.index');
            }
        }
        $countSuspend = Suspend::count();
        $warehouses = Warehouses::all();
        $currencies = Currencies::all();
        $billers = Branch::all();



        $suspend = Suspend::orderByDesc('id')->first();
        $lastId = $suspend->id ?? 0;



        $cash_accounts = CashAccount::select('id', 'name')->get();

        // Build reusable HTML for <option> elements (server-side)
        $cashOptionsHtml = '';
        foreach ($cash_accounts as $acc) {
            $rateAttr = isset($acc->rate) ? (float) $acc->rate : 1;
            // Escape the name to be safe in HTML
            $name = e($acc->name);
            $cashOptionsHtml .= '<option value="' . $acc->id . '" data-rate="' . $rateAttr . '" cash_type="cash">' . $name . '</option>';
        }





        $pageSize = config('pos.categories_per_page', 100);
        $offset = 0;
        $cat_id = (int) $request->query('category_id', 0);

        $categories = Category::orderBy('name')
            ->skip($offset)
            ->take($pageSize)
            ->get(['id', 'name']);

        // pick first category if not selected
        if (!$cat_id) {
            $cat_id = $categories->first()->id ?? 0;
        }

        // initial products
        $lim = (int) config('pos.pro_limit', 25);
        $products = Product::when($cat_id, fn($q) => $q->where('category_id', $cat_id))
            ->orderBy('name')
            ->take($lim)
            ->get(['id', 'code', 'name', 'image']);

        // rooms
        $rooms = Room::join('floor', 'floor.id', '=', 'rooms.floor_id')
            ->select('rooms.*', 'floor.name as floor_name')
            ->get();

        // read selected table safely from session
        $selectedTable = session('selected_table', null);
        $selectedRoomId = $selectedTable['id'] ?? null;
        $selectedRoomName = $selectedTable['name'] ?? null;
        $sid = session('current_sid') ?? null;
        return view('pos.index_new', compact(
            'warehouses',
            'billers',
            'categories',
            'cat_id',
            'pageSize',
            'products',
            'rooms',
            'selectedRoomId',
            'selectedRoomName',
            'countSuspend',
            'sid',
            'currencies',
            'cash_accounts',
            'cashOptionsHtml',   // << add this
            'suspend',
        ));
    }

    public function ajaxCategoryData(Request $request)
    {
        $categoryInput = $request->query('category_id', []);
        if (!is_array($categoryInput)) {
            if ($categoryInput === '' || $categoryInput === null) {
                $category_ids = [];
            } else {
                $category_ids = [(int) $categoryInput];
            }
        } else {
            $category_ids = array_map('intval', $categoryInput);
        }

        $category_ids = array_values(array_filter($category_ids, fn($v) => $v > 0));
        $offset = max(0, (int) $request->query('per_page', 0));

        $code = $request->query('sp_code');
        $name = $request->query('sp_name');
        $favorite = $request->query('sp_favorite');

        // --- Subcategories ---
        $scats = '';
        if (!empty($category_ids)) {
            $first = $category_ids[0];
            $subcategories = Category::where('parent_id', $first)->get();
            if ((int) config('pos.pos_category_fix') === 1) {
                foreach ($subcategories as $category) {
                    $scats .= "<button type='button' disabled-open-category='true' value='{$category->id}' class='ccategory btn cl-primary subcategory' style='width:16.6666666667%; margin:0px; height:50px; font-weight:bold;'>{$category->name}</button>";
                }
                $scats .= '<div class="clearfix"></div>';
            } else {
                foreach ($subcategories as $category) {
                    $img = $category->image ?: 'no_image.png';
                    $imgPath = asset('assets/images/' . $img);
                    $scats .= "<button id=\"subcategory-{$category->id}\" type=\"button\" value=\"{$category->id}\" class=\"btn-prni subcategory\">
                    <img src=\"{$imgPath}\" style='width:" . config('pos.thumb_width') . "px;height:" . config('pos.thumb_height') . "px;' class='img-rounded img-thumbnail' />
                    <span>{$category->name}</span>
                </button>";
                }
            }
        }

        // --- Products ---
        $lim = (int) config('pos.pro_limit', 15);

        $productsQuery = Product::query()
            ->when(!empty($category_ids), fn($q) => $q->whereIn('category_id', $category_ids))
            ->when($code, fn($q) => $q->where('code', 'like', "%{$code}%"))
            ->when($name, fn($q) => $q->where('name', 'like', "%{$name}%"))
            ->when($favorite !== null && $favorite !== '', fn($q) => $q->where('favorite', $favorite))
            ->orderBy('name');

        $totalProducts = (int) $productsQuery->count();

        // Get products and their units
        $products = $productsQuery->skip($offset)->take($lim)->get(['id', 'code', 'name', 'image']);

        // --- Fetch related product units ---
        $productIds = $products->pluck('id')->toArray();
        $productUnits = DB::table('product_units')
            ->whereIn('product_id', $productIds)
            ->select('product_id', 'unit_id', 'price')
            ->get()
            ->groupBy('product_id');

        // --- Render product buttons ---
        $html = '';
        foreach ($products as $p) {
            // Image path
            if (!empty($p->image)) {
                if (preg_match('#^https?://#i', $p->image)) {
                    $imgPath = $p->image;
                } elseif (str_starts_with($p->image, 'assets/') || str_starts_with($p->image, '/assets/')) {
                    $imgPath = asset(ltrim($p->image, '/'));
                } elseif (str_starts_with($p->image, 'uploads/') || str_starts_with($p->image, '/uploads/')) {
                    $imgPath = asset(ltrim($p->image, '/'));
                } else {
                    $imgPath = asset('assets/images/' . ltrim($p->image, '/'));
                }
            } else {
                $imgPath = asset('assets/images/no_image.png');
            }


            $unitsHtml = '';
            if (isset($productUnits[$p->id])) {
                foreach ($productUnits[$p->id] as $u) {
                    $unitsHtml .= "<div class='unit-line'>Unit ID: {$u->unit_id} | Price: {$u->price}</div>";
                }
            }

            $html .= "
            <button id='product-{$p->id}' type='button' value='{$p->id}'
                class='btn-prni btn-default product pos-tip'
                title='" . e($p->code . ' ' . $p->name) . "' data-container='body'>
                <img src='{$imgPath}' alt='" . e($p->name) . "' style='width:150px;height:150px;' class='img-rounded'/>
                <span>" . e($p->name) . "</span>
                
            </button>";
        }

        $tcp = (int) ceil($totalProducts / max(1, $lim));

        return response()->json([
            'products' => $html,
            'subcategories' => $scats,
            'tcp' => $tcp,
            'total' => $totalProducts,
        ]);
    }

    public function moveRoom(Request $request)
    {
        $roomId = (int) $request->query('room_id', 0);
        if (!$roomId) {
            return redirect()->route('pos.table.addTable')->with('error', 'No room selected.');
        }

        $room = Room::find($roomId);
        if (!$room) {
            return redirect()->route('pos.table.addTable')->with('error', 'Room not found.');
        }

        // prevent moving into an occupied room
        if ($room->status === 'occupied') {
            return redirect()->route('pos.table.addTable')->with('error', 'That room is occupied. Choose another.');
        }

        // update session to point selected table to the new room
        session([
            'selected_table' => [
                'id' => $room->id,
                'name' => $room->name
            ]
        ]);

        // optional flash message
        session()->flash('success', 'Moved to room: ' . $room->name);

        // back to POS index
        return redirect()->route('pos.index');
    }

    public function clearTable()
    {
        session()->forget('selected_table');
        return redirect()->route('pos.index');
    }

    public function addTable(Request $request)
    {
        // if user requested to clear selection, remove it and redirect cleanly
        if ($request->query('clear')) {
            session()->forget('selected_table');
            return redirect()->route('pos.table.addTable');
        }

        // load data (adapt queries to your app if needed)
        $floors = Floor::orderBy('name')->get();
        $rooms = Room::with('floor')->get(); // adjust as needed
        $customers = Customer::orderBy('name')->get();
        $warehouses = Warehouses::orderBy('name')->get();

        // selected table from session (if any)
        $selectedTable = session('selected_table', null);
        $selectedRoomId = $selectedTable['id'] ?? null;
        $selectedRoomName = $selectedTable['name'] ?? null;

        // pass these to blade
        return view('pos.table.form', compact(
            'floors',
            'rooms',
            'customers',
            'warehouses',
            'selectedRoomId',
            'selectedRoomName'
        ));
    }

    public function searchCustomer(Request $request)
    {
        $term = $request->get('q', '');

        $results = Customer::select('id', 'name', 'phone')
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%");

            })
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'phone' => $c->phone,

                ];
            });

        return response()->json($results);
    }
    public function getWarehouses()
    {
        $warehouses = Warehouses::select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($warehouses);
    }

    public function getProductDataByCode(Request $request)
    {
        $id = $request->query('id');

        if (!$id) {
            return response()->json(null);
        }

        $product = Product::where('id', $id)
            ->select('id', 'code', 'name', 'price', 'image', 'unit_id as base_unit_id', 'cost')
            ->first();

        if (!$product) {
            return response()->json(null);
        }

        // prepare units array
        $units = collect();

        // 1) include product's base unit (from products.unit_id) if set
        if (!empty($product->base_unit_id)) {
            $base = DB::table('units')->where('id', $product->base_unit_id)->select('id', 'name', 'code')->first();
            $units->push([
                'unit_id' => (int) $product->base_unit_id,
                'unit_name' => $base->name ?? 'Base Unit',
                'unit_code' => $base->code ?? null,
                'qty' => 1.0,
                'price' => $product->price !== null ? (float) $product->price : ($product->cost !== null ? (float) $product->cost : null),
                'is_base' => true,
            ]);
        }

        // 2) get conversion units from product_units (if any)
        $rows = DB::table('product_units as pu')
            ->leftJoin('units as u', 'pu.unit_id', '=', 'u.id')
            ->where('pu.product_id', $product->id)
            ->select(
                'pu.id as product_unit_id',
                'pu.unit_id',
                DB::raw('COALESCE(u.name, CONCAT("Unit ", pu.unit_id)) as unit_name'),
                DB::raw('u.code as unit_code'),
                'pu.qty',
                'pu.price'
            )
            ->orderByDesc('pu.qty')
            ->get();

        foreach ($rows as $r) {
            $units->push([
                'product_unit_id' => $r->product_unit_id,
                'unit_id' => (int) $r->unit_id,
                'unit_name' => $r->unit_name,
                'unit_code' => $r->unit_code,
                'qty' => $r->qty !== null ? (float) $r->qty : null,
                'price' => $r->price !== null ? (float) $r->price : null,
                'is_base' => false,
            ]);
        }

        // attach units to product and return
        $product->units = $units->values();

        return response()->json($product);
    }

    public function searchProductByName(Request $request)
    {
        $term = trim((string) $request->get('q', ''));

        if ($term === '') {
            return response()->json([], 200);
        }

        $results = DB::table('products as p')
            ->leftJoin('units as u', 'p.unit_id', '=', 'u.id')
            ->select(
                'p.id',
                'p.code',
                'p.name',
                DB::raw('CASE WHEN p.price IS NOT NULL THEN p.price ELSE NULL END as price'),
                'p.image',
                'p.unit_id as unit_id',
                DB::raw('u.name as unit_name'),
                DB::raw('u.code as unit_code')
            )
            ->where(function ($q) use ($term) {
                $q->where('p.code', 'like', '%' . $term . '%')
                    ->orWhere('p.name', 'like', '%' . $term . '%');
            })
            ->orderBy('p.name')
            ->limit(20)
            ->get()
            ->map(function ($r) {
                return [
                    'id' => (int) $r->id,
                    'code' => $r->code,
                    'name' => $r->name,
                    'price' => $r->price !== null ? (float) $r->price : null,
                    'image' => $r->image ?? null,
                    'unit_id' => $r->unit_id ? (int) $r->unit_id : null,
                    'unit_name' => $r->unit_name ?? null,
                    'unit_code' => $r->unit_code ?? null,
                ];
            });

        return response()->json($results);
    }


    public function Suspend(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'nullable|integer',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.price' => 'required|numeric',
            'items.*.qty' => 'required|integer',
        ]);

        DB::transaction(function () use ($data) {
            $s = Suspend::create([
                'room_id' => $data['room_id'] ?? null,
            ]);
            foreach ($data['items'] as $item) {
                $s->items()->create($item);
            }
        });

        return response()->json(['message' => 'Sale suspended.']);
    }

    public function todaySale()
    {

        $cash_in_hand = 0;
        $product_sale = 0;
        $sale_discount = 0;
        $total_sale = 0;
        $sale_return = 0;
        $expense = 0;
        $total_cash = $cash_in_hand + $total_sale - $sale_return - $expense;

        return view('pos.today_sale', compact(
            'cash_in_hand',
            'product_sale',
            'sale_discount',
            'total_sale',
            'sale_return',
            'expense',
            'total_cash'
        ));
    }

    public function saveSuspend(Request $request)
    {
        // Validate basic structure
        $validator = FacadesValidator::make($request->all(), [
            'customer_id' => 'nullable|integer',
            'warehouse_id' => 'nullable|integer',
            'salesman_id' => 'nullable|integer',
            'room_id' => 'nullable|integer',
            'total' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'shipping' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payload',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        DB::beginTransaction();
        try {
            // Create parent suspend record
            $suspend = Suspend::create([
                'room_id' => $data['room_id'] ?? null,
                'customer_id' => $data['customer_id'] ?? null,
                'warehouse_id' => $data['warehouse_id'] ?? null,
                'salesman_id' => $data['salesman_id'] ?? auth()->id() ?? null,
                'total' => $data['total'],
                'discount' => $data['discount'] ?? 0,
                'shipping' => $data['shipping'] ?? 0,
                'tax' => $data['tax'] ?? 0,
                // add other parent fields here if needed
            ]);

            // Insert items linked to suspend
            $items = $data['items'];
            foreach ($items as $it) {
                // normalize fields
                $itemData = [
                    'suspend_id' => $suspend->id,
                    'product_id' => $it['product_id'] ?? null,
                    'unit_id' => $it['unit_id'] ?? ($it['current_unit_id'] ?? null),
                    'name' => $it['name'] ?? null,
                    'code' => $it['code'] ?? null,
                    'price' => $it['price'] ?? 0,
                    'qty' => $it['qty'] ?? 1,
                    'subtotal' => $it['subtotal'] ?? (($it['price'] ?? 0) * ($it['qty'] ?? 1)),
                    // add other item columns here if present
                ];

                SuspendItem::create($itemData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'id' => $suspend->id,
                'message' => 'Saved suspend successfully',
                'redirect' => route('pos.table.addTable')
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to save suspend',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function openedBills()
    {

        $suspends = Suspend::with('items')->orderByDesc('created_at')->get();


        return view('pos.opened_bills', compact('suspends'));
    }

    public function openedBillsItems($sid = null)
    {
        if ($sid) {
            $suspend = Suspend::with('items.product')->find($sid);

            if (!$suspend) {
                return response()->json(['error' => 'Suspend not found'], 404);
            }

            $suspend->items->map(function ($item) {
                $product = $item->product;
                $units = collect();

                // Base unit (if product has a base unit)
                if (!empty($product->unit_id)) {
                    $base = DB::table('units')->where('id', $product->unit_id)->first();
                    $units->push([
                        'unit_id' => (int) $product->unit_id,
                        // keep both name keys so frontend can use u.name || u.unit_name
                        'unit_name' => $base->name ?? 'Base Unit',
                        'name' => $base->name ?? 'Base Unit',
                        'unit_code' => $base->code ?? null,
                        'qty' => 1.0,
                        'price' => isset($product->price) ? (float) $product->price : ((isset($product->cost) ? (float) $product->cost : 0.0)),
                        'is_base' => true,
                    ]);
                }

                // Conversion units (product_units)
                $conv_units = DB::table('product_units as pu')
                    ->leftJoin('units as u', 'pu.unit_id', '=', 'u.id')
                    ->where('pu.product_id', $product->id)
                    ->select(
                        'pu.id as product_unit_id',
                        'pu.unit_id',
                        DB::raw('COALESCE(u.name, CONCAT("Unit ", pu.unit_id)) as unit_name'),
                        'u.code as unit_code',
                        'pu.qty',
                        'pu.price'
                    )
                    ->orderByDesc('pu.qty')
                    ->get();

                foreach ($conv_units as $r) {
                    $units->push([
                        'product_unit_id' => (int) $r->product_unit_id,
                        'unit_id' => (int) $r->unit_id,
                        'unit_name' => $r->unit_name,
                        'name' => $r->unit_name,
                        'unit_code' => $r->unit_code,
                        'qty' => (float) $r->qty,
                        'price' => $r->price !== null ? (float) $r->price : null,
                        'is_base' => false,
                    ]);
                }

                // Attach 'units' as a plain array and also expose a current_unit_id for frontend selection
                $item->units = $units->values()->toArray();

                // Normalize what the frontend expects for the selected unit:
                // If your stored item has a product_unit_id, prefer it; else if it stores unit_id, use that.
                // Adjust these fields based on how you persist selected unit in Suspend->items table
                $item->current_unit_id = $item->product_unit_id ?? $item->unit_id ?? null;

                return $item;
            });

            return response()->json($suspend);
        }

        // no $sid -> return all suspends (same per-item logic)
        $suspends = Suspend::with('items.product')->orderByDesc('created_at')->get();

        $suspends->map(function ($suspend) {
            $suspend->items->map(function ($item) {
                $product = $item->product;
                $units = collect();

                if (!empty($product->unit_id)) {
                    $base = DB::table('units')->where('id', $product->unit_id)->first();
                    $units->push([
                        'unit_id' => (int) $product->unit_id,
                        'unit_name' => $base->name ?? 'Base Unit',
                        'name' => $base->name ?? 'Base Unit',
                        'unit_code' => $base->code ?? null,
                        'qty' => 1.0,
                        'price' => isset($product->price) ? (float) $product->price : ((isset($product->cost) ? (float) $product->cost : 0.0)),
                        'is_base' => true,
                    ]);
                }

                $conv_units = DB::table('product_units as pu')
                    ->leftJoin('units as u', 'pu.unit_id', '=', 'u.id')
                    ->where('pu.product_id', $product->id)
                    ->select(
                        'pu.id as product_unit_id',
                        'pu.unit_id',
                        DB::raw('COALESCE(u.name, CONCAT("Unit ", pu.unit_id)) as unit_name'),
                        'u.code as unit_code',
                        'pu.qty',
                        'pu.price'
                    )
                    ->orderByDesc('pu.qty')
                    ->get();

                foreach ($conv_units as $r) {
                    $units->push([
                        'product_unit_id' => (int) $r->product_unit_id,
                        'unit_id' => (int) $r->unit_id,
                        'unit_name' => $r->unit_name,
                        'name' => $r->unit_name,
                        'unit_code' => $r->unit_code,
                        'qty' => (float) $r->qty,
                        'price' => $r->price !== null ? (float) $r->price : null,
                        'is_base' => false,
                    ]);
                }

                $item->units = $units->values()->toArray();
                $item->current_unit_id = $item->product_unit_id ?? $item->unit_id ?? null;

                return $item;
            });

            return $suspend;
        });

        return response()->json($suspends);
    }



    public function submitSale(Request $request)
    {
        $data = $request->all();

        if (empty($data['items']) || !is_array($data['items'])) {
            return response()->json(['success' => false, 'message' => 'No items provided'], 422);
        }

        DB::beginTransaction();
        try {
            $total = floatval($data['total'] ?? 0);
            $tax = floatval($data['tax'] ?? 0);
            $returned = floatval($data['returned'] ?? 0);
            $discount = floatval($data['discount'] ?? 0);
            $shipping = floatval($data['shipping'] ?? 0);
            $room_id = floatval($data['room_id'] ?? 0);
            $warehouseId = intval($data['warehouse_id'] ?? 0);

            // calculate grand_total
            $grandTotal = $total + $tax + $shipping - $discount - $returned;
            $grandTotal = $grandTotal < 0 ? 0 : $grandTotal;

            $paid = floatval($data['paid'] ?? 0);

            // balance and return_amount (change)
            $balance = $grandTotal - $paid;
            $returnAmount = 0;
            if ($balance < 0) {
                $returnAmount = abs($balance);
                $balance = 0;
            }

            // determine payment_status
            if ($paid <= 0) {
                $paymentStatus = $data['payment_status'] ?? 'pending';
            } elseif ($paid < $grandTotal) {
                $paymentStatus = 'partial';
            } else {
                $paymentStatus = 'paid';
            }

            $deliveryStatus = $data['delivery_status'] ?? 'pending';
            $referenceNo = $data['reference_no'] ?? ('INV/' . date('Ymd') . '/' . time());


            $groupedItems = [];
            $neededPairs = []; // to preload unit conversion
            foreach ($data['items'] as $it) {
                $productId = intval($it['product_id'] ?? 0);
                if (!$productId) {
                    throw new Exception('Invalid product_id in items');
                }

                $unitId = $it['unit_id'] ?? null;
                $qty = floatval($it['qty'] ?? 1);
                $unitPrice = floatval($it['unit_price'] ?? $it['price'] ?? 0);

                // CONSISTENT key: use 'null' when unit missing
                $key = $productId . '_' . ($unitId ?? 'null');
                $subtotal = $qty * $unitPrice;

                if (!isset($groupedItems[$key])) {
                    $groupedItems[$key] = [
                        'product_id' => $productId,
                        'unit_id' => $unitId,
                        'unit_price' => $unitPrice,
                        'qty' => $qty,
                        'subtotal' => $subtotal,
                        'name' => $it['name'] ?? null,
                        'code' => $it['code'] ?? null,
                    ];
                } else {
                    $groupedItems[$key]['qty'] += $qty;
                    $groupedItems[$key]['subtotal'] += $subtotal;
                }

                if (!empty($unitId)) {
                    // store with the same consistent key
                    $neededPairs[$productId . '_' . $unitId] = ['product_id' => $productId, 'unit_id' => $unitId];
                } else {
                    // ensure we also have an entry for 'null' key if no unit provided
                    $neededPairs[$productId . '_null'] = ['product_id' => $productId, 'unit_id' => null];
                }
            }

            // Preload conversion factors (product-unit -> qty)
            $unitQtyMap = [];
            if (!empty($neededPairs)) {
                foreach ($neededPairs as $k => $p) {
                    $qty = ProductUnit::where('product_id', $p['product_id'])
                        ->where('unit_id', $p['unit_id'])
                        ->value('qty');
                    $unitQtyMap[$k] = $qty ? (float) $qty : 1.0;
                }
            }

            $productIds = array_unique(array_map(function ($g) {
                return $g['product_id'];
            }, $groupedItems));
            $products = Product::whereIn('id', $productIds)->get(['id', 'code', 'type', 'quantity'])->keyBy('id');

            $shortages = [];
            $groupedBaseQty = [];
            foreach ($groupedItems as $key => $gi) {
                $productId = $gi['product_id'];
                $unitId = $gi['unit_id'];
                $enteredQty = (float) $gi['qty'];

                // use consistent lookup key for conversion
                $mapKey = $productId . '_' . ($unitId ?? 'null');
                $conversion = $unitQtyMap[$mapKey] ?? 1.0;
                $baseQty = $enteredQty * $conversion;
                $groupedBaseQty[$key] = $baseQty;

                $prod = $products->get($productId);
                if (strtolower($prod->type ?? '') === 'service') {
                    continue; // no stock check for services
                }

                // IMPORTANT CHANGE: do NOT filter by unit_id here.
                // Sum unit_quantity (base units) for the product (and warehouse if provided).
                $available = (float) StockMove::where('product_id', $productId)
                    ->when($warehouseId, function ($q) use ($warehouseId) {
                        return $q->where('warehouse_id', $warehouseId);
                    })
                    ->sum('unit_quantity');

                if ($available < $baseQty) {
                    $shortages[] = [
                        'product_id' => $productId,
                        'product_code' => $prod->code ?? null,
                        'required_base_qty' => $baseQty,
                        'available_base_qty' => $available,
                    ];
                }
            }

            if (!empty($shortages)) {
                // do not proceed — inform caller which items are short
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock for some items.',
                    'shortages' => $shortages
                ], 422);
            }

            // All good — create the sale record
            $sale = Sales::create([
                'date' => $data['date'] ?? now(),
                'reference_no' => $referenceNo,
                'customer_id' => $data['customer_id'] ?? null,
                'biller_id' => $data['salesman_id'] ?? $data['biller_id'] ?? null,
                'warehouse_id' => $warehouseId ?: null,
                'room_id' => $room_id,
                'total' => $total,
                'tax' => $tax,
                'returned' => $returned,
                'discount' => $discount,
                'shipping' => $shipping,
                'grand_total' => $grandTotal,
                'paid' => $paid,
                'balance' => $balance,
                'return_amount' => $returnAmount,
                'delivery_status' => $deliveryStatus,
                'payment_status' => $paymentStatus,
                'status' => $data['status'] ?? 'completed',
                'note' => $data['note'] ?? null,
                'created_by' => auth()->id() ?? null,
            ]);

            // Insert SaleItems (grouped) and prepare StockMove rows
            $stockMoveRows = [];
            $productQtyDelta = []; // product_id => base_qty delta (to subtract)
            foreach ($groupedItems as $key => $gi) {
                // create sale item
                $saleItem = SaleItems::create([
                    'sale_id' => $sale->id,
                    'product_id' => $gi['product_id'],
                    'unit_id' => $gi['unit_id'],
                    'unit_price' => $gi['unit_price'],
                    'qty' => $gi['qty'],
                    'subtotal' => $gi['subtotal'],
                    'name' => $gi['name'],
                    'code' => $gi['code'],
                ]);

                $baseQty = $groupedBaseQty[$key] ?? ($gi['qty'] * ($unitQtyMap[$gi['product_id'] . '_' . ($gi['unit_id'] ?? 'null')] ?? 1.0));
                $prod = $products->get($gi['product_id']);

                // Only add stockmove rows and qty deltas for non-service products
                if (strtolower($prod->type ?? '') !== 'service') {
                    $stockMoveRows[] = [
                        'transaction' => 'sale',
                        'transaction_id' => (int) $sale->id,
                        'quantity' => (float) $gi['qty'],            // entered qty (units)
                        'unit_quantity' => -1.0 * (float) $baseQty,  // negative base qty to show removal
                        'product_id' => (int) $gi['product_id'],
                        'product_type' => $prod->type ?? null,
                        'product_code' => $prod->code ?? null,
                        'date' => $data['date'] ?? now(),
                        'unit_code' => optional(Unit::find($gi['unit_id']))->code ?? null,
                        'unit_id' => $gi['unit_id'] ?? null,
                        'warehouse_id' => $warehouseId ?: null,
                        'expiry' => null,
                        'real_unit_cost' => (float) ($gi['unit_price'] ?? 0),
                        'serial_no' => null,
                        'reference_no' => $sale->reference_no,
                        'user_id' => auth()->id() ?? null,
                        'created_at' => now()->utc(),
                        'updated_at' => now()->utc(),
                    ];

                    $productQtyDelta[$gi['product_id']] = ($productQtyDelta[$gi['product_id']] ?? 0) + $baseQty;
                }
            }

            // Insert stock move rows in bulk
            if (!empty($stockMoveRows)) {
                StockMove::insert($stockMoveRows);
            }

            // Update product quantities (subtract base qty) only for non-service products
            if (!empty($productQtyDelta)) {
                $productModels = Product::whereIn('id', array_keys($productQtyDelta))->get()->keyBy('id');
                foreach ($productQtyDelta as $pid => $delta) {
                    $p = $productModels->get($pid);
                    if ($p && isset($p->quantity)) {
                        $p->quantity = (float) $p->quantity - (float) $delta;
                        $p->save();
                    }
                }
            }

            // If a suspend id provided, delete suspend + items
            $suspendId = $data['suspend_id'] ?? $data['suspend'] ?? null;
            if ($suspendId) {
                try {
                    SuspendItem::where('suspend_id', $suspendId)->delete();
                    Suspend::where('id', $suspendId)->delete();
                } catch (\Exception $ex) {
                    Log::warning('submitSale: failed to delete suspend', ['suspend_id' => $suspendId, 'error' => $ex->getMessage()]);
                }
            }

            DB::commit();

            // reload for invoice rendering
            $sale->load(['items', 'customer']);
            $saleItems = SaleItems::where('sale_id', $sale->id)->with('unit')->get();

            $invoiceHtml = view('pos.modal_view', [
                'sale' => $sale,
                'saleItems' => $saleItems,
                'logo_url' => '/mnt/data/2d130323-e5b3-4185-84b7-845b94ae3e02.png'
            ])->render();

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'message' => 'Sale saved successfully',
                'invoice_html' => $invoiceHtml
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('submitSale error: ' . $e->getMessage(), ['payload' => $data]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save sale: ' . $e->getMessage()
            ], 500);
        }
    }



    public function modal_bill($id)
    {
        $suspend = Suspend::with(['items.unit', 'customer', 'room'])->findOrFail($id);
        $suspendItems = SuspendItem::where('suspend_id', $id)->get(); // optional: only items for this suspend
        return view('pos.modal_bill', compact('suspend', 'suspendItems'));
    }



}




