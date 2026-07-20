<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitConvertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unit_from_id' => 'required|exists:units,id',
            'unit_to_id' => 'required|exists:units,id|different:unit_from_id',
            'numerator' => 'required|numeric|min:0',
            'operator' => 'required|string|max:2',
            'name' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }
}