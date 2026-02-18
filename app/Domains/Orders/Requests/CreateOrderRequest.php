<?php

namespace App\Domains\Orders\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'locations' => ['required', 'array', 'min:2'], // At least pickup and delivery
            'locations.*.type' => ['required', 'string', Rule::in(['pickup', 'delivery'])],
            'locations.*.address' => ['required', 'string'],
            'locations.*.city' => ['required', 'string'],
            'locations.*.country' => ['required', 'string'],
            'locations.*.scheduled_at' => ['nullable', 'date'],
        ];
    }
}
