<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'promotion' => $this->boolean('promotion'),
        ]);
    }

    public function rules(): array
    {
        return [
            'product_type' => ['required', Rule::in(['Standard', 'Service'])],
            'name'         => ['required', 'string', 'max:255'],
            'product_code' => ['required', 'string', 'max:255', Rule::unique('products', 'code')],
            'category_id'  => ['required', 'integer', 'exists:categories,id'],
            'price'        => ['required', 'numeric', 'min:0'],
            'cost'         => ['nullable', 'numeric', 'min:0'],

            'unit_id'         => ['required_if:product_type,Standard', 'nullable', 'integer', 'exists:units,id'],
            'sale_unit'       => ['nullable', 'integer', 'exists:units,id'],
            'purchase_unit'   => ['nullable', 'integer', 'exists:units,id'],
            'alert_quantity'  => ['nullable', 'numeric', 'min:0'],
            'brand'           => ['required'],

            'image'           => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'product_details' => ['nullable', 'string'],

            // Promotion
            'promotion'   => ['nullable', 'boolean'],
            'promo_price' => ['nullable', 'numeric', 'min:0'],
            'promo_qty'   => ['nullable'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],

            // Unit grid
            'product_units'           => ['nullable', 'array'],
            'product_units.*.unit_id' => ['nullable', 'integer', 'exists:units,id'],
            'product_units.*.qty'     => ['nullable', 'numeric', 'min:0'],
            'product_units.*.price'   => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_code.unique' => 'This product code is already in use.',
        ];
    }
}