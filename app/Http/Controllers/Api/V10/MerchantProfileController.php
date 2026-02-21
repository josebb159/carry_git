<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MerchantProfileController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v10/profile
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->successResponse([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? null,
            'wallet_balance' => $user->wallet_balance ?? 0,
            'avatar_url' => $user->avatar_url,
            'role' => $user->role,
        ], 'Profile retrieved.');
    }

    /**
     * POST /api/v10/profile/update
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|nullable|string|max:30',
        ]);

        $user = $request->user();
        $user->update($validated);

        return $this->successResponse([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ], 'Profile updated successfully.');
    }

    /**
     * POST /api/v10/update-password
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->errorResponse('Current password is incorrect.', 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return $this->successResponse(null, 'Password updated successfully.');
    }

    /**
     * POST /api/v10/device
     * Register device token for push notifications.
     */
    public function registerDevice(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = $request->user();
        $user->update(['fcm_token' => $request->token]);

        return $this->successResponse(null, 'Device registered successfully.');
    }

    /**
     * POST /api/v10/fcm-subscribe
     */
    public function fcmSubscribe(Request $request): JsonResponse
    {
        $request->validate(['token' => 'required|string']);

        $user = $request->user();
        $user->update(['fcm_token' => $request->token]);

        return $this->successResponse(null, 'FCM subscription successful.');
    }

    /**
     * POST /api/v10/fcm-unsubscribe
     */
    public function fcmUnsubscribe(Request $request): JsonResponse
    {
        $request->user()->update(['fcm_token' => null]);
        return $this->successResponse(null, 'FCM unsubscribed successfully.');
    }
}
