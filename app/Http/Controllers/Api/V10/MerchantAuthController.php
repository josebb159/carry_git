<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Domains\Users\Models\User;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class MerchantAuthController extends Controller
{
    use ApiResponse;

    /**
     * POST /api/v10/signin
     * Login for merchant users.
     * Returns { "token": "...", "user": {...} } as expected by Flutter app.
     */
    public function signin(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->errorResponse('Invalid credentials. Please check your email and password.', 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();



        // Revoke previous tokens to keep sessions clean (optional)
        // $user->tokens()->delete();

        $deviceName = $request->header('User-Agent') ?? 'merchant_app';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? null,
                'role' => $user->role,
            ],
        ]);
    }

    /**
     * POST /api/v10/register
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:30',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
        ]);

        $user->assignRole('merchant');

        $token = $user->createToken('merchant_app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ], 201);
    }

    /**
     * POST /api/v10/sign-out
     */
    public function signOut(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null, 'Logged out successfully.');
    }

    /**
     * POST /api/v10/refresh
     * Revoke current token and issue a new one.
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $token = $user->createToken('merchant_app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? null,
            ],
        ]);
    }

    /**
     * POST /api/v10/otp-login  (stub – OTP not implemented yet)
     */
    public function otpLogin(Request $request): JsonResponse
    {
        return $this->errorResponse('OTP login not yet implemented.', 501);
    }

    /**
     * POST /api/v10/resend-otp  (stub)
     */
    public function resendOtp(Request $request): JsonResponse
    {
        return $this->errorResponse('OTP resend not yet implemented.', 501);
    }

    /**
     * POST /api/v10/otp-verification  (stub)
     */
    public function otpVerification(Request $request): JsonResponse
    {
        return $this->errorResponse('OTP verification not yet implemented.', 501);
    }
}
