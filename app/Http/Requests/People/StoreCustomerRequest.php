<?php

namespace App\Http\Requests\People;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_group_id' => 'required|exists:group_customers,id',
            'code'              => 'required|string|max:255',
            'company'           => 'required|string|max:255',
            'name'              => 'required|string|max:255',
            'phone'             => 'required|string|max:50',
            'address'           => 'required|string|max:255',
            'city'              => 'nullable|string|max:255',
            'state'             => 'nullable|string|max:255',
            'email_address'     => 'nullable|email|max:255',
            'vat_number'        => 'nullable|string|max:100',
            'postal_code'       => 'nullable|string|max:50',
            'country'           => 'nullable|string|max:100',
            'credit_day'        => 'nullable|integer',
            'credit_amount'     => 'nullable|numeric',
            // 'price_group_id' => 'nullable|exists:price_groups,id',
            // 'salesman_id'    => 'nullable|exists:users,id',
            'attachment'        => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
        ];
    }
}