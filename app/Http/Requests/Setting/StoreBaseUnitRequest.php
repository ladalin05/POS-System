<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class StoreBaseUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from_unit_id' => 'required|exists:units,id',
            'to_unit_id' => 'required|exists:units,id|different:from_unit_id',
            'numerator' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ];
    }
}