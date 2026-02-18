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

    /**
     * Store a new event for an order, including optional proof of delivery (image).
     */
    public function store(Request $request, string $orderUuid): JsonResponse
    {
        $order = Order::where('uuid', $orderUuid)->firstOrFail();

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

        $event = Event::create([
            'uuid' => (string)Str::uuid(),
            'order_id' => $order->id,
            'user_id' => $request->user()->id,
            'type' => $validated['type'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'meta_data' => $validated['meta_data'],
            'proof_url' => $proofUrl,
        ]);

        // Update order status if applicable
        if ($validated['type'] === 'unloaded') {
            $order->update(['status' => 'delivered']);
        }

        return $this->successResponse($event, 'Event created successfully', 201);
    }

    /**
     * List events for a specific order.
     */
    public function index(string $orderUuid): JsonResponse
    {
        $order = Order::where('uuid', $orderUuid)->firstOrFail();
        $events = $order->events()->with('user:id,name')->orderBy('created_at', 'desc')->get();

        return $this->successResponse($events, 'Order events retrieved');
    }
}
