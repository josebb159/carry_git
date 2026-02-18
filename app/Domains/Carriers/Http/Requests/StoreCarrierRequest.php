<?php

namespace App\Domains\Carriers\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarrierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:carriers'],
            'vat_number' => ['required', 'string', 'max:50', 'unique:carriers'],
            'transport_license' => ['nullable', 'string', 'max:100'],
            'insurance_policy' => ['nullable', 'string', 'max:100'],
            'gps_tracking' => ['boolean'],
            'adr_enabled' => ['boolean'],
            'pallet_exchange' => ['boolean'],
            'payment_terms_days' => ['required', 'integer', 'min:0'],
        ];
    }
}
