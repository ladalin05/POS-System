<?php

namespace App\Http\Requests\Other;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'      => 'required|string|max:255',
            'branch_id' => 'required|integer|exists:branches,id',
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:100',
            'address'   => 'required|string|max:1000',
            'email'     => 'required|email|max:255',
        ];
    }
}