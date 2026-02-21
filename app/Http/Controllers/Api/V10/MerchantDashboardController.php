<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Domains\Merchant\Models\Parcel;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantDashboardController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v10/dashboard
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $total = Parcel::where('user_id', $userId)->count();
        $pending = Parcel::where('user_id', $userId)->where('status', 'pending')->count();
        $delivered = Parcel::where('user_id', $userId)->where('status', 'delivered')->count();
        $returned = Parcel::where('user_id', $userId)->where('status', 'returned')->count();
        $cancelled = Parcel::where('user_id', $userId)->where('status', 'cancelled')->count();

        $wallet = $request->user()->wallet_balance ?? 0;

        return $this->successResponse([
            'total_parcels' => $total,
            'pending_parcels' => $pending,
            'delivered_parcels' => $delivered,
            'returned_parcels' => $returned,
            'cancelled_parcels' => $cancelled,
            'wallet_balance' => $wallet,
        ], 'Dashboard data retrieved.');
    }

    /**
     * GET /api/v10/dashboard/balance-details
     */
    public function balanceDetails(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->successResponse([
            'wallet_balance' => $user->wallet_balance ?? 0,
            'total_collected' => Parcel::where('user_id', $user->id)->sum('cod_amount'),
            'total_charges' => Parcel::where('user_id', $user->id)->sum('delivery_charge'),
            'pending_payment' => 0, // can be computed from payment_requests
        ], 'Balance details retrieved.');
    }

    /**
     * GET /api/v10/dashboard/available-parcels
     */
    public function availableParcels(Request $request): JsonResponse
    {
        $parcels = Parcel::where('user_id', $request->user()->id)
            ->where('status', 'pending')
            ->with('shop')
            ->latest()
            ->take(20)
            ->get();

        return $this->successResponse($parcels, 'Available parcels retrieved.');
    }

    /**
     * GET /api/v10/analytics
     */
    public function analytics(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $statuses = ['pending', 'picked_up', 'in_transit', 'delivered', 'returned', 'cancelled'];
        $data = [];
        foreach ($statuses as $status) {
            $data[$status] = Parcel::where('user_id', $userId)->where('status', $status)->count();
        }

        return $this->successResponse([
            'parcel_by_status' => $data,
            'monthly_total' => Parcel::where('user_id', $userId)
                ->whereMonth('created_at', now()->month)
                ->count(),
        ], 'Analytics retrieved.');
    }
}
