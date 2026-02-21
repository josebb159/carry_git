<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Domains\Merchant\Models\Fraud;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantFraudController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v10/fraud/index
     */
    public function index(Request $request): JsonResponse
    {
        $frauds = Fraud::where('user_id', $request->user()->id)->latest()->paginate(20);
        return $this->successResponse($frauds, 'Fraud reports retrieved.');
    }

    /**
     * POST /api/v10/fraud/store
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tracking_code' => 'nullable|string|max:100',
            'description' => 'required|string',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'open';

        $fraud = Fraud::create($validated);

        return $this->successResponse($fraud, 'Fraud report submitted.', 201);
    }

    /**
     * GET /api/v10/fraud/edit/{id}
     */
    public function edit(int $id, Request $request): JsonResponse
    {
        $fraud = Fraud::where('user_id', $request->user()->id)->findOrFail($id);
        return $this->successResponse($fraud, 'Fraud report retrieved.');
    }

    /**
     * PUT /api/v10/fraud/update/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $fraud = Fraud::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'tracking_code' => 'nullable|string|max:100',
            'description' => 'sometimes|string',
        ]);

        $fraud->update($validated);

        return $this->successResponse($fraud, 'Fraud report updated.');
    }

    /**
     * DELETE /api/v10/fraud/delete/{id}
     */
    public function destroy(int $id, Request $request): JsonResponse
    {
        $fraud = Fraud::where('user_id', $request->user()->id)->findOrFail($id);
        $fraud->delete();

        return $this->successResponse(null, 'Fraud report deleted.');
    }
}
