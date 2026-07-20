<?php

namespace App\Http\Requests\Other;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'rate' => 'required|string|max:100',
        ];
    }
}