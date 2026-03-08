<?php

namespace App\Http\Controllers\Stocks;

use App\Http\Controllers\Controller;
use App\DataTables\Stocks\ProductStockDataTable;
use App\Models\Stocks\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageStockController extends Controller
{
    public function index(ProductStockDataTable $dataTable)
    {
        return $dataTable->render('stocks.product_stock.index');
    }

    public function create(Request $request)
    {
        try{

            if($request->isMethod('get')){
                $title = __('global.add_new');
                $form = new ProductStock();
                $action = route('stocks.manage.add');
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('stocks.product_stock.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if($request->isMethod('post')){
                $request->validate([
                    'warehouse_id' => 'required|integer',
                    'respon_person_id' => 'required|integer',
                    'products' => 'required|array',
                ]);


                foreach($request->products as $product){
                    $stock = ProductStock::where('warehouse_id', $request->warehouse_id)
                                ->where('product_id', $product['product_id'])
                                ->first();

                    if (!$stock) {
                        ProductStock::create([
                            'warehouse_id'     => $request->warehouse_id,
                            'respon_person_id' => $request->respon_person_id,
                            'product_id'       => $product['product_id'],
                            'stock'            => $product['qty'],
                            'alert_quantity'   => $product['alert_qty'],
                            'created_by'       => Auth::id(),
                        ]);
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.create_stock_success'),
                    'redirect' => route('stocks.manage.index'),
                    'modal' => 'action-modal',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => __('messages.405'),
            ]);

        } catch(\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request)
    {
        try{

            if($request->isMethod('get')){
                $title = __('global.edit');
                $form = ProductStock::find($request->id);
                $action = route('stocks.manage.edit',  ['id' => $request->id]);
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('stocks.product_stock.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if($request->isMethod('post')){
                $request->validate([
                    'id'               => 'required|integer',
                    'warehouse_id'     => 'required|integer',
                    'respon_person_id' => 'required|integer',
                    'products'         => 'required|array',
                ]);

                foreach ($request->products as $product) {

                    ProductStock::updateOrCreate(
                        [
                            'product_id'   => $product['product_id'],
                            'warehouse_id' => $request->warehouse_id,
                        ],
                        [
                            'respon_person_id' => $request->respon_person_id,
                            'stock'            => (int) $product['qty'],
                            'alert_quantity'   => (int) $product['alert_qty'],
                            'updated_by'       => Auth::id(),
                        ]
                    );
                }

                return response()->json([
                    'status'   => 'success',
                    'message'  => __('messages.update_stock_success'),
                    'redirect' => route('stocks.manage.index'),
                    'modal'    => 'action-modal',
                ]);
            }

            return response()->json([
                'status'  => 'error',
                'message' => __('messages.405'),
            ]);
            
        } catch(\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function delete(Request $request)
    {
        try {

            $form = ProductStock::find($request->id);

            if (!$form) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Stock not found',
                ], 404);
            }

            $form->delete();

            return response()->json([
                'status'  => 'success',
                'message' => __('messages.delete_stock_success'),
                'redirect' => route('stocks.manage.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
