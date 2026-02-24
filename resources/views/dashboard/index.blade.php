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

    @if($stats['client_profile'])
    <div class="mb-8 bg-black/40 border border-gray-800 rounded-2xl p-4 flex items-center justify-between">
        <div class="flex items-center">
            <div class="p-2 bg-blue-500/10 rounded-lg text-blue-400 mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div>
                <span class="text-gray-400 text-sm">Soporte 24/7:</span>
                <span class="text-white font-bold ml-2">{{ $stats['client_profile']->payment_terms_days }} días al mes</span>
            </div>
        </div>
    </div>
    @endif
@else
    <div class="mb-8 flex justify-between items-center text-white">
        <div>
            <h2 class="text-3xl font-bold">Panel Administrativo</h2>
            <p class="text-gray-400 mt-1">Visión global de la flota y operaciones</p>
        </div>
        <div class="space-x-3">
            <a href="{{ route('orders.create') }}"
                class="inline-flex items-center px-4 py-2 bg-lime-brand border border-transparent rounded-lg font-bold text-xs text-black uppercase tracking-widest hover:bg-lime-400 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Órden
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-[#1E1E1E] rounded-2xl border border-gray-800 p-6 flex flex-col justify-between h-32 hover:border-yellow-500/50 transition-all duration-300 group cursor-default">
            <div class="flex justify-between items-start">
                <div class="text-xs font-black text-gray-500 uppercase tracking-widest group-hover:text-yellow-500 transition-colors">Órdenes Pendientes</div>
                <div class="p-2 bg-yellow-500/10 rounded-lg text-yellow-500 shadow-[0_0_15px_rgba(234,179,8,0.1)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-white group-hover:scale-105 transition-transform origin-left">{{ $stats['orders']['PENDING'] ?? $stats['orders']['pending'] ?? 0 }}</div>
        </div>

        <div class="bg-[#1E1E1E] rounded-2xl border border-gray-800 p-6 flex flex-col justify-between h-32 hover:border-blue-500/50 transition-all duration-300 group cursor-default">
            <div class="flex justify-between items-start">
                <div class="text-xs font-black text-gray-500 uppercase tracking-widest group-hover:text-blue-500 transition-colors">En Tránsito</div>
                <div class="p-2 bg-blue-500/10 rounded-lg text-blue-500 shadow-[0_0_15px_rgba(59,130,246,0.1)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-white group-hover:scale-105 transition-transform origin-left">{{ $stats['orders']['IN_TRANSIT'] ?? $stats['orders']['in_transit'] ?? 0 }}</div>
        </div>

        <div class="bg-[#1E1E1E] rounded-2xl border border-gray-800 p-6 flex flex-col justify-between h-32 hover:border-lime-brand/50 transition-all duration-300 group cursor-default">
            <div class="flex justify-between items-start">
                <div class="text-xs font-black text-gray-500 uppercase tracking-widest group-hover:text-lime-brand transition-colors">Entregadas</div>
                <div class="p-2 bg-lime-brand/10 rounded-lg text-lime-brand shadow-[0_0_15px_rgba(207,247,0,0.1)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-white group-hover:scale-105 transition-transform origin-left">{{ $stats['orders']['DELIVERED'] ?? $stats['orders']['delivered'] ?? 0 }}</div>
        </div>

        <div class="bg-[#1E1E1E] rounded-2xl border border-gray-800 p-6 flex flex-col justify-between h-32 hover:border-white/20 transition-all duration-300 group cursor-default text-white">
            <div class="flex justify-between items-start">
                <div class="text-xs font-black text-gray-500 uppercase tracking-widest group-hover:text-white transition-colors">Facturación</div>
                <div class="p-2 bg-white/5 rounded-lg text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black">${{ number_format($stats['revenue']['total_collected'], 0) }}</div>
        </div>
    </div>
@endif

    <div class="mb-8">
        <div class="bg-[#1E1E1E] rounded-3xl border border-gray-800 overflow-hidden shadow-2xl relative">
            <div class="p-6 border-b border-gray-800 flex justify-between items-center bg-black/40">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <span class="flex h-3 w-3 relative mr-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-lime-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-lime-500"></span>
                    </span>
                    Seguimiento de Flota (Google Maps Premium)
                </h3>
                <span class="text-sm font-medium text-gray-400 uppercase tracking-widest">
                    {{ count($stats['in_transit_tracking']) }} {{ Str::plural('Carga', count($stats['in_transit_tracking'])) }} en movimiento
                </span>
            </div>
            
            <div id="googleMap" class="h-[600px] w-full bg-[#121212]">
                @if(empty($stats['in_transit_tracking']))
                    <div class="h-full w-full flex flex-col items-center justify-center text-gray-600 space-y-4">
                        <svg class="w-16 h-16 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A2 2 0 013 15.487V4.512a2 2 0 011.053-1.789L9 0l6 3 5.447-2.724A2 2 0 0121 2.053v10.975a2 2 0 01-1.053 1.789L15 24l-6-4z"></path>
                        </svg>
                        <p class="text-xl font-medium">Sin vehículos en ruta actualmente</p>
                    </div>
                @endif
            </div>

            @if(!empty($stats['in_transit_tracking']))
            <div class="absolute bottom-6 left-6 right-6 flex gap-4 overflow-x-auto pb-4 scrollbar-hide z-10">
                @foreach($stats['in_transit_tracking'] as $tp)
                    @php 
                        $tp = (object)$tp; 
                        $order = (object)$tp->order;
                    @endphp
                    <div onclick="focusMarker('{{ $order->uuid }}')" class="cursor-pointer flex-shrink-0 bg-black/90 backdrop-blur-xl border border-white/10 rounded-2xl p-4 w-72 shadow-2xl hover:border-lime-brand/50 transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-black text-lime-brand">#{{ $order->order_number }}</span>
                            <span class="px-2 py-0.5 {{ ($order->cargo_type == 'Refrigerada') ? 'bg-blue-500/20 text-blue-400' : 'bg-lime-brand/20 text-lime-brand' }} text-[10px] rounded-full font-black uppercase">
                                {{ $order->cargo_type ?? 'General' }}
                            </span>
                        </div>
                        <div class="text-sm text-white font-bold mb-1 truncate">{{ $order->client['legal_name'] ?? 'N/A' }}</div>
                        <div class="flex items-center justify-between mt-3">
                            <div class="text-[10px] text-gray-500 uppercase font-bold tracking-tighter">
                                <span class="text-white">{{ $tp->speed ?? 0 }} km/h</span> • {{ $order->carrier['name'] ?? 'Asignando...' }}
                            </div>
                            <div class="w-2 h-2 rounded-full bg-lime-brand animate-pulse"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-[#1E1E1E] rounded-3xl shadow-sm border border-gray-800 overflow-hidden">
                <div class="p-8 border-b border-gray-800 flex justify-between items-center text-white">
                    <h3 class="text-xl font-bold">Últimas Órdenes</h3>
                    <a href="{{ route('orders.index') }}" class="text-lime-brand hover:text-lime-400 text-sm font-bold uppercase tracking-wider transition">Ver Historial</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-black/50">
                            <tr>
                                <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Órden</th>
                                <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Cliente</th>
                                <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Estado</th>
                                <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-500 uppercase tracking-widest">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @foreach($stats['recent_orders'] as $order)
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-bold text-lime-brand">#{{ $order->order_number }}</div>
                                        <div class="text-[10px] text-gray-600">{{ $order->cargo_type }}</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm text-white font-medium">{{ $order->client->legal_name }}</td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        @php
                                            $statusName = is_string($order->status) ? $order->status : $order->status->name;
                                            $color = match ($statusName) {
                                                'PENDING' => 'yellow',
                                                'IN_TRANSIT' => 'blue',
                                                'DELIVERED', 'COMPLETED' => 'lime',
                                                default => 'gray'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $color === 'lime' ? 'bg-lime-brand/10 text-lime-brand' : "bg-{$color}-500/10 text-{$color}-400" }}">
                                            {{ str_replace('_', ' ', $statusName) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('orders.show', $order->uuid) }}" class="p-2 hover:bg-lime-brand/10 rounded-lg text-gray-400 hover:text-lime-brand transition-all inline-block">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            @if(!($stats['is_client'] ?? false))
                <div class="bg-[#1E1E1E] rounded-3xl border border-gray-800 overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-gray-800 text-white">
                        <h3 class="text-lg font-bold">Mejores Clientes</h3>
                    </div>
                    <div class="divide-y divide-gray-800">
                        @foreach($stats['top_clients'] as $client)
                            <div class="p-4 flex items-center justify-between hover:bg-black/20 transition-colors">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-xl bg-lime-brand/10 text-lime-brand flex items-center justify-center font-bold text-lg">
                                        {{ substr($client->legal_name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-white">{{ $client->legal_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $client->orders_count }} órdenes ejecutadas</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-[#1E1E1E] rounded-3xl border border-gray-800 p-8">
                <h3 class="text-xl font-bold text-white mb-6">Actividad de Flota</h3>
                <div class="space-y-6">
                    @foreach($stats['recent_events'] as $event)
                        <div class="flex gap-4">
                            <div class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-lime-brand shadow-[0_0_10px_rgba(207,247,0,0.5)]"></div>
                            <div>
                                <p class="text-xs text-white">{{ $event->description }}</p>
                                <p class="text-[10px] text-gray-600 font-mono mt-1 uppercase tracking-tighter">{{ $event->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @if(!empty($stats['in_transit_tracking']))
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>
    <script>
        let map;
        let markers = {};
        let infoWindow;

        const nightStyle = [
            { "elementType": "geometry", "stylers": [{ "color": "#242f3e" }] },
            { "elementType": "labels.text.stroke", "stylers": [{ "color": "#242f3e" }] },
            { "elementType": "labels.text.fill", "stylers": [{ "color": "#746855" }] },
            { "featureType": "administrative.locality", "elementType": "labels.text.fill", "stylers": [{ "color": "#d59563" }] },
            { "featureType": "poi", "elementType": "labels.text.fill", "stylers": [{ "color": "#d59563" }] },
            { "featureType": "road", "elementType": "geometry", "stylers": [{ "color": "#38414e" }] },
            { "featureType": "road", "elementType": "geometry.stroke", "stylers": [{ "color": "#212a37" }] },
            { "featureType": "road", "elementType": "labels.text.fill", "stylers": [{ "color": "#9ca5b9" }] },
            { "featureType": "road.highway", "elementType": "geometry", "stylers": [{ "color": "#746855" }] },
            { "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{ "color": "#1f2835" }] },
            { "featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [{ "color": "#f3d19c" }] },
            { "featureType": "water", "elementType": "geometry", "stylers": [{ "color": "#17263c" }] }
        ];

        function initMap() {
            const mapOptions = {
                zoom: 5,
                center: { lat: 40.4168, lng: -3.7038 },
                styles: nightStyle,
                disableDefaultUI: true,
                zoomControl: true,
            };
            
            map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);
            infoWindow = new google.maps.InfoWindow();

            const trackingData = @json($stats['in_transit_tracking']);
            const bounds = new google.maps.LatLngBounds();

            trackingData.forEach(point => {
                const isRefrigerated = point.order.cargo_type === 'Refrigerada';
                const color = isRefrigerated ? '#00A3FF' : '#CCFF00';
                
                const truckIcon = {
                    path: 'M20,8H17V4H3C1.9,4,1,4.9,1,6v10h2c0,1.66,1.34,3,3,3s3-1.34,3-3h6c0,1.66,1.34,3,3,3s3-1.34,3-3h2v-5L20,8z M6,17.2c-0.66,0-1.2-0.54-1.2-1.2c0-0.66,0.54-1.2,1.2-1.2s1.2,0.54,1.2,1.2C7.2,16.66,6.66,17.2,6,17.2z M17,17.2c-0.66,0-1.2-0.54-1.2-1.2c0-0.66,0.54-1.2,1.2-1.2s1.2,0.54,1.2,1.2C18.2,16.66,17.66,17.2,17,17.2z M17,12V9h2.2l1.8,3H17z',
                    fillColor: color,
                    fillOpacity: 1,
                    strokeWeight: 1,
                    strokeColor: '#000000',
                    scale: 1.5,
                    anchor: new google.maps.Point(12, 12),
                };

                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(point.lat), lng: parseFloat(point.lng) },
                    map: map,
                    icon: truckIcon,
                    title: `Órden #${point.order.order_number}`
                });

                const content = `
                    <div style="color: #000; padding: 10px; font-family: sans-serif;">
                        <h4 style="margin: 0; color: ${color}; font-weight: 800;">ÓRDEN #${point.order.order_number}</h4>
                        <p style="margin: 5px 0 0 0; font-size: 12px;"><b>Conductor:</b> ${point.order.carrier ? point.order.carrier.name : 'N/A'}</p>
                        ${point.order.temperature ? `<p style="margin: 3px 0 0 0; font-size: 12px;"><b>Temp:</b> <span style="color: #00A3FF; font-weight: bold;">${point.order.temperature}</span></p>` : ''}
                        <p style="margin: 3px 0 0 0; font-size: 11px; color: #666;"><b>Ciudad:</b> ${point.order.locations[0] ? point.order.locations[0].city : '---'}</p>
                    </div>
                `;

                marker.addListener("click", () => {
                    infoWindow.setContent(content);
                    infoWindow.open(map, marker);
                });

                markers[point.order.uuid] = marker;
                bounds.extend(marker.getPosition());
            });

            if (trackingData.length > 0) {
                map.fitBounds(bounds);
                if (trackingData.length === 1) map.setZoom(12);
            }
        }

        window.focusMarker = function(uuid) {
            if (markers[uuid]) {
                map.panTo(markers[uuid].getPosition());
                map.setZoom(14);
                google.maps.event.trigger(markers[uuid], 'click');
            }
        };
    </script>
    @endif
</x-app-layout>