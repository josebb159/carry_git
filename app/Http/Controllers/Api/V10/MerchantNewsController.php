<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Domains\Merchant\Models\NewsOffer;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class MerchantNewsController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v10/news-offer/index
     */
    public function index(): JsonResponse
    {
        $items = NewsOffer::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(20);

        return $this->successResponse($items, 'News and offers retrieved.');
    }
}
