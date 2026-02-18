<?php

namespace App\Domains\Orders\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Orders\Requests\CreateOrderRequest;
use App\Domains\Orders\DTOs\OrderDTO;
use App\Domains\Orders\Services\OrderService;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected OrderService $orderService
    ) {
    }

    public function store(CreateOrderRequest $request): JsonResponse
    {
        $dto = OrderDTO::fromRequest($request, $request->user()->id);
        $order = $this->orderService->createOrder($dto);

        return $this->successResponse($order, 'Order created successfully', 201);
    }

    // Placeholder for index, show, etc.
    public function index()
    {
        return response()->json([
            'data' => [
                [
                    'id' => 1,
                    'orderNumber' => 'ORD-001',
                    'status' => 'ASSIGNED',
                    'clientName' => 'Amazon Inc.',
                    'originCity' => 'New York',
                    'destinationCity' => 'Boston',
                    'originAddress' => '123 Warehouse Blvd, NY',
                    'destinationAddress' => '456 Distribution Dr, MA',
                    'pickupDate' => now()->addDays(1)->toIso8601String(),
                    'deliveryDate' => now()->addDays(2)->toIso8601String(),
                    'temperatureRequirements' => 'keep_frozen',
                    'pallets' => 5,
                    'price' => 150.00,
                ],
                [
                    'id' => 2,
                    'orderNumber' => 'ORD-002',
                    'status' => 'IN_TRANSIT',
                    'clientName' => 'Walmart',
                    'originCity' => 'Chicago',
                    'destinationCity' => 'Detroit',
                    'originAddress' => '789 Supply St, IL',
                    'destinationAddress' => '101 Store Ln, MI',
                    'pickupDate' => now()->subDays(1)->toIso8601String(),
                    'deliveryDate' => now()->addDays(1)->toIso8601String(),
                    'temperatureRequirements' => null,
                    'pallets' => 12,
                    'price' => 300.50,
                ],
                [
                    'id' => 3,
                    'orderNumber' => 'ORD-003',
                    'status' => 'DELIVERED',
                    'clientName' => 'Target',
                    'originCity' => 'Los Angeles',
                    'destinationCity' => 'San Diego',
                    'originAddress' => '222 Sunset Blvd, CA',
                    'destinationAddress' => '333 Ocean Dr, CA',
                    'pickupDate' => now()->subDays(2)->toIso8601String(),
                    'deliveryDate' => now()->subDays(1)->toIso8601String(),
                    'temperatureRequirements' => 'keep_dry',
                    'pallets' => 2,
                    'price' => 120.00,
                ],
            ]
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'id' => (int) $id,
            'orderNumber' => 'ORD-00' . $id,
            'status' => $id == 1 ? 'ASSIGNED' : ($id == 2 ? 'IN_TRANSIT' : 'DELIVERED'),
            'clientName' => $id == 1 ? 'Amazon Inc.' : ($id == 2 ? 'Walmart' : 'Target'),
            'originCity' => $id == 1 ? 'New York' : ($id == 2 ? 'Chicago' : 'Los Angeles'),
            'destinationCity' => $id == 1 ? 'Boston' : ($id == 2 ? 'Detroit' : 'San Diego'),
            'originAddress' => $id == 1 ? '123 Warehouse Blvd, NY' : ($id == 2 ? '789 Supply St, IL' : '222 Sunset Blvd, CA'),
            'destinationAddress' => $id == 1 ? '456 Distribution Dr, MA' : ($id == 2 ? '101 Store Ln, MI' : '333 Ocean Dr, CA'),
            'pickupDate' => $id == 1 ? now()->addDays(1)->toIso8601String() : now()->subDays(1)->toIso8601String(),
            'deliveryDate' => $id == 1 ? now()->addDays(2)->toIso8601String() : now()->addDays(1)->toIso8601String(),
            'temperatureRequirements' => $id == 1 ? 'keep_frozen' : ($id == 3 ? 'keep_dry' : null),
            'pallets' => $id * 2,
            'price' => 100.00 + ($id * 10),
        ]);
    }
}
