<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Domains\Billing\Models\Invoice;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantInvoiceController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v10/invoice-list/index
     */
    public function index(Request $request): JsonResponse
    {
        // Merchants see invoices linked to orders placed by them (user_id)
        $invoices = Invoice::with('client')
            ->latest()
            ->paginate(20);

        return $this->successResponse($invoices, 'Invoice list retrieved.');
    }

    /**
     * GET /api/v10/invoice-details/{id}
     */
    public function show(int $id): JsonResponse
    {
        $invoice = Invoice::with(['client', 'lines'])->findOrFail($id);
        return $this->successResponse($invoice, 'Invoice details retrieved.');
    }
}
