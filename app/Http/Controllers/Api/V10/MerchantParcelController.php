<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Domains\Merchant\Models\Parcel;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MerchantParcelController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v10/parcel/index
     */
    public function index(Request $request): JsonResponse
    {
        $parcels = Parcel::where('user_id', $request->user()->id)
            ->with('shop')
            ->latest()
            ->paginate(20);

        return $this->successResponse($parcels, 'Parcels retrieved.');
    }

    /**
     * GET /api/v10/parcel/filter
     */
    public function filter(Request $request): JsonResponse
    {
        $query = Parcel::where('user_id', $request->user()->id)->with('shop');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('tracking_code', 'like', "%{$request->search}%")
                    ->orWhere('recipient_name', 'like', "%{$request->search}%")
                    ->orWhere('recipient_phone', 'like', "%{$request->search}%");
            });
        }

        return $this->successResponse($query->latest()->paginate(20), 'Filtered parcels retrieved.');
    }

    /**
     * GET /api/v10/parcel/create
     * Returns the data needed to build the creation form (shops list).
     */
    public function create(Request $request): JsonResponse
    {
        $shops = \App\Domains\Merchant\Models\Shop::where('user_id', $request->user()->id)->get();
        return $this->successResponse(['shops' => $shops], 'Parcel creation form data.');
    }

    /**
     * GET /api/v10/parcel/details/{id}
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $parcel = Parcel::where('user_id', $request->user()->id)
            ->with('shop')
            ->findOrFail($id);

        return $this->successResponse($parcel, 'Parcel details retrieved.');
    }

    /**
     * GET /api/v10/parcel/logs/{id}
     */
    public function logs(int $id, Request $request): JsonResponse
    {
        $parcel = Parcel::where('user_id', $request->user()->id)->findOrFail($id);
        return $this->successResponse($parcel->logs ?? [], 'Parcel logs retrieved.');
    }

    /**
     * POST /api/v10/parcel/store
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shop_id' => 'nullable|exists:shops,id',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:30',
            'recipient_address' => 'required|string',
            'weight' => 'nullable|numeric|min:0',
            'cod_amount' => 'nullable|numeric|min:0',
            'delivery_charge' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['tracking_code'] = strtoupper('TRK-' . Str::random(8));
        $validated['status'] = 'pending';
        $validated['logs'] = [['status' => 'pending', 'note' => 'Parcel created.', 'time' => now()->toDateTimeString()]];

        $parcel = Parcel::create($validated);

        return $this->successResponse($parcel, 'Parcel created successfully.', 201);
    }

    /**
     * GET /api/v10/parcel/edit/{id}
     */
    public function edit(int $id, Request $request): JsonResponse
    {
        $parcel = Parcel::where('user_id', $request->user()->id)->with('shop')->findOrFail($id);
        $shops = \App\Domains\Merchant\Models\Shop::where('user_id', $request->user()->id)->get();

        return $this->successResponse(['parcel' => $parcel, 'shops' => $shops], 'Parcel edit data retrieved.');
    }

    /**
     * PUT /api/v10/parcel/update/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $parcel = Parcel::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'shop_id' => 'nullable|exists:shops,id',
            'recipient_name' => 'sometimes|string|max:255',
            'recipient_phone' => 'sometimes|string|max:30',
            'recipient_address' => 'sometimes|string',
            'weight' => 'sometimes|numeric|min:0',
            'cod_amount' => 'sometimes|numeric|min:0',
            'delivery_charge' => 'sometimes|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $parcel->update($validated);

        return $this->successResponse($parcel, 'Parcel updated successfully.');
    }

    /**
     * DELETE /api/v10/parcel/delete/{id}
     */
    public function destroy(int $id, Request $request): JsonResponse
    {
        $parcel = Parcel::where('user_id', $request->user()->id)->findOrFail($id);
        $parcel->delete();

        return $this->successResponse(null, 'Parcel deleted successfully.');
    }

    /**
     * GET /api/v10/parcel/all/status
     * Returns all available parcel statuses.
     */
    public function allStatuses(): JsonResponse
    {
        $statuses = ['pending', 'picked_up', 'in_transit', 'delivered', 'returned', 'cancelled'];
        return $this->successResponse($statuses, 'Parcel statuses retrieved.');
    }

    /**
     * GET /api/v10/status-wise/parcel/list/{status}
     */
    public function byStatus(string $status, Request $request): JsonResponse
    {
        $parcels = Parcel::where('user_id', $request->user()->id)
            ->where('status', $status)
            ->with('shop')
            ->latest()
            ->paginate(20);

        return $this->successResponse($parcels, "Parcels with status '{$status}' retrieved.");
    }
}
