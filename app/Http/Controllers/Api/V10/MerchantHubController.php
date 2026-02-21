<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantHubController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v10/hub
     * GET+POST  /api/v10/hub
     * Hub (pickup points/branches) listing for merchants.
     */
    public function index(): JsonResponse
    {
        // Returns hub/branch data — can be seeded or pulled from settings
        $hubs = \App\Domains\Settings\Models\Setting::get('hubs', '[]');
        $hubs = is_array($hubs) ? $hubs : json_decode($hubs, true) ?? [];

        return $this->successResponse($hubs, 'Hub list retrieved.');
    }
}
