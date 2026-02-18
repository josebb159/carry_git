<?php

namespace Tests\Feature\Orders;

use App\Domains\Users\Models\User;
use App\Domains\Clients\Models\Client;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    // use RefreshDatabase;

    public function test_authenticated_user_can_create_order()
    {
        $user = User::factory()->create();
        $client = Client::create([
            'legal_name' => 'Test Client',
            'vat_number' => 'VAT' . time(),
            'country' => 'ES',
            'payment_terms_days' => 30,
        ]);

        $response = $this->actingAs($user)->postJson('/api/v1/orders', [
            'client_id' => $client->id,
            'total_amount' => 1500.00,
            'locations' => [
                [
                    'type' => 'pickup',
                    'address' => 'Warehouse A',
                    'city' => 'Madrid',
                    'country' => 'ES',
                    'scheduled_at' => now()->addDays(1)->toIso8601String(),
                ],
                [
                    'type' => 'delivery',
                    'address' => 'Store B',
                    'city' => 'Barcelona',
                    'country' => 'ES',
                    'scheduled_at' => now()->addDays(2)->toIso8601String(),
                ],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'uuid',
                    'order_number',
                    'status',
                    'locations',
                ],
            ]);

        $this->assertDatabaseHas('orders', [
            'client_id' => $client->id,
            'user_id' => $user->id,
            'total_amount' => 1500.00,
        ]);

        $this->assertDatabaseCount('order_locations', 2);
    }

    public function test_create_order_validation_errors()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/orders', [
            // Missing required fields
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['client_id', 'locations']);
    }
}
