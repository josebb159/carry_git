<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <a href="{{ route('dashboard') }}" class="text-lime-brand hover:text-lime-300 font-medium flex items-center mb-2 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Panel
            </a>
            <h2 class="text-3xl font-bold text-white">Nueva Solicitud de Transporte</h2>
            <p class="text-gray-400 mt-1">Completa los detalles de tu envío con precisión de Google Maps</p>
        </div>
    </div>

    <div class="max-w-5xl">
        <form method="POST" action="{{ route('orders.store') }}" class="space-y-8" x-data="{ cargoType: 'General' }">
            @csrf
            <input type="hidden" name="order_number" value="REQ-{{ strtoupper(Str::random(8)) }}">

            {{-- 01. CLIENTE Y CONTACTO --}}
            <div class="bg-[#1E1E1E]/50 backdrop-blur-xl rounded-3xl border border-gray-800 p-8">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <span class="w-8 h-8 bg-lime-brand/10 text-lime-brand rounded-full flex items-center justify-center mr-3 text-sm">01</span>
                    Facturación y Contacto
                </h3>
                
                @if($isClient && $myClient)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <input type="hidden" name="client_id" value="{{ $myClient->id }}">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-400">Razón Social</label>
                            <div class="text-white font-semibold py-2 px-1 border-b border-gray-800/50">{{ $myClient->legal_name }}</div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-400">NIF / CIF</label>
                            <div class="text-white font-semibold py-2 px-1 border-b border-gray-800/50">{{ $myClient->vat_number }}</div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-400">Contacto Logístico</label>
                            <input type="text" name="logistics_contact_name" value="{{ old('logistics_contact_name', $myClient->logistics_contact_name) }}" class="w-full bg-black/50 border-gray-800 text-white rounded-xl focus:border-lime-brand focus:ring-lime-brand text-sm">
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-400">Email de Gestión</label>
                            <input type="email" name="logistics_contact_email" value="{{ old('logistics_contact_email', $myClient->logistics_contact_email ?? auth()->user()->email) }}" class="w-full bg-black/50 border-gray-800 text-white rounded-xl focus:border-lime-brand focus:ring-lime-brand text-sm">
                        </div>
                    </div>
                @else
                    <div class="space-y-6">
                        <x-input-label for="client_id" value="Seleccionar Cliente" class="text-gray-400 mb-2" />
                        <select name="client_id" id="client_id" class="w-full bg-black border-gray-800 text-white rounded-xl focus:border-lime-brand focus:ring-lime-brand">
                            <option value="">Seleccione un cliente...</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->legal_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            {{-- 02. RUTA (CON AUTOCOMPLETE) --}}
            <div class="bg-[#1E1E1E]/50 backdrop-blur-xl rounded-3xl border border-gray-800 p-8">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <span class="w-8 h-8 bg-lime-brand/10 text-lime-brand rounded-full flex items-center justify-center mr-3 text-sm">02</span>
                    Origen y Destino (Logística)
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    {{-- Origen --}}
                    <div class="space-y-6">
                        <h4 class="text-lime-brand font-black uppercase tracking-widest text-[10px]">Punto de Recogida (Origen)</h4>
                        <input type="hidden" name="locations[0][type]" value="pickup">
                        <input type="hidden" name="locations[0][sequence]" value="1">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Dirección o Establecimiento</label>
                                <x-text-input id="address_pickup" name="locations[0][address]" placeholder="Empieza a escribir..." class="w-full bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.0.address')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <x-text-input id="city_pickup" name="locations[0][city]" placeholder="Ciudad" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.0.city')" required />
                                <x-text-input id="zip_pickup" name="locations[0][zip_code]" placeholder="Código Postal" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.0.zip_code')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <x-text-input id="state_pickup" name="locations[0][state]" placeholder="Provincia" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.0.state')" required />
                                <x-text-input id="country_pickup" name="locations[0][country]" placeholder="País" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.0.country', 'España')" required />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Fecha/Hora Recogida</label>
                                <x-text-input type="datetime-local" name="locations[0][scheduled_at]" class="w-full bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.0.scheduled_at')" />
                            </div>
                        </div>
                    </div>

                    {{-- Destino --}}
                    <div class="space-y-6">
                        <h4 class="text-blue-400 font-black uppercase tracking-widest text-[10px]">Punto de Entrega (Destino)</h4>
                        <input type="hidden" name="locations[1][type]" value="delivery">
                        <input type="hidden" name="locations[1][sequence]" value="2">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Dirección o Establecimiento</label>
                                <x-text-input id="address_delivery" name="locations[1][address]" placeholder="Empieza a escribir..." class="w-full bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.1.address')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <x-text-input id="city_delivery" name="locations[1][city]" placeholder="Ciudad" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.1.city')" required />
                                <x-text-input id="zip_delivery" name="locations[1][zip_code]" placeholder="Código Postal" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.1.zip_code')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <x-text-input id="state_delivery" name="locations[1][state]" placeholder="Provincia" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.1.state')" required />
                                <x-text-input id="country_delivery" name="locations[1][country]" placeholder="País" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.1.country', 'España')" required />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Fecha/Hora Entrega</label>
                                <x-text-input type="datetime-local" name="locations[1][scheduled_at]" class="w-full bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.1.scheduled_at')" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-gray-800 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <x-input-label for="cargo_type" value="Tipo de Mercancía" class="text-gray-400 mb-2" />
                        <select name="cargo_type" id="cargo_type" x-model="cargoType" class="w-full bg-black border-gray-800 text-white rounded-xl focus:border-lime-brand focus:ring-lime-brand">
                            <option value="General">Carga General</option>
                            <option value="Refrigerada">Temperatura Controlada</option>
                            <option value="Peligrosa (ADR)">ADR / Peligrosa</option>
                            <option value="Granel">Granel</option>
                        </select>
                    </div>

                    <div x-show="cargoType === 'Refrigerada'" x-transition class="col-span-1">
                        <x-input-label for="temperature" value="Temperatura Requerida (ºC)" class="text-lime-brand mb-2" />
                        <x-text-input name="temperature" id="temperature" placeholder="Ej: +4ºC" class="w-full bg-black border-lime-brand/30 text-white rounded-xl focus:border-lime-brand focus:ring-lime-brand" :value="old('temperature')" />
                    </div>

                    <div :class="cargoType === 'Refrigerada' ? 'md:col-span-2' : ''">
                        <x-input-label for="notes" value="Observaciones" class="text-gray-400 mb-2" />
                        <textarea name="notes" rows="1" class="w-full bg-black border-gray-800 text-white rounded-xl focus:border-lime-brand focus:ring-lime-brand" placeholder="Ej: Muelle 12...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 03. EXTRAS --}}
            <div class="bg-[#1E1E1E]/50 backdrop-blur-xl rounded-3xl border border-gray-800 p-8">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <span class="w-8 h-8 bg-lime-brand/10 text-lime-brand rounded-full flex items-center justify-center mr-3 text-sm">03</span>
                    Gestión CMR y Albarán
                </h3>
                <div class="flex gap-8">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="request_cmr" value="1" class="rounded bg-black border-gray-800 text-lime-brand focus:ring-lime-brand" checked>
                        <span class="ml-2 text-gray-400 text-sm">Solicitar CMR Digital</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="request_delivery_note" value="1" class="rounded bg-black border-gray-800 text-blue-500 focus:ring-blue-500" checked>
                        <span class="ml-2 text-gray-400 text-sm">Solicitar Albarán Digital</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-6 pb-20">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-white font-medium transition">Cancelar</a>
                <button type="submit" class="px-12 py-4 bg-lime-brand hover:bg-lime-400 text-black font-extrabold rounded-full transition-all duration-300 shadow-[0_0_30px_rgba(207,247,0,0.2)] hover:scale-105">
                    Confirmar Envío
                </button>
            </div>
        </form>
    </div>

    <!-- GOOGLE PLACES AUTOCOMPLETE -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initAutocomplete" async defer></script>
    <script>
        function initAutocomplete() {
            initSingleAutocomplete('address_pickup', 'pickup');
            initSingleAutocomplete('address_delivery', 'delivery');
        }

        function initSingleAutocomplete(inputId, prefix) {
            const input = document.getElementById(inputId);
            const autocomplete = new google.maps.places.Autocomplete(input, {
                componentRestrictions: { country: ["es"] },
                fields: ["address_components", "formatted_address"]
            });

            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();
                if (!place.address_components) return;

                let city = '', zip = '', state = '', country = '';

                place.address_components.forEach(component => {
                    const types = component.types;
                    if (types.includes("locality")) city = component.long_name;
                    if (types.includes("postal_code")) zip = component.long_name;
                    if (types.includes("administrative_area_level_2")) state = component.long_name;
                    if (types.includes("country")) country = component.long_name;
                });

                if (city) document.getElementById(`city_${prefix}`).value = city;
                if (zip) document.getElementById(`zip_${prefix}`).value = zip;
                if (state) document.getElementById(`state_${prefix}`).value = state;
                if (country) document.getElementById(`country_${prefix}`).value = country;
            });
        }
    </script>
</x-app-layout>