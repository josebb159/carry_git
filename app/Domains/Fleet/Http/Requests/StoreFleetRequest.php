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
            'total_owned_trucks' => ['nullable', 'integer', 'min:0'],
            'three_axle_trucks' => ['nullable', 'integer', 'min:0'],
            'tauliner_trucks' => ['nullable', 'integer', 'min:0'],
            'container_chassis' => ['nullable', 'integer', 'min:0'],
            'mega_trailers' => ['nullable', 'integer', 'min:0'],
            'frigo_trucks' => ['nullable', 'integer', 'min:0'],
            'frigo_bitemp_trucks' => ['nullable', 'integer', 'min:0'],
            'double_deck_trucks' => ['nullable', 'integer', 'min:0'],
            'preferred_destinations' => ['nullable', 'array'],
            'adr_enabled' => ['boolean', 'nullable'],
            'adr_classes' => ['nullable', 'string', 'max:255'],
            'pallet_exchange' => ['boolean', 'nullable'],
            'gps_tracking' => ['boolean', 'nullable'],
            'subcontractors_trucks' => ['boolean', 'nullable'],
            'double_driver' => ['boolean', 'nullable'],
            'multimodal_solutions' => ['boolean', 'nullable'],
            'partial_loads' => ['boolean', 'nullable'],
        ];
    }
}
