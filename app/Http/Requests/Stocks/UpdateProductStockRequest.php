<?php

namespace App\Http\Requests\Stocks;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'                       => 'required|integer|exists:product_stocks,id',
            'warehouse_id'             => 'required|integer|exists:warehouses,id',
            'respon_person_id'         => 'required|integer|exists:users,id',
            'products'                 => 'required|array|min:1',
            'products.*.product_id'    => 'required|integer|exists:products,id',
            'products.*.qty'           => 'required|integer|min:0',
            'products.*.alert_qty'     => 'required|integer|min:0',
        ];
    }
}