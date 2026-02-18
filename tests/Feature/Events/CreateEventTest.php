<?php

namespace Tests\Feature\Events;

use App\Domains\Users\Models\User;
use App\Domains\Orders\Models\Order;
use App\Domains\Clients\Models\Client;
use App\Shared\Enums\EventType;
use App\Shared\Enums\OrderStatus;
use Tests\TestCase;

class CreateEventTest extends TestCase
{
    public function test_can_create_event_and_updates_order_status()
    {
        $user = User::factory()->create();
        $client = Client::create([
            'legal_name' => 'Test Client',
            'vat_number' => 'VAT' . time(),
            'country' => 'ES'
        ]);

        $order = Order::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'client_id' => $client->id,
            'user_id' => $user->id,
            'order_number' => 'ORD-TEST-' . time(),
            'status' => OrderStatus::PENDING,
            'total_amount' => 100,
        ]);

        $response = $this->actingAs($user)->postJson("/api/v1/orders/{$order->uuid}/events", [
            'type' => EventType::DEPARTED->value,
            'description' => 'Truck departed from warehouse',
            'location' => ['lat' => 40.4168, 'lng' => -3.7038],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.event.type', EventType::DEPARTED->value)
            ->assertJsonPath('data.order_status', OrderStatus::IN_TRANSIT->value);

        $this->assertDatabaseHas('events', [
            'order_id' => $order->id,
            'type' => EventType::DEPARTED->value,
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::IN_TRANSIT,
        ]);
    }

    public function test_create_event_validation_errors()
    {
        $user = User::factory()->create();
        $client = Client::create([
            'legal_name' => 'Test Client 2',
            'vat_number' => 'VAT2' . time(),
            'country' => 'ES'
        ]);

        $order = Order::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'client_id' => $client->id,
            'user_id' => $user->id,
            'order_number' => 'ORD-TEST-2-' . time(),
            'status' => OrderStatus::PENDING,
        ]);

        $response = $this->actingAs($user)->postJson("/api/v1/orders/{$order->uuid}/events", [
            'type' => 'INVALID_TYPE',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    }
}
