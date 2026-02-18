<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <nav class="flex mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('orders.index') }}" class="text-sm font-medium text-gray-400 hover:text-white">Orders</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-sm font-medium text-gray-400">#{{ $order->order_number }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-2xl font-bold text-white">Order Details</h2>
        </div>
        <div class="flex space-x-3">
            <button class="bg-black border border-gray-700 text-gray-300 hover:bg-gray-800 px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors">
                Generate Invoice
            </button>
            <button class="bg-lime-brand hover:bg-lime-400 text-black px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors">
                Add Event
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Order Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- General Info Card -->
            <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 p-6">
                <div class="flex justify-between items-start mb-6">
                    <h3 class="text-lg font-bold text-white">General Information</h3>
                    @php
                        $statusClasses = match($order->status->value) {
                            'pending' => 'bg-yellow-900/30 text-yellow-400 border border-yellow-700/50',
                            'in_transit' => 'bg-blue-900/30 text-blue-400 border border-blue-700/50',
                            'delivered' => 'bg-lime-brand/20 text-lime-brand border border-lime-brand/30',
                            'incident' => 'bg-red-900/30 text-red-400 border border-red-700/50',
                            default => 'bg-gray-800 text-gray-400',
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusClasses }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status->value)) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-400 mb-1">Client</p>
                        <p class="text-white font-semibold">{{ $order->client->legal_name }}</p>
                        <p class="text-sm text-gray-400">{{ $order->client->address }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400 mb-1">Carrier</p>
                        <p class="text-white font-semibold">{{ $order->carrier->name ?? 'Not Assigned' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400 mb-1">Total Amount</p>
                        <p class="text-white font-semibold">${{ number_format($order->total_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400 mb-1">Created At</p>
                        <p class="text-white">{{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Locations (Route) -->
            <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 p-6">
                <h3 class="text-lg font-bold text-white mb-6">Route</h3>
                <div class="relative pl-4 border-l-2 border-gray-700 space-y-8">
                    @foreach($order->locations as $location)
                        <div class="relative">
                            <span class="absolute -left-[21px] top-1 h-4 w-4 rounded-full border-2 border-[#1E1E1E] {{ $location->type === 'pickup' ? 'bg-lime-brand' : 'bg-green-500' }}"></span>
                            <div class="bg-black rounded-lg p-4 border border-gray-800">
                                <p class="text-xs font-bold uppercase text-gray-500 mb-1">{{ $location->type }}</p>
                                <p class="font-semibold text-white">{{ $location->city }}, {{ $location->country }}</p>
                                <p class="text-sm text-gray-400">{{ $location->address }}</p>
                                @if($location->scheduled_at)
                                    <p class="text-sm text-lime-brand mt-2 font-medium">Scheduled: {{ $location->scheduled_at->format('M d, Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column: Timeline -->
        <div class="lg:col-span-1">
            <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 p-6 h-full">
                <h3 class="text-lg font-bold text-white mb-6">Event Timeline</h3>
                
                <div class="flow-root">
                    <ul class="-mb-8">
                        @forelse($order->events->sortByDesc('created_at') as $event)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-700" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @php
                                                $iconColor = match($event->type->value) {
                                                    'incident' => 'bg-red-500',
                                                    'delivered' => 'bg-green-500',
                                                    default => 'bg-gray-600',
                                                };
                                            @endphp
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-[#1E1E1E] {{ $iconColor }}">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-medium text-white">{{ ucfirst(str_replace('_', ' ', $event->type->value)) }}</p>
                                                <p class="text-sm text-gray-400">{{ $event->description }}</p>
                                                @if($event->location)
                                                    <p class="text-xs text-gray-500 mt-1 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                        {{ is_array($event->location) ? implode(', ', $event->location) : $event->location }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <time>{{ $event->created_at->format('M d H:i') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <p class="text-gray-500 text-center italic">No events recorded yet.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
