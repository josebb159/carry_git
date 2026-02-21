<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Domains\Merchant\Models\Shop;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantShopController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v10/shops/index
     */
    public function index(Request $request): JsonResponse
    {
        $shops = Shop::where('user_id', $request->user()->id)->latest()->get();
        return $this->successResponse($shops, 'Shops retrieved.');
    }

    /**
     * POST /api/v10/shops/store
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = $request->user()->id;
        $shop = Shop::create($validated);

        return $this->successResponse($shop, 'Shop created successfully.', 201);
    }

    /**
     * PUT /api/v10/shops/update/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $shop = Shop::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|nullable|string|max:30',
            'address' => 'sometimes|nullable|string',
            'contact_person' => 'sometimes|nullable|string|max:255',
        ]);

        $shop->update($validated);

        return $this->successResponse($shop, 'Shop updated successfully.');
    }

    /**
     * DELETE /api/v10/shops/delete/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $shop = Shop::where('user_id', $request->user()->id)->findOrFail($id);
        $shop->delete();

        return $this->successResponse(null, 'Shop deleted successfully.');
    }
}
