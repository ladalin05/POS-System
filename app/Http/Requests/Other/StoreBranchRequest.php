<?php

namespace App\Http\Requests\Other;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'name_kh'        => 'nullable|string|max:255',
            'phone'          => 'required|string|max:100',
            'phone_kh'       => 'nullable|string|max:100',
            'address'        => 'required|string|max:1000',
            'address_kh'     => 'nullable|string|max:1000',
            'city'           => 'required|string|max:120',
            'city_kh'        => 'nullable|string|max:120',
            'country'        => 'nullable|string|max:120',
            'country_kh'     => 'nullable|string|max:120',
            'vat_number'     => 'nullable|string|max:120',
            'vat_number_kh'  => 'nullable|string|max:120',
            'email'          => 'required|email|max:255',
            'prefix'         => 'nullable|string|max:50',
            // 'default_cash' => 'required|string|in:Cash,Card,Bank',
            'working_day'    => 'nullable|integer|min:0',
            'invoice_footer' => 'nullable|string|max:2000',
            'logo'           => 'nullable|image|max:2048',
        ];
    }
}