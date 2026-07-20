<?php

namespace App\Http\Requests\Pos;

use Illuminate\Foundation\Http\FormRequest;

class HoldOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'warehouse_id'       => 'nullable|integer',
            'customer_id'        => 'nullable|integer|exists:customers,id',
            'biller_id'          => 'nullable|integer',
            'discount'           => 'nullable|numeric|min:0',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.qty'        => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.unit_id'    => 'nullable|integer',
        ];
    }

    /**
     * Custom messages for validator errors (optional, tweak as needed).
     */
    public function messages(): array
    {
        return [
            'items.required' => 'Cannot hold an empty order. Add at least one item.',
        ];
    }
}