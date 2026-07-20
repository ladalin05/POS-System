<?php

namespace App\Http\Requests\People;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'          => 'required|string|max:255',
            'company'       => 'required|string|max:255',
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string|max:50',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:255',
            'state'         => 'required|string|max:255',
            'email_address' => 'required|email|max:255',
            'vat_number'    => 'required|string|max:100',
            'postal_code'   => 'required|string|max:50',
            'country'       => 'required|string|max:100',
        ];
    }
}