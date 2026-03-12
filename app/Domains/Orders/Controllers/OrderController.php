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

    public function index()
    {
        $orders = \App\Domains\Orders\Models\Order::with(['client', 'locations'])->get()->map(function($order) {
            $origin = $order->locations->where('type', 'pickup')->first();
            $destination = $order->locations->where('type', 'delivery')->first();
            return [
                'id' => $order->id,
                'orderNumber' => $order->order_number,
                'status' => $order->status->value ?? 'PENDING',
                'clientName' => $order->client->name ?? 'Unknown',
                'originCity' => $origin?->city ?? 'Unknown',
                'destinationCity' => $destination?->city ?? 'Unknown',
                'originAddress' => $origin?->address ?? 'Unknown',
                'destinationAddress' => $destination?->address ?? 'Unknown',
                'pickupDate' => $origin?->scheduled_at ? \Carbon\Carbon::parse($origin->scheduled_at)->toIso8601String() : now()->toIso8601String(),
                'deliveryDate' => $destination?->scheduled_at ? \Carbon\Carbon::parse($destination->scheduled_at)->toIso8601String() : now()->addDays(1)->toIso8601String(),
                'temperatureRequirements' => $order->temperature,
                'pallets' => 1,
                'price' => (float) $order->total_amount,
            ];
        });

        $parcels = \App\Domains\Merchant\Models\Parcel::with(['shop', 'user'])->get()->map(function($parcel) {
            // Map parcel status to equivalent order status
            $statusMap = [
                'pending' => 'PENDING',
                'processing' => 'PENDING',
                'picked_up' => 'IN_TRANSIT',
                'in_transit' => 'IN_TRANSIT',
                'delivered' => 'DELIVERED',
                'returned' => 'INCIDENT',
                'cancelled' => 'CANCELLED',
            ];
            
            return [
                'id' => 1000000 + $parcel->id, // Offset to avoid ID collision with Orders
                'orderNumber' => $parcel->tracking_code,
                'status' => $statusMap[$parcel->status] ?? 'PENDING',
                'clientName' => $parcel->shop->name ?? $parcel->user->name ?? 'Merchant',
                'originCity' => 'Local Shop',
                'destinationCity' => 'Local Dest',
                'originAddress' => $parcel->shop->address ?? 'Shop Location',
                'destinationAddress' => $parcel->recipient_address ?? 'Dest Location',
                'pickupDate' => $parcel->created_at ? $parcel->created_at->toIso8601String() : now()->toIso8601String(),
                'deliveryDate' => $parcel->created_at ? $parcel->created_at->addDays(1)->toIso8601String() : now()->addDays(1)->toIso8601String(),
                'temperatureRequirements' => null,
                'pallets' => 1,
                'price' => (float) ($parcel->cod_amount ?? $parcel->delivery_charge ?? 0),
            ];
        });

        return response()->json([
            'data' => $orders->concat($parcels)->values()
        ]);
    }

    public function show($id)
    {
        if ($id >= 1000000) {
            $parcelId = $id - 1000000;
            $parcel = \App\Domains\Merchant\Models\Parcel::with(['shop', 'user'])->find($parcelId);
            if (!$parcel) return response()->json(['message' => 'Not found'], 404);

            $statusMap = [
                'pending' => 'PENDING',
                'processing' => 'PENDING',
                'picked_up' => 'IN_TRANSIT',
                'in_transit' => 'IN_TRANSIT',
                'delivered' => 'DELIVERED',
                'returned' => 'INCIDENT',
                'cancelled' => 'CANCELLED',
            ];

            return response()->json([
                'id' => 1000000 + $parcel->id,
                'orderNumber' => $parcel->tracking_code,
                'status' => $statusMap[$parcel->status] ?? 'PENDING',
                'clientName' => $parcel->shop->name ?? $parcel->user->name ?? 'Merchant',
                'originCity' => 'Local Shop',
                'destinationCity' => 'Local Dest',
                'originAddress' => $parcel->shop->address ?? 'Shop Location',
                'destinationAddress' => $parcel->recipient_address ?? 'Dest Location',
                'pickupDate' => $parcel->created_at ? $parcel->created_at->toIso8601String() : now()->toIso8601String(),
                'deliveryDate' => $parcel->created_at ? $parcel->created_at->addDays(1)->toIso8601String() : now()->addDays(1)->toIso8601String(),
                'temperatureRequirements' => null,
                'pallets' => 1,
                'price' => (float) ($parcel->cod_amount ?? $parcel->delivery_charge ?? 0),
            ]);
        }

        $order = \App\Domains\Orders\Models\Order::with(['client', 'locations'])->find($id);
        if (!$order) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $origin = $order->locations->where('type', 'pickup')->first();
        $destination = $order->locations->where('type', 'delivery')->first();
        
        return response()->json([
            'id' => $order->id,
            'orderNumber' => $order->order_number,
            'status' => $order->status->value ?? 'PENDING',
            'clientName' => $order->client->name ?? 'Unknown',
            'originCity' => $origin?->city ?? 'Unknown',
            'destinationCity' => $destination?->city ?? 'Unknown',
            'originAddress' => $origin?->address ?? 'Unknown',
            'destinationAddress' => $destination?->address ?? 'Unknown',
            'pickupDate' => $origin?->scheduled_at ? \Carbon\Carbon::parse($origin->scheduled_at)->toIso8601String() : now()->toIso8601String(),
            'deliveryDate' => $destination?->scheduled_at ? \Carbon\Carbon::parse($destination->scheduled_at)->toIso8601String() : now()->addDays(1)->toIso8601String(),
            'temperatureRequirements' => $order->temperature,
            'pallets' => 1,
            'price' => (float) $order->total_amount,
        ]);
    }
}
