<?php

namespace App\Domains\Dashboard\Services;

use App\Domains\Orders\Models\Order;
use App\Domains\Billing\Models\Invoice;
use App\Domains\Events\Models\Event;
use App\Shared\Enums\OrderStatus;
use App\Shared\Enums\InvoiceStatus;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStats(): array
    {
        // 1. Order Counts by Status
        $orderStats = Order::toBase()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Fill missing statuses with 0
        $allStatuses = OrderStatus::cases();
        $formattedOrderStats = [];
        foreach ($allStatuses as $status) {
            $formattedOrderStats[$status->value] = $orderStats[$status->value] ?? 0;
        }

        // 2. Revenue (Total of Paid Invoices)
        $revenue = Invoice::where('status', InvoiceStatus::PAID)->sum('total');

        // 3. Pending Revenue (Sent but not paid)
        $pendingRevenue = Invoice::where('status', InvoiceStatus::SENT)->sum('total');

        // 4. Recent Events
        $recentEvents = Event::with(['order', 'user'])
            ->latest()
            ->latest()
            ->limit(10)
            ->get();

        // 5. Recent Orders
        $recentOrders = Order::with(['client', 'carrier'])
            ->latest()
            ->limit(5)
            ->get();

        // 6. Top Clients (by order volume)
        $topClients = \App\Domains\Clients\Models\Client::withCount('orders')
            ->orderByDesc('orders_count')
            ->limit(5)
            ->get();

        return [
            'orders' => $formattedOrderStats,
            'revenue' => [
                'total_collected' => (float) $revenue,
                'pending_collection' => (float) $pendingRevenue,
            ],
            'recent_events' => $recentEvents,
            'recent_orders' => $recentOrders,
            'top_clients' => $topClients,
        ];
    }
}
