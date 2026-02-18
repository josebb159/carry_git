<?php

namespace App\Domains\Fleet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFleetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'carrier_id' => ['required', 'exists:carriers,id'],
            'truck_type' => ['required', 'string', 'max:50'],
            'refrigeration_type' => ['nullable', 'string', 'max:50'],
            'capacity_cbm' => ['nullable', 'numeric', 'min:0'],
            'double_driver' => ['boolean'],
        ];
    }
}
