<?php

namespace App\Domains\Billing\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Billing\Services\BillingService;
use App\Domains\Orders\Models\Order;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected BillingService $billingService
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'order_uuid' => 'required|exists:orders,uuid',
        ]);

        $order = Order::where('uuid', $request->order_uuid)->firstOrFail();

        $invoice = $this->billingService->generateFromOrder($order);

        return $this->successResponse($invoice, 'Invoice created successfully', 201);
    }

    public function index(): JsonResponse
    {
        // Simple index for now, can add filters later
        $invoices = \App\Domains\Billing\Models\Invoice::with('client')->latest()->paginate(20);
        return $this->successResponse($invoices, 'Invoices retrieved');
    }
}
