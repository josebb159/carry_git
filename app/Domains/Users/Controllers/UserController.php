<?php

namespace App\Domains\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Users\Models\User;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * List all users with their connection_id (for Admin)
     */
    public function index(): JsonResponse
    {
        // Simple listing for dashboard management
        $users = User::select('id', 'name', 'email', 'connection_id', 'created_at')
            ->with('roles')
            ->get();

        return $this->successResponse($users, 'Users retrieved successfully');
    }

    /**
     * Reset the connection_id for a user (driver/carrier)
     */
    public function resetDevice(User $user): JsonResponse
    {
        $user->update(['connection_id' => null]);

        return $this->successResponse($user, "Device reset successful for user {$user->name}. They can now login from a new device.");
    }
}
