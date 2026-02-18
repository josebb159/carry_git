<?php

namespace App\Domains\Fleet\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Fleet\Models\Tracking;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    use ApiResponse;

    /**
     * Store new tracking coordinates
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'speed' => 'nullable|numeric',
            'order_uuid' => 'nullable|uuid|exists:orders,uuid',
            'carrier_id' => 'nullable|exists:carriers,id',
        ]);

        $tracking = Tracking::create([
            'user_id' => $request->user()->id,
            'carrier_id' => $validated['carrier_id'] ?? null,
            'order_uuid' => $validated['order_uuid'] ?? null,
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
            'speed' => $validated['speed'] ?? 0,
        ]);

        return $this->successResponse($tracking, 'Tracking data recorded', 201);
    }
}
