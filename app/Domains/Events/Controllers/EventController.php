<?php

namespace App\Domains\Events\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Events\Models\Event;
use App\Domains\Orders\Models\Order;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    use ApiResponse;

    public function store(Request $request, int $orderId): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'description' => 'required|string',
            'location' => 'nullable|array',
            'meta_data' => 'nullable|array',
            'proof_image' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $proofUrl = null;
        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('proofs', 'public');
            $proofUrl = Storage::disk('public')->url($path);
        }

        if ($orderId >= 1000000) {
            $parcelId = $orderId - 1000000;
            $parcel = \App\Domains\Merchant\Models\Parcel::findOrFail($parcelId);

            $parcelStatusMap = [
                'IN_TRANSIT' => 'in_transit',
                'DELIVERED' => 'delivered',
            ];
            $newStatus = $parcelStatusMap[$validated['type']] ?? 'processing';

            $parcel->update(['status' => $newStatus]);
            $parcel->appendLog($newStatus, $validated['description']);

            return response()->json(['message' => 'Event created successfully'], 201);
        }

        $order = Order::findOrFail($orderId);

        $backendType = match ($validated['type']) {
            'IN_TRANSIT' => 'departed',
            'DELIVERED' => 'unloaded',
            default => 'incident'
        };

        $event = Event::create([
            'uuid' => (string)Str::uuid(),
            'order_id' => $order->id,
            'user_id' => $request->user()->id,
            'type' => $backendType,
            'description' => $validated['description'],
            'location' => $validated['location'] ?? [],
            'meta_data' => $validated['meta_data'] ?? [],
            'proof_url' => $proofUrl,
        ]);

        // Update order status if applicable
        if ($backendType === 'unloaded') {
            $order->update(['status' => 'delivered']);
        } else if ($backendType === 'departed') {
            $order->update(['status' => 'in_transit']);
        }

        return $this->successResponse($event, 'Event created successfully', 201);
    }

    /**
     * List events for a specific order.
     */
    public function index(int $orderId): JsonResponse
    {
        if ($orderId >= 1000000) {
            $parcelId = $orderId - 1000000;
            $parcel = \App\Domains\Merchant\Models\Parcel::findOrFail($parcelId);
            $logs = $parcel->logs ?? [];
            
            $events = array_map(function($log) {
               return [
                   'type' => $log['status'] ?? 'processing',
                   'description' => $log['note'] ?? 'Status update',
                   'created_at' => $log['time'] ?? now()->toIso8601String(),
                   'user' => ['name' => 'Merchant/System']
               ];
            }, $logs);
            
            // reverse to match descending order
            $events = array_reverse($events);

            return response()->json(['data' => $events, 'message' => 'Order events retrieved', 'success' => true]);
        }

        $order = Order::findOrFail($orderId);
        $events = $order->events()->with('user:id,name')->orderBy('created_at', 'desc')->get();

        return $this->successResponse($events, 'Order events retrieved');
    }
}
