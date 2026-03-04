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
            'gps_tracking' => ['boolean', 'nullable'],
            'adr_enabled' => ['boolean', 'nullable'],
            'pallet_exchange' => ['boolean', 'nullable'],
            'payment_terms_days' => ['required', 'integer', 'min:0'],
            'company_registration_number' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'full_address' => ['nullable', 'string'],
            'fleet_tauliner_count' => ['nullable', 'integer', 'min:0'],
            'fleet_mega_count' => ['nullable', 'integer', 'min:0'],
            'fleet_frigo_count' => ['nullable', 'integer', 'min:0'],
            'fleet_double_deck_count' => ['nullable', 'integer', 'min:0'],
            'adr_classes' => ['nullable', 'string', 'max:255'],
            'xl_certification' => ['boolean', 'nullable'],
            'contact_traffic_email' => ['nullable', 'email', 'max:255'],
            'contact_traffic_phone' => ['nullable', 'string', 'max:50'],
            'contact_admin_email' => ['nullable', 'email', 'max:255'],
            'contact_admin_phone' => ['nullable', 'string', 'max:50'],
            'contact_sales_email' => ['nullable', 'email', 'max:255'],
            'contact_sales_phone' => ['nullable', 'string', 'max:50'],
            'preferred_destinations' => ['nullable', 'array'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_address' => ['nullable', 'string'],
            'bank_iban' => ['nullable', 'string', 'max:255'],
            'bank_swift' => ['nullable', 'string', 'max:255'],
            'accept_e_invoicing' => ['boolean', 'nullable'],

            // files
            'doc_company_registration' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'doc_cmr_insurance' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'doc_transport_license' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'doc_bank_certificate' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'doc_tax_residence' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
