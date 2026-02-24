<?php

namespace App\Domains\Clients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Activity Segmentation
            'activity_category' => ['required', 'string', 'in:Productor,Operador Logístico,Agricultor,Distribuidor,Transportista,Otros'],

            // Fiscal Data
            'legal_name' => ['required', 'string', 'max:255'],
            'vat_number' => ['required', 'string', 'max:50', 'unique:clients'],
            'commercial_registry_number' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string'],

            // Official Address
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'zip_code' => ['required', 'string'],

            // Correspondence Address
            'shipping_address' => ['nullable', 'string'],
            'shipping_city' => ['nullable', 'string'],
            'shipping_country' => ['nullable', 'string'],
            'shipping_zip_code' => ['nullable', 'string'],
            'shipping_email' => ['nullable', 'email'],

            // Contacts
            'billing_contact_name' => ['nullable', 'string'],
            'billing_contact_phone' => ['nullable', 'string'],
            'billing_contact_email' => ['nullable', 'email'],

            'logistics_contact_name' => ['nullable', 'string'],
            'logistics_contact_role' => ['nullable', 'string'],
            'logistics_contact_phone' => ['nullable', 'string'],
            'logistics_contact_email' => ['nullable', 'email'],

            'emergency_contact_name' => ['nullable', 'string'],
            'emergency_contact_phone' => ['nullable', 'string'],
            'emergency_contact_email' => ['nullable', 'email'],

            // Finance
            'payment_terms_days' => ['required', 'integer', 'min:0'],
            'payment_conditions' => ['nullable', 'string'],
            'billing_procedure' => ['nullable', 'string', 'in:Factura por viaje,Autofacturación,Otros'],

            // Documents
            'doc_invoice_email' => ['boolean'],
            'doc_invoice_postal' => ['boolean'],
            'doc_cmr_email' => ['boolean'],
            'doc_cmr_postal' => ['boolean'],
            'doc_delivery_note_email' => ['boolean'],
            'doc_delivery_note_postal' => ['boolean'],
            'doc_temp_report_email' => ['boolean'],
            'doc_temp_report_postal' => ['boolean'],

            // Signature
            'filled_by_name' => ['required', 'string'],
            'filled_by_role' => ['required', 'string'],
            'filled_by_phone' => ['required', 'string'],
            'filled_by_date' => ['required', 'date'],
        ];
    }
}
