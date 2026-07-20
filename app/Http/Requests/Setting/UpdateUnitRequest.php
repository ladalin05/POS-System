<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:55',
            'name' => 'required|string|max:55',
            'operator' => 'nullable|string|max:1',
            'unit_value' => 'nullable|string|max:55',
            'operation_value' => 'nullable|string|max:55',
            'base_unit' => 'nullable|exists:units,id',
        ];
    }
}