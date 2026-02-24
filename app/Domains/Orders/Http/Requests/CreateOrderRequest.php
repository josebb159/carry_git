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
        $user = auth()->user();
        $isClient = $user && ($user->hasRole('user') || $user->hasRole('merchant'));

        return [
            'client_id' => ['required', 'exists:clients,id'],
            'carrier_id' => ['nullable', 'exists:carriers,id'],
            'order_number' => ['required', 'string', 'max:255', 'unique:orders'],
            'status' => [$isClient ? 'nullable' : 'required', Rule::enum(OrderStatus::class)],
            'payment_status' => [$isClient ? 'nullable' : 'required', Rule::enum(PaymentStatus::class)],
            'total_amount' => [$isClient ? 'nullable' : 'required', 'numeric', 'min:0'],
            'cargo_type' => ['nullable', 'string', 'max:255'],
            'temperature' => ['nullable', 'string', 'max:255'],
            'request_cmr' => ['nullable', 'boolean'],
            'request_delivery_note' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
            'logistics_contact_name' => ['nullable', 'string', 'max:255'],
            'logistics_contact_email' => ['nullable', 'email', 'max:255'],
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
