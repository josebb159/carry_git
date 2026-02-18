<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">Orders</h2>
            <p class="text-gray-400">Manage your logistics orders</p>
        </div>
        <a href="{{ route('orders.create') }}" class="bg-lime-brand hover:bg-lime-400 text-black px-4 py-2 rounded-lg text-sm font-bold transition-colors">
            New Order
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 p-4 mb-6">
        <form method="GET" action="{{ route('orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, Client..." class="w-full rounded-lg bg-black border-gray-700 text-white focus:ring-lime-brand focus:border-lime-brand text-sm placeholder-gray-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg bg-black border-gray-700 text-white focus:ring-lime-brand focus:border-lime-brand text-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex items-end">
                <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors mr-2">
                    Filter
                </button>
                <a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-white px-4 py-2 text-sm font-medium">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-black">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Carrier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-[#1E1E1E] divide-y divide-gray-800">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium text-lime-brand">#{{ $order->order_number }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-white">{{ $order->client->legal_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-400">{{ $order->carrier->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = match($order->status->value) {
                                        'pending' => 'bg-yellow-900/30 text-yellow-400 border border-yellow-700/50',
                                        'in_transit' => 'bg-blue-900/30 text-blue-400 border border-blue-700/50',
                                        'delivered' => 'bg-lime-brand/20 text-lime-brand border border-lime-brand/30',
                                        'incident' => 'bg-red-900/30 text-red-400 border border-red-700/50',
                                        default => 'bg-gray-800 text-gray-400',
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status->value)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('orders.show', $order->uuid) }}" class="text-lime-brand hover:text-lime-300">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                No orders found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-800">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
