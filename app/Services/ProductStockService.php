<?php

namespace App\Services;

use App\Models\Stocks\ProductStock;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;

class ProductStockService extends BaseService
{
    protected function getQuery()
    {
        return ProductStock::query();
    }

    public function storeBulk(array $data): void
    {
        foreach ($data['products'] as $product) {
            $exists = ProductStock::where('warehouse_id', $data['warehouse_id'])
                ->where('product_id', $product['product_id'])
                ->exists();

            if (!$exists) {
                ProductStock::create([
                    'warehouse_id'     => $data['warehouse_id'],
                    'respon_person_id' => $data['respon_person_id'],
                    'product_id'       => $product['product_id'],
                    'stock'            => $product['qty'],
                    'alert_quantity'   => $product['alert_qty'],
                    'created_by'       => Auth::id(),
                ]);
            }
        }
    }

    public function updateBulk(array $data): void
    {
        foreach ($data['products'] as $product) {
            ProductStock::updateOrCreate(
                [
                    'product_id'   => $product['product_id'],
                    'warehouse_id' => $data['warehouse_id'],
                ],
                [
                    'respon_person_id' => $data['respon_person_id'],
                    'stock'            => (int) $product['qty'],
                    'alert_quantity'   => (int) $product['alert_qty'],
                    'updated_by'       => Auth::id(),
                ]
            );
        }
    }
}