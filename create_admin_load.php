<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Users\Models\User;
use App\Domains\Clients\Models\Client;
use App\Domains\Orders\Models\Order;
use App\Domains\Orders\Services\OrderService;
use App\Domains\Orders\DTOs\OrderDTO;
use Illuminate\Support\Facades\Auth;

try {
    $admin = User::role('admin')->first();
    Auth::login($admin);

    $client = Client::first();
    if (!$client) {
        $client = Client::create([
            'user_id' => $admin->id,
            'name' => 'Demo Client',
            'email' => 'client@carri.com',
            'phone' => '123456789',
            'address' => 'Demo Address',
        ]);
    }

    $driver = User::role('delivery')->first();
    if (!$driver) {
        throw new \Exception("No driver found. Run seeders.");
    }

    // Prepare Request Data Array
    $dataArray = [
        'client_id' => $client->id,
        'order_number' => 'ORD-' . strtoupper(uniqid()),
        'status' => 'pending',
        'payment_status' => 'pending',
        'total_amount' => 150.00,
        'cargo_type' => 'Boxes',
        'temperature' => 'Ambient',
        'request_cmr' => false,
        'request_delivery_note' => false,
        'notes' => 'Test admin order programmatically',
        'locations' => [
            [
                'type' => 'pickup',
                'address' => 'Test Pickup Address, Madrid, Spain',
                'city' => 'Madrid',
                'state' => 'Madrid',
                'zip_code' => '28001',
                'country' => 'Spain',
                'sequence' => 1,
            ],
            [
                'type' => 'delivery',
                'address' => 'Test Delivery Address, Barcelona, Spain',
                'city' => 'Barcelona',
                'state' => 'Catalonia',
                'zip_code' => '08001',
                'country' => 'Spain',
                'sequence' => 2,
            ]
        ]
    ];


    // Use form request trick to validate and parse correctly
    $request = \App\Domains\Orders\Http\Requests\CreateOrderRequest::create('/orders', 'POST', $dataArray);
    $request->merge($dataArray);
    $request->setContainer($app);
    $request->validateResolved();

    // Setup DTO and call Service
    $dto = OrderDTO::fromRequest($request, $admin->id);
    $service = app(OrderService::class);
    $order = $service->createOrder($dto);

    echo "Order Created Successfully: UUID: " . $order->uuid . "\n";
    
    // Now how to assign driver?
    echo "To assign driver, check what relation it uses (carrier_id or fleet_id)\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
