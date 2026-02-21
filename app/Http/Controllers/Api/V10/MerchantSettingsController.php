<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Domains\Settings\Models\Setting;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class MerchantSettingsController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v10/general-settings  (PUBLIC — only apiKey required, no auth)
     * Returns app-wide configuration needed by the Flutter app on startup.
     */
    public function generalSettings(): JsonResponse
    {
        $settings = [
            'app_name' => config('app.name'),
            'currency' => Setting::get('currency', 'USD'),
            'currency_symbol' => Setting::get('currency_symbol', '$'),
            'maps_api_key' => Setting::get('Maps_API_KEY', ''),
            'base_url' => config('app.url'),
            'otp_enabled' => (bool) Setting::get('otp_enabled', false),
            'registration_open' => (bool) Setting::get('registration_open', true),
            'min_withdrawal' => (float) Setting::get('min_withdrawal', 0),
            'firebase_key' => Setting::get('firebase_key', ''),
        ];

        return $this->successResponse($settings, 'General settings retrieved.');
    }

    /**
     * GET /api/v10/settings/delivery-charges
     */
    public function deliveryCharges(): JsonResponse
    {
        $charges = [
            'base_charge' => (float) Setting::get('delivery_base_charge', 0),
            'per_kg_charge' => (float) Setting::get('delivery_per_kg_charge', 0),
            'zones' => json_decode(Setting::get('delivery_zones', '[]'), true),
        ];

        return $this->successResponse($charges, 'Delivery charges retrieved.');
    }

    /**
     * GET /api/v10/settings/cod-charges
     */
    public function codCharges(): JsonResponse
    {
        $charges = [
            'cod_charge_type' => Setting::get('cod_charge_type', 'flat'),  // flat | percentage
            'cod_charge_value' => (float) Setting::get('cod_charge_value', 0),
            'cod_min_amount' => (float) Setting::get('cod_min_amount', 0),
        ];

        return $this->successResponse($charges, 'COD charges retrieved.');
    }
}
