<x-app-layout>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-white">Dashboard</h2>
            <p class="text-gray-400 mt-1">Overview of your logistics performance</p>
        </div>
        <div class="space-x-3">
            <a href="{{ route('orders.create') }}"
                class="inline-flex items-center px-4 py-2 bg-lime-brand border border-transparent rounded-lg font-bold text-xs text-black uppercase tracking-widest hover:bg-lime-400 active:bg-lime-500 focus:outline-none focus:border-lime-700 focus:ring ring-lime-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Order
            </a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Pending (Yellow) -->
        <div
            class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 p-6 transition hover:border-lime-brand/50">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pending
                        Orders</div>
                    <div class="mt-2 text-3xl font-bold text-white">
                        {{ $stats['orders']['pending'] ?? 0 }}
                    </div>
                </div>
                <div class="p-3 bg-yellow-900/20 rounded-xl text-yellow-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-yellow-400 flex items-center">
                <span class="font-medium">Needs attention</span>
            </div>
        </div>

        <!-- In Transit (Blue) -->
        <div
            class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 p-6 transition hover:border-lime-brand/50">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-medium text-gray-400 uppercase tracking-wider">In
                        Transit</div>
                    <div class="mt-2 text-3xl font-bold text-white">
                        {{ $stats['orders']['in_transit'] ?? 0 }}
                    </div>
                </div>
                <div class="p-3 bg-blue-900/20 rounded-xl text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-blue-400 flex items-center">
                <span class="font-medium">Active shipments</span>
            </div>
        </div>

        <!-- Delivered (Green) -->
        <div
            class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 p-6 transition hover:border-lime-brand/50">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-medium text-gray-400 uppercase tracking-wider">Delivered
                    </div>
                    <div class="mt-2 text-3xl font-bold text-white">
                        {{ $stats['orders']['delivered'] ?? 0 }}
                    </div>
                </div>
                <div class="p-3 bg-lime-brand/20 rounded-xl text-lime-brand">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-lime-brand flex items-center">
                <span class="font-medium">Completed tasks</span>
            </div>
        </div>

        <!-- Revenue (Emerald) -->
        <div
            class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 p-6 transition hover:border-lime-brand/50">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-medium text-gray-400 uppercase tracking-wider">Revenue
                    </div>
                    <div class="mt-2 text-3xl font-bold text-white">
                        ${{ number_format($stats['revenue']['total_collected'], 0) }}</div>
                </div>
                <div class="p-3 bg-green-900/20 rounded-xl text-green-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-400 flex items-center">
                <span>Pending: ${{ number_format($stats['revenue']['pending_collection'], 0) }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column (Chart & Table) -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Charts Section -->
            <div class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 p-6">
                <h3 class="text-lg font-bold text-white mb-6">Order Status Distribution</h3>
                <div class="h-64 flex justify-center">
                    <canvas id="ordersChart" class="max-h-full"></canvas>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-800 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Recent Orders</h3>
                    <a href="{{ route('orders.index') }}"
                        class="text-lime-brand hover:text-lime-400 text-sm font-medium">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-black">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Order</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Client</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-[#1E1E1E] divide-y divide-gray-800">
                            @forelse($stats['recent_orders'] as $order)
                                <tr class="hover:bg-gray-800 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                        #{{ $order->order_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                        {{ $order->client->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $color = match ($order->status->name) {
                                                'PENDING' => 'yellow',
                                                'IN_TRANSIT' => 'blue',
                                                'DELIVERED' => 'green',
                                                'CANCELLED' => 'red',
                                                default => 'gray'
                                            };
                                            // Fix for Green -> Lime in some cases if desired, or keep standard colors
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-900/30 text-{{ $color }}-400 border border-{{ $color }}-700/50">
                                            {{ str_replace('_', ' ', $order->status->name) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('orders.show', $order->uuid) }}"
                                            class="text-lime-brand hover:text-lime-400">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">No orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-8">

            <!-- Top Clients -->
            <div class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">Top Clients</h3>
                </div>
                <div class="p-0">
                    <ul class="divide-y divide-gray-800">
                        @forelse($stats['top_clients'] as $client)
                            <li class="p-4 flex items-center justify-between hover:bg-gray-800 transition">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-lime-brand flex items-center justify-center text-black font-bold">
                                        {{ substr($client->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-white">{{ $client->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $client->contact_person ?? 'No contact' }}</p>
                                    </div>
                                </div>
                                <div class="text-sm font-semibold text-gray-400">
                                    {{ $client->orders_count }} Orders
                                </div>
                            </li>
                        @empty
                            <li class="p-4 text-center text-gray-500 text-sm">No clients yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800">
                <div class="p-6 border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">Activity Feed</h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @forelse($stats['recent_events'] as $event)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-800"
                                                aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-[#1E1E1E] bg-lime-brand">
                                                    <svg class="h-4 w-4 text-black" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-400">
                                                        {{ $event->description }}
                                                        <a href="{{ route('orders.show', $event->order->uuid ?? '') }}"
                                                            class="font-medium text-white hover:text-lime-brand transition">
                                                            #{{ $event->order->order_number ?? 'N/A' }}
                                                        </a>
                                                    </p>
                                                </div>
                                                <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                    {{ $event->created_at->diffForHumans(null, true, true) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-gray-500 text-center text-sm">No recent activity.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script type="module">
        import Chart from 'chart.js/auto';

        const ctx = document.getElementById('ordersChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Transit', 'Delivered', 'Cancelled'],
                    datasets: [{
                        label: 'Orders',
                        data: [
                            {{ $stats['orders']['pending'] ?? 0 }},
                            {{ $stats['orders']['in_transit'] ?? 0 }},
                            {{ $stats['orders']['delivered'] ?? 0 }},
                            {{ $stats['orders']['cancelled'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#fbbf24', // yellow
                            '#3b82f6', // blue
                            '#cff700', // lime (was green)
                            '#ef4444'  // red
                        ],
                        hoverOffset: 4,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                color: '#9CA3AF' // gray-400
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    </script>
</x-app-layout>