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
            'legal_name' => ['required', 'string', 'max:255'],
            'vat_number' => ['required', 'string', 'max:50', 'unique:clients'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'zip_code' => ['required', 'string'],
            'country' => ['required', 'string'],
            'payment_terms' => ['required', 'integer', 'min:0'],
        ];
    }
}
