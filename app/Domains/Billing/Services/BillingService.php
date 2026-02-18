<?php

namespace App\Domains\Billing\Services;

use App\Domains\Billing\Models\Invoice;
use App\Domains\Orders\Models\Order;
use App\Shared\Enums\InvoiceStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BillingService
{
    public function generateFromOrder(Order $order): Invoice
    {
        return DB::transaction(function () use ($order) {
            // Load client relationships if not loaded
            $order->loadMissing('client');

            $client = $order->client;
            $paymentTerms = $client->payment_terms_days ?? 30; // Default to 30 days

            $invoice = Invoice::create([
                'uuid' => Str::uuid(),
                'client_id' => $client->id,
                'order_id' => $order->id,
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)), // Simple generator
                'date' => now(),
                'due_date' => now()->addDays($paymentTerms),
                'subtotal' => $order->total_amount,
                'tax' => $order->total_amount * 0.21, // Hardcoded 21% VAT for now
                'total' => $order->total_amount * 1.21,
                'status' => InvoiceStatus::DRAFT,
            ]);

            // Create a single line item for the order (can be expanded later)
            $invoice->lines()->create([
                'description' => "Order #{$order->order_number}",
                'quantity' => 1,
                'unit_price' => $order->total_amount,
                'amount' => $order->total_amount,
            ]);

            return $invoice->load('lines');
        });
    }
}
