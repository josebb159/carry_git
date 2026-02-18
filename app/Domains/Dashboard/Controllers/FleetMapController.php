<?php

namespace App\Domains\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Fleet\Models\Tracking;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FleetMapController extends Controller
{
    use ApiResponse;

    /**
     * Show the Fleet Map View
     */
    public function index()
    {
        return view('dashboard.fleet.map');
    }

    /**
     * Get latest locations for all active carriers (AJAX)
     */
    public function getLocations(): JsonResponse
    {
        // Get the latest tracking point for each user/driver
        $latestLocations = Tracking::select('fleet_tracking.*', 'users.name as driver_name')
            ->join(DB::raw('(SELECT user_id, MAX(created_at) as max_date FROM fleet_tracking GROUP BY user_id) as latest'), function ($join) {
            $join->on('fleet_tracking.user_id', '=', 'latest.user_id')
                ->on('fleet_tracking.created_at', '=', 'latest.max_date');
        })
            ->join('users', 'fleet_tracking.user_id', '=', 'users.id')
            ->get();

        return response()->json($latestLocations);
    }
}
