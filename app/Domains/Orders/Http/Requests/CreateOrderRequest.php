<?php

namespace App\Domains\Orders\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Shared\Enums\OrderStatus;
use App\Shared\Enums\PaymentStatus;

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
            'carrier_id' => ['nullable', 'exists:carriers,id'],
            'order_number' => ['required', 'string', 'max:255', 'unique:orders'],
            'status' => ['required', Rule::enum(OrderStatus::class)],
            'payment_status' => ['required', Rule::enum(PaymentStatus::class)],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'locations' => ['required', 'array', 'min:2'], // At least pickup and delivery
            'locations.*.type' => ['required', 'string', 'in:pickup,delivery,stop'],
            'locations.*.address' => ['required', 'string'],
            'locations.*.city' => ['required', 'string'],
            'locations.*.state' => ['required', 'string'],
            'locations.*.zip_code' => ['required', 'string'],
            'locations.*.country' => ['nullable', 'string'],
            'locations.*.sequence' => ['required', 'integer'],
            'locations.*.scheduled_at' => ['nullable', 'date'],
        ];
    }
}
