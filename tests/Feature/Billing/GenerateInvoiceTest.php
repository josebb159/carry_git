<?php

namespace Tests\Feature\Billing;

use App\Domains\Users\Models\User;
use App\Domains\Orders\Models\Order;
use App\Domains\Clients\Models\Client;
use App\Shared\Enums\OrderStatus;
use App\Shared\Enums\InvoiceStatus;
use Tests\TestCase;

class GenerateInvoiceTest extends TestCase
{
    public function test_can_generate_invoice_from_order()
    {
        $user = User::factory()->create();
        $client = Client::create([
            'legal_name' => 'Billable Client',
            'vat_number' => 'VAT-BILL-' . time(),
            'country' => 'ES',
            'payment_terms_days' => 45, // Custom terms
        ]);

        $order = Order::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'client_id' => $client->id,
            'user_id' => $user->id,
            'order_number' => 'ORD-BILL-' . time(),
            'status' => OrderStatus::DELIVERED,
            'total_amount' => 1000.00,
        ]);

        $response = $this->actingAs($user)->postJson('/api/v1/billing/invoices', [
            'order_uuid' => $order->uuid,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'uuid',
                    'invoice_number',
                    'total',
                    'status',
                    'lines',
                ],
            ]);

        $invoiceData = $response->json('data');

        $this->assertEquals(1210.00, $invoiceData['total']); // 1000 + 21% VAT
        $this->assertEquals(InvoiceStatus::DRAFT->value, $invoiceData['status']);

        // Check due date is ~45 days from now (allow small diff)
        $dueDate = \Carbon\Carbon::parse($invoiceData['due_date']);
        $this->assertTrue($dueDate->isSameDay(now()->addDays(45)));

        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'client_id' => $client->id,
        ]);

        $this->assertDatabaseHas('invoice_lines', [
            'invoice_id' => $invoiceData['id'],
            'amount' => 1000.00,
        ]);
    }
}
