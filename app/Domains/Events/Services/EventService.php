<?php

namespace App\Domains\Events\Services;

use App\Domains\Events\Models\Event;
use App\Domains\Orders\Models\Order;
use App\Shared\Enums\EventType;
use App\Shared\Enums\OrderStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function createEvent(array $data): Event
    {
        return DB::transaction(function () use ($data) {
            $event = Event::create([
                'uuid' => Str::uuid(),
                'order_id' => $data['order_id'],
                'user_id' => $data['user_id'],
                'type' => $data['type'],
                'description' => $data['description'] ?? null,
                'location' => $data['location'] ?? null,
                'meta_data' => $data['meta_data'] ?? null,
            ]);

            $this->syncOrderStatus($event);

            return $event;
        });
    }

    protected function syncOrderStatus(Event $event): void
    {
        $order = $event->order;

        $newStatus = match ($event->type) {
            EventType::TRUCK_ASSIGNED => OrderStatus::ASSIGNED,
            EventType::DEPARTED => OrderStatus::IN_TRANSIT,
            EventType::ARRIVED_UNLOAD => OrderStatus::IN_TRANSIT, // Still in transit or close
            EventType::UNLOADED => OrderStatus::DELIVERED,
            EventType::INCIDENT => OrderStatus::INCIDENT,
            default => null,
        };

        if ($newStatus) {
            $order->update(['status' => $newStatus]);
        }
    }
}
