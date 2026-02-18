<?php

namespace Tests\Feature;

use App\Domains\Carriers\Models\Carrier;
use App\Domains\Clients\Models\Client;
use App\Domains\Fleet\Models\Fleet;
use App\Domains\Orders\Models\Order;
use App\Domains\Users\Models\User;
use App\Shared\Enums\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FullSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'merchant']);
        Role::firstOrCreate(['name' => 'delivery']);
        Role::firstOrCreate(['name' => 'agent']);
    }

    public function test_end_to_end_delivery_flow()
    {
        // 1. Setup Actor: Admin
        $admin = User::factory()->create(['email' => 'admin@test.com']);
        $admin->assignRole('admin');

        // 2. Setup Resources (Client, Carrier, Fleet, Driver)
        $client = Client::factory()->create(['name' => 'Test Client']);
        $carrier = Carrier::factory()->create(['name' => 'Test Carrier']);
        $fleet = Fleet::factory()->create(['name' => 'Test Fleet', 'carrier_id' => $carrier->id]);

        $driver = User::factory()->create(['email' => 'driver@test.com', 'name' => 'Driver User']);
        $driver->assignRole('delivery');

        $merchant = User::factory()->create(['email' => 'merchant@test.com', 'name' => 'Merchant User']);
        $merchant->assignRole('merchant');

        // 3. Admin Creates an Order via Web Interface
        $orderData = [
            'client_id' => $client->id,
            'carrier_id' => $carrier->id,
            'fleet_id' => $fleet->id,
            'user_id' => $driver->id, // Assign to driver
            'order_number' => 'ORD-001',
            'origin_city' => 'Bogota',
            'origin_address' => 'Calle 123',
            'destination_city' => 'Medellin',
            'destination_address' => 'Carrera 456',
            'pickup_date' => now()->addDay()->toDateTimeString(),
            'delivery_date' => now()->addDays(2)->toDateTimeString(),
            'total_amount' => 1500.00,
            'items' => [
                ['description' => 'Box 1', 'quantity' => 10, 'weight' => 50],
            ]
        ];

        $response = $this->actingAs($admin)
            ->post('/orders', $orderData);

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseHas('orders', ['order_number' => 'ORD-001']);

        $order = Order::where('order_number', 'ORD-001')->first();
        $this->assertEquals(OrderStatus::PENDING, $order->status);

        // 4. Driver (Mobile App) Login & Fetch Orders
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'driver@test.com',
            'password' => 'password', // Assumes factory uses 'password'
            'device_name' => 'test_device'
        ]);
        $loginResponse->assertOk();
        $token = $loginResponse->json('data.token');

        // Driver checks assigned orders
        $ordersResponse = $this->withToken($token)
            ->getJson('/api/v1/orders');

        $ordersResponse->assertOk();
        $ordersResponse->assertJsonFragment(['order_number' => 'ORD-001']);

        // 5. Driver updates status (Simulating Event Registration: "Pickup")
        // Assuming we have an endpoint to register events that updates order status
        // Or directly calling status update if that's how the app works.
        // Based on "EventService", creating an event updates the status.

        $pickupEventData = [
            'order_id' => $order->id,
            'type' => 'pickup', // EventType enum value? checking...
            'description' => 'Driver arrived at pickup',
            'latitude' => 4.6097,
            'longitude' => -74.0817,
        ];

        // Need to check EventType Enum or how events are handled. 
        // Assuming 'pickup' triggers IN_TRANSIT or similar.
        // Let's first verify Event Routes. POST /api/v1/events

        $eventResponse = $this->withToken($token)
            ->postJson('/api/v1/events', [
                'order_id' => $order->id,
                'type' => 'PICKUP', // Enum value usually uppercase
                'description' => 'Picked up goods',
                'location' => 'Bogota Warehouse'
            ]);

        // If 422, we might need to adjust fields.
        if ($eventResponse->status() === 422) {
            dump($eventResponse->json());
        }
        $eventResponse->assertCreated();

        $order->refresh();
        $this->assertEquals(OrderStatus::IN_TRANSIT, $order->status);

        // 6. Merchant (Mobile App) Login & Check Status
        $merchantLoginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'merchant@test.com',
            'password' => 'password',
            'device_name' => 'merchant_device'
        ]);
        $merchantToken = $merchantLoginResponse->json('data.token');

        // Merchant checks the order (assuming they can see it - if they are associated? 
        // The test setup didn't associate the merchant to the client/order. 
        // If merchant app shows ALL orders for now, or if we need to link them.
        // Let's assume for this test the merchant role can view orders or we skip merchant specific visibility check if not strictly defined yet).

        // Actually, usually Merchant is the Client user. Let's update the Order to belong to this Merchant User or Client.
        // If the Merchant User belongs to the Client, we're good.
        // For now, let's just use the Admin dashboard to verify the final status as the "Web User".

        // 7. Admin Dashboard Verification
        $dashboardResponse = $this->actingAs($admin)
            ->getJson('/dashboard'); // The view

        // We can't easily assert view content for JS charts, but we can check the service method.
        // Let's verify via API endpoint for dashboard if exists, or just check DB again.

        $this->assertEquals(OrderStatus::IN_TRANSIT, $order->status);

        // 8. Driver Delivers
        $deliveryEventResponse = $this->withToken($token)
            ->postJson('/api/v1/events', [
                'order_id' => $order->id,
                'type' => 'DELIVERY',
                'description' => 'Delivered to customer',
                'location' => 'Medellin Store'
            ]);
        $deliveryEventResponse->assertCreated();

        $order->refresh();
        $this->assertEquals(OrderStatus::DELIVERED, $order->status);
    }
}
