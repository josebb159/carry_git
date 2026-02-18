<?php

namespace Tests\Feature\Dashboard;

use App\Domains\Users\Models\User;
use App\Domains\Orders\Models\Order;
use App\Domains\Billing\Models\Invoice;
use App\Domains\Events\Models\Event;
use App\Domains\Clients\Models\Client;
use App\Shared\Enums\OrderStatus;
use App\Shared\Enums\InvoiceStatus;
use App\Shared\Enums\EventType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_retrieve_dashboard_stats()
    {
        $user = User::factory()->create();
        $client = Client::create([
            'legal_name' => 'Dash Client',
            'vat_number' => 'VAT-DASH-' . time(),
            'country' => 'ES',
        ]);

        // Seed Orders
        Order::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'client_id' => $client->id,
            'user_id' => $user->id,
            'order_number' => 'ORD-1',
            'status' => OrderStatus::PENDING,
            'total_amount' => 100,
        ]);
        Order::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'client_id' => $client->id,
            'user_id' => $user->id,
            'order_number' => 'ORD-2',
            'status' => OrderStatus::DELIVERED,
            'total_amount' => 200,
        ]);

        // Seed Invoices
        Invoice::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'client_id' => $client->id,
            'invoice_number' => 'INV-1',
            'date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => 100,
            'tax' => 21,
            'total' => 121,
            'status' => InvoiceStatus::PAID,
        ]);
        Invoice::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'client_id' => $client->id,
            'invoice_number' => 'INV-2',
            'date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => 200,
            'tax' => 42,
            'total' => 242,
            'status' => InvoiceStatus::SENT,
        ]);

        // Seed Events (for recent activity)
        $order = Order::first();
        Event::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'order_id' => $order->id,
            'user_id' => $user->id,
            'type' => EventType::TRUCK_ASSIGNED,
            'description' => 'Truck assigned',
        ]);

        $response = $this->actingAs($user)->getJson('/api/v1/dashboard/stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'orders',
                    'revenue',
                    'recent_events',
                ],
            ]);

        $data = $response->json('data');

        // Verify Order Counts
        $this->assertEquals(1, $data['orders']['pending']);
        $this->assertEquals(1, $data['orders']['delivered']);
        $this->assertEquals(0, $data['orders']['cancelled']);

        // Verify Revenue
        $this->assertEquals(121, $data['revenue']['total_collected']);
        $this->assertEquals(242, $data['revenue']['pending_collection']);

        // Verify Recent Events
        $this->assertCount(1, $data['recent_events']);
        $this->assertEquals(EventType::TRUCK_ASSIGNED->value, $data['recent_events'][0]['type']);
    }
}
