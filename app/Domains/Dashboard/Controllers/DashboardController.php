<?php

namespace App\Domains\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Dashboard\Services\DashboardService;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    public function index()
    {
        // Mock data for now, or fetch from DB
        return response()->json([
            'active_orders' => 12,
            'in_transit_orders' => 5,
            'delivered_month' => 45,
            'active_incidents' => 1,
        ]);
    }
}
