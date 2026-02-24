<?php

namespace App\Domains\Orders\Services;

use App\Domains\Orders\DTOs\OrderDTO;
use App\Domains\Orders\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function createOrder(OrderDTO $dto): Order
    {
        return DB::transaction(function () use ($dto) {
            $order = Order::create([
                'uuid' => Str::uuid(),
                'client_id' => $dto->client_id,
                'user_id' => $dto->user_id,
                'carrier_id' => $dto->carrier_id,
                'order_number' => $dto->order_number,
                'cargo_type' => $dto->cargo_type,
                'temperature' => $dto->temperature,
                'request_cmr' => $dto->request_cmr,
                'request_delivery_note' => $dto->request_delivery_note,
                'total_amount' => $dto->total_amount ?? 0,
                'status' => $dto->status ?? \App\Shared\Enums\OrderStatus::PENDING,
                'payment_status' => $dto->payment_status ?? \App\Shared\Enums\PaymentStatus::PENDING,
                'notes' => $dto->notes,
                'logistics_contact_name' => $dto->logistics_contact_name,
                'logistics_contact_email' => $dto->logistics_contact_email,
            ]);

            foreach ($dto->locations as $locationData) {
                $order->locations()->create($locationData);
            }

            // Internal Notification via Event model for Admin/Logistics
            \App\Domains\Events\Models\Event::create([
                'uuid' => (string)\Illuminate\Support\Str::uuid(),
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'type' => \App\Shared\Enums\EventType::REQUEST_CREATED, // Internal request alert
                'description' => "Nueva solicitud de transporte pendiente: {$order->order_number}",
                'meta_data' => [
                    'source' => 'client_dashboard',
                    'action' => 'review_required'
                ]
            ]);

            return $order->load('locations', 'client');
        });
    }
}
