<?php

namespace App\Http\Requests\Stocks;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference_no' => 'nullable|string',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'branch_id'    => 'required|integer|exists:branches,id',
            'date'         => 'required|date',
            'note'         => 'nullable|string',
            'document'     => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',

            'products'                      => 'nullable|array',
            'products.*.id'                => 'nullable|integer|exists:adjustment_items,id',
            'products.*.product_id'        => 'required_with:products|integer|exists:products,id',
            'products.*.product_unit_id'   => 'nullable|integer|exists:units,id',
            'products.*.product_unit_code' => 'nullable|string|max:50',

            'items'                      => 'nullable|array',
            'items.*.id'                => 'nullable|integer|exists:adjustment_items,id',
            'items.*.product_id'        => 'required_with:items|integer|exists:products,id',
            'items.*.product_unit_id'   => 'nullable|integer|exists:units,id',
            'items.*.product_unit_code' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'products.required_without' => 'Please add at least one product.',
        ];
    }
}