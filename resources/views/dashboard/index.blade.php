<x-app-layout>
@if($stats['is_client'] ?? false)
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-white">Hola, {{ auth()->user()->name }}</h2>
            <p class="text-gray-400 mt-1">Gestiona tu operativa de transporte en tiempo real</p>
        </div>
        <div>
            <a href="{{ route('orders.create') }}"
                class="inline-flex items-center px-8 py-3 bg-lime-brand hover:bg-lime-400 text-black font-bold rounded-full transition-all duration-300 shadow-[0_0_20px_rgba(207,247,0,0.3)] hover:scale-105 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Solicitud
            </a>
        </div>
    </div>

    <!-- Live Tracking Highlight (If any) -->
    @if($stats['live_tracking'])
    <div class="mb-8 relative overflow-hidden bg-gradient-to-r from-gray-900 to-[#1A1A1A] rounded-3xl border border-lime-brand/30 p-1">
        <div class="absolute top-0 right-0 p-4">
            <span class="flex h-3 w-3 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-lime-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-lime-500"></span>
            </span>
        </div>
        <div class="bg-[#121212]/90 backdrop-blur-xl rounded-[calc(1.5rem-1px)] p-6 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-lime-brand/10 rounded-2xl text-lime-brand border border-lime-brand/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white uppercase tracking-tight">Seguimiento en Vivo</h3>
                    <p class="text-gray-400">Órden <span class="text-lime-brand font-mono">#{{ $stats['live_tracking']->order->order_number }}</span> • {{ $stats['live_tracking']->order->status->name }}</p>
                </div>
            </div>
            
            <div class="hidden md:block h-12 w-px bg-gray-800"></div>

            <div class="flex-1 grid grid-cols-2 md:grid-cols-3 gap-4 w-full md:w-auto">
                <div class="text-center md:text-left">
                    <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Latitud</div>
                    <div class="text-white font-mono">{{ number_format($stats['live_tracking']->lat, 6) }}</div>
                </div>
                <div class="text-center md:text-left">
                    <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Longitud</div>
                    <div class="text-white font-mono">{{ number_format($stats['live_tracking']->lng, 6) }}</div>
                </div>
                <div class="col-span-2 md:col-span-1 text-center md:text-left">
                    <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Velocidad</div>
                    <div class="text-lime-brand font-bold">{{ $stats['live_tracking']->speed ?? 0 }} km/h</div>
                </div>
            </div>

            <a href="{{ route('orders.show', $stats['live_tracking']->order->uuid) }}" 
               class="w-full md:w-auto px-6 py-2 bg-white/5 hover:bg-white/10 text-white text-sm font-semibold rounded-xl border border-white/10 transition-colors text-center">
                Ver Mapa Detallado
            </a>
        </div>
    </div>
    @endif

    <!-- Client Widgets Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- In Transit -->
        <a href="{{ route('orders.index', ['status' => 'in_transit']) }}"
            class="group bg-[#1E1E1E] hover:bg-[#252525] rounded-3xl border border-gray-800 p-8 transition-all duration-300 hover:border-blue-500/50 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="w-32 h-32 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Cargas en Tránsito</div>
                    <div class="text-4xl font-black text-white group-hover:text-blue-400 transition-colors">
                        {{ $stats['orders']['in_transit'] ?? 0 }}
                    </div>
                </div>
                <div class="p-4 bg-blue-500/10 rounded-2xl text-blue-400 border border-blue-500/20 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 flex items-center text-xs text-blue-400 font-bold uppercase tracking-wider">
                <span>Ver envíos activos</span>
                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </div>
        </a>

        <!-- Pending -->
        <a href="{{ route('orders.index', ['status' => 'pending']) }}"
            class="group bg-[#1E1E1E] hover:bg-[#252525] rounded-3xl border border-gray-800 p-8 transition-all duration-300 hover:border-yellow-500/50 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="w-32 h-32 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Cargas Pendientes</div>
                    <div class="text-4xl font-black text-white group-hover:text-yellow-400 transition-colors">
                        {{ $stats['orders']['pending'] ?? 0 }}
                    </div>
                </div>
                <div class="p-4 bg-yellow-500/10 rounded-2xl text-yellow-400 border border-yellow-500/20 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 flex items-center text-xs text-yellow-400 font-bold uppercase tracking-wider">
                <span>Esperando validación</span>
                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </div>
        </a>

        <!-- History (Delivered + Completed) -->
        <a href="{{ route('orders.index', ['status' => 'delivered']) }}"
            class="group bg-[#1E1E1E] hover:bg-[#252525] rounded-3xl border border-gray-800 p-8 transition-all duration-300 hover:border-lime-brand/50 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="w-32 h-32 text-lime-brand" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Historial de Cargas</div>
                    <div class="text-4xl font-black text-white group-hover:text-lime-brand transition-colors">
                        {{ ($stats['orders']['delivered'] ?? 0) + ($stats['orders']['completed'] ?? 0) }}
                    </div>
                </div>
                <div class="p-4 bg-lime-brand/10 rounded-2xl text-lime-brand border border-lime-brand/20 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 flex items-center text-xs text-lime-brand font-bold uppercase tracking-wider">
                <span>Consultar finalizados</span>
                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </div>
        </a>
    </div>

    <!-- Client Recent Orders & Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-[#1E1E1E] rounded-3xl shadow-sm border border-gray-800 overflow-hidden">
                <div class="p-8 border-b border-gray-800 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-white">Últimas Cargas Solicitadas</h3>
                        <p class="text-sm text-gray-500 mt-1">Monitorea el estado de tus envíos recientes</p>
                    </div>
                    <a href="{{ route('orders.index') }}" class="text-lime-brand hover:text-lime-400 text-sm font-bold uppercase tracking-wider">Ver Todo</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-black/50">
                            <tr>
                                <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Servicio</th>
                                <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Estado</th>
                                <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Fecha</th>
                                <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-500 uppercase tracking-widest">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @forelse($stats['recent_orders'] as $order)
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-xl bg-gray-800 flex items-center justify-center text-gray-400 group-hover:bg-lime-brand/10 group-hover:text-lime-brand transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-white">#{{ $order->order_number }}</div>
                                                <div class="text-xs text-gray-500">Ref: {{ substr($order->uuid, 0, 8) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        @php
                                            $color = match ($order->status->name) {
                                                'PENDING' => 'yellow',
                                                'IN_TRANSIT' => 'blue',
                                                'DELIVERED', 'COMPLETED' => 'lime',
                                                'CANCELLED' => 'red',
                                                default => 'gray'
                                            };
                                            $status_name = match ($order->status->name) {
                                                'PENDING' => 'PENDIENTE',
                                                'IN_TRANSIT' => 'EN RUTA',
                                                'DELIVERED' => 'ENTREGADO',
                                                'COMPLETED' => 'COMPLETADO',
                                                'CANCELLED' => 'CANCELADO',
                                                default => str_replace('_', ' ', $order->status->name)
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $color === 'lime' ? 'bg-lime-brand/10 text-lime-brand border border-lime-brand/20' : "bg-{$color}-500/10 text-{$color}-400 border border-{$color}-500/20" }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                            {{ $status_name }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-400 font-medium">
                                        {{ $order->created_at->format('d/m/Y') }}
                                        <div class="text-[10px] text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <a href="{{ route('orders.show', $order->uuid) }}"
                                            class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-gray-800 text-white hover:bg-lime-brand hover:text-black transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center">
                                        <div class="text-gray-500 italic">No tienes cargas recientes.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <!-- Activity Timeline -->
            <div class="bg-[#1E1E1E] rounded-3xl shadow-sm border border-gray-800 p-8 h-full">
                <h3 class="text-xl font-bold text-white mb-8">Seguimiento Detallado</h3>
                <div class="flow-root">
                    <ul class="-mb-8">
                        @forelse($stats['recent_events'] as $event)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-800" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-4">
                                        <div>
                                            <span class="h-8 w-8 rounded-xl flex items-center justify-center ring-4 ring-[#1E1E1E] bg-gray-800 text-lime-brand">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <p class="text-sm text-white font-medium">
                                                    {{ $event->description }}
                                                </p>
                                                <p class="mt-0.5 text-xs text-gray-500">
                                                    Órden #{{ $event->order->order_number ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="mt-2 text-xs text-gray-600 font-mono tracking-tighter">
                                                {{ $event->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="text-gray-500 text-center py-4 italic">Sin eventos recientes.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-white">Panel Administrativo</h2>
            <p class="text-gray-400 mt-1">Resumen general de las operaciones logísticas</p>
        </div>
        <div class="space-x-3">
            <a href="{{ route('orders.create') }}"
                class="inline-flex items-center px-4 py-2 bg-lime-brand border border-transparent rounded-lg font-bold text-xs text-black uppercase tracking-widest hover:bg-lime-400 active:bg-lime-500 focus:outline-none focus:border-lime-700 focus:ring ring-lime-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Órden
            </a>
        </div>
    </div>
@endif

@if(!($stats['is_client'] ?? false))
    <!-- KPI Cards (Admin) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stats... (Mismas que antes pero dentro del condicional) -->
        <div class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 p-6 transition hover:border-lime-brand/50">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-medium text-gray-400 uppercase tracking-wider">Órdenes Pendientes</div>
                    <div class="mt-2 text-3xl font-bold text-white">{{ $stats['orders']['pending'] ?? 0 }}</div>
                </div>
                <div class="p-3 bg-yellow-900/20 rounded-xl text-yellow-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 p-6 transition hover:border-lime-brand/50">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-medium text-gray-400 uppercase tracking-wider">En Tránsito</div>
                    <div class="mt-2 text-3xl font-bold text-white">{{ $stats['orders']['in_transit'] ?? 0 }}</div>
                </div>
                <div class="p-3 bg-blue-900/20 rounded-xl text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 p-6 transition hover:border-lime-brand/50">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-medium text-gray-400 uppercase tracking-wider">Entregadas</div>
                    <div class="mt-2 text-3xl font-bold text-white">{{ $stats['orders']['delivered'] ?? 0 }}</div>
                </div>
                <div class="p-3 bg-lime-brand/20 rounded-xl text-lime-brand">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 p-6 transition hover:border-lime-brand/50">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs font-medium text-gray-400 uppercase tracking-wider">Ingresos</div>
                    <div class="mt-2 text-3xl font-bold text-white">${{ number_format($stats['revenue']['total_collected'], 0) }}</div>
                </div>
                <div class="p-3 bg-green-900/20 rounded-xl text-green-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid (Admin) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 p-6">
                <h3 class="text-lg font-bold text-white mb-6">Distribución de Órdenes</h3>
                <div class="h-64 flex justify-center">
                    <canvas id="ordersChart" class="max-h-full"></canvas>
                </div>
            </div>

            <div class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-800 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Órdenes Recientes</h3>
                    <a href="{{ route('orders.index') }}" class="text-lime-brand hover:text-lime-400 text-sm font-medium">Ver Todo</a>
                </div>
                <!-- ... Tabla de órdenes similar ... -->
            </div>
        </div>
        <div class="space-y-8">
            <div class="bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-800">
                    <h3 class="text-lg font-bold text-white">Mejores Clientes</h3>
                </div>
                <!-- ... Lista de clientes ... -->
            </div>
        </div>
    </div>
@endif


    <script type="module">
        import Chart from 'chart.js/auto';

        const ctx = document.getElementById('ordersChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pendiente', 'En Tránsito', 'Entregada', 'Cancelada'],
                    datasets: [{
                        label: 'Órdenes',
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
