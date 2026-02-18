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
                'total_amount' => $dto->total_amount,
                'status' => $dto->status ?? \App\Shared\Enums\OrderStatus::PENDING,
                'payment_status' => $dto->payment_status ?? \App\Shared\Enums\PaymentStatus::PENDING,
            ]);

            foreach ($dto->locations as $locationData) {
                $order->locations()->create($locationData);
            }

            return $order->load('locations', 'client');
        });
    }
}
