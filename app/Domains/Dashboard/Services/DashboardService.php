<?php

namespace App\Domains\Dashboard\Services;

use App\Domains\Orders\Models\Order;
use App\Domains\Billing\Models\Invoice;
use App\Domains\Events\Models\Event;
use App\Shared\Enums\OrderStatus;
use App\Shared\Enums\InvoiceStatus;
use Illuminate\Support\Facades\DB;
use App\Domains\Users\Models\User;

class DashboardService
{
    public function getStats(?User $user = null): array
    {
        $isClient = false;
        $clientProfile = null;

        if ($user) {
            $isClient = $user->hasRole('merchant') || $user->hasRole('user');
            if ($isClient) {
                $clientProfile = \App\Domains\Clients\Models\Client::where('user_id', $user->id)->first();
            }
        }

        // 1. Order Counts by Status - Fixed Illegal offset type using toBase()
        $query = Order::query();
        if ($isClient && $user) {
            $query->where('user_id', $user->id);
        }

        $orderStats = $query->toBase()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $allStatuses = OrderStatus::cases();
        $formattedOrderStats = [];
        foreach ($allStatuses as $status) {
            $formattedOrderStats[$status->value] = (int) ($orderStats[$status->value] ?? 0);
        }

        // 2. Revenue
        $revenueQuery = Invoice::where('status', InvoiceStatus::PAID);
        if ($isClient && $user) {
            $revenueQuery->whereHas('order', fn($q) => $q->where('user_id', $user->id));
        }
        $revenue = $revenueQuery->sum('total');

        // 3. Pending Revenue
        $pendingRevenueQuery = Invoice::where('status', InvoiceStatus::SENT);
        if ($isClient && $user) {
            $pendingRevenueQuery->whereHas('order', fn($q) => $q->where('user_id', $user->id));
        }
        $pendingRevenue = $pendingRevenueQuery->sum('total');

        // 4. Recent Events
        $eventsQuery = Event::with(['order', 'user']);
        if ($isClient && $user) {
            $eventsQuery->whereHas('order', fn($q) => $q->where('user_id', $user->id));
        }
        $recentEvents = $eventsQuery->latest()->limit(10)->get();

        // 5. Recent Orders
        $ordersQuery = Order::with(['client', 'carrier']);
        if ($isClient && $user) {
            $ordersQuery->where('user_id', $user->id);
        }
        $recentOrders = $ordersQuery->latest()->limit(5)->get();

        // 6. Top Clients - Only for non-clients (Admins)
        $topClients = $isClient ? collect() : \App\Domains\Clients\Models\Client::withCount('orders')
            ->orderByDesc('orders_count')
            ->limit(5)
            ->get();

        // 7. TRACKING DATA - Formatted to Array for frontend
        $trackingPoints = collect();
        
        $trackingOrdersQuery = Order::where('status', OrderStatus::IN_TRANSIT);
        if ($isClient && $user) {
            $trackingOrdersQuery->where('user_id', $user->id);
        }
        $inTransitOrders = $trackingOrdersQuery->get();

        foreach ($inTransitOrders as $incOrder) {
            $latestPoint = \App\Domains\Fleet\Models\Tracking::where('order_uuid', $incOrder->uuid)
                ->latest('id')
                ->first();
            
            if ($latestPoint) {
                // Ensure relationship is loaded for the view
                $latestPoint->setRelation('order', $incOrder->load('client', 'carrier', 'locations'));
                $trackingPoints->push($latestPoint);
            }
        }

        return [
            'is_client' => $isClient,
            'client_profile' => $clientProfile,
            'in_transit_tracking' => $trackingPoints->toArray(),
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