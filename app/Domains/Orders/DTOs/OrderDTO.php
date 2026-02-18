<?php

namespace App\Domains\Orders\DTOs;

readonly class OrderDTO
{
    public function __construct(
        public int $client_id,
        public int $user_id,
        public string $order_number,
        public array $locations, // Array of OrderLocationDTOs or arrays
        public ?float $total_amount = null,
        public ?int $carrier_id = null,
        public ?\App\Shared\Enums\OrderStatus $status = null,
        public ?\App\Shared\Enums\PaymentStatus $payment_status = null,
    ) {
    }

    public static function fromRequest($request, int $userId): self
    {
        return new self(
            client_id: $request->validated('client_id'),
            user_id: $userId,
            order_number: $request->validated('order_number'),
            locations: $request->validated('locations') ?? [],
            total_amount: $request->validated('total_amount'),
            carrier_id: $request->validated('carrier_id'),
            status: $request->has('status') ? \App\Shared\Enums\OrderStatus::tryFrom($request->validated('status')) : null,
            payment_status: $request->has('payment_status') ? \App\Shared\Enums\PaymentStatus::tryFrom($request->validated('payment_status')) : null,
        );
    }
}
