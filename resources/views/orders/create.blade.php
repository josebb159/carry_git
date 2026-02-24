<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <a href="{{ route('dashboard') }}" class="text-lime-brand hover:text-lime-300 font-medium flex items-center mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Panel
            </a>
            <h2 class="text-3xl font-bold text-white">Nueva Solicitud de Transporte</h2>
            <p class="text-gray-400 mt-1">Completa los detalles de tu envío</p>
        </div>
    </div>

    <div class="max-w-5xl">
        <form method="POST" action="{{ route('orders.store') }}" class="space-y-8" x-data="{ cargoType: 'General' }">
            @csrf

            {{-- Generación automática de número de orden --}}
            <input type="hidden" name="order_number" value="REQ-{{ strtoupper(Str::random(8)) }}">

            {{-- Sección: Datos del Cliente (Auto-rellenados si es cliente) --}}
            <div class="bg-[#1E1E1E]/50 backdrop-blur-xl rounded-3xl border border-gray-800 p-8">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <span class="w-8 h-8 bg-lime-brand/10 text-lime-brand rounded-full flex items-center justify-center mr-3 text-sm">01</span>
                    Datos de Facturación y Contacto
                </h3>
                
                @if($isClient && $myClient)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <input type="hidden" name="client_id" value="{{ $myClient->id }}">
                        
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-400">Razón Social</label>
                            <div class="text-white font-semibold py-2 px-1 border-b border-gray-800">{{ $myClient->legal_name }}</div>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-400">NIF / CIF</label>
                            <div class="text-white font-semibold py-2 px-1 border-b border-gray-800">{{ $myClient->vat_number }}</div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-400">Contacto Logístico</label>
                            <div class="text-white font-semibold py-2 px-1 border-b border-gray-800">{{ $myClient->logistics_contact_name ?? 'No definido' }}</div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-400">Email de Gestión</label>
                            <div class="text-white font-semibold py-2 px-1 border-b border-gray-800">{{ $myClient->logistics_contact_email ?? auth()->user()->email }}</div>
                        </div>
                    </div>
                @else
                    {{-- Vista para Admin o usuarios sin perfil de cliente creado --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="client_id" value="Seleccionar Cliente" class="text-gray-400 mb-2" />
                            <select name="client_id" id="client_id" class="w-full bg-black border-gray-800 text-white rounded-xl focus:border-lime-brand focus:ring-lime-brand">
                                <option value="">Seleccione un cliente...</option>
                                @foreach($clients as $c)
                                    <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->legal_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sección: Detalles de la Carga --}}
            <div class="bg-[#1E1E1E]/50 backdrop-blur-xl rounded-3xl border border-gray-800 p-8">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <span class="w-8 h-8 bg-lime-brand/10 text-lime-brand rounded-full flex items-center justify-center mr-3 text-sm">02</span>
                    Logística y Mercancía
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    {{-- Columna: Origen --}}
                    <div class="space-y-6">
                        <h4 class="text-lime-brand font-bold uppercase tracking-wider text-xs">Punto de Recogida (Origen)</h4>
                        <input type="hidden" name="locations[0][type]" value="pickup">
                        <input type="hidden" name="locations[0][sequence]" value="1">
                        
                        <div class="space-y-4">
                            <div>
                                <x-text-input name="locations[0][address]" placeholder="Dirección completa" class="w-full bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.0.address')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <x-text-input name="locations[0][city]" placeholder="Ciudad" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.0.city')" required />
                                <x-text-input name="locations[0][zip_code]" placeholder="Código Postal" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.0.zip_code')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <x-text-input name="locations[0][state]" placeholder="Provincia / Estado" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.0.state')" required />
                                <x-text-input name="locations[0][country]" placeholder="País" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.0.country', 'España')" required />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Fecha/Hora estimada de recogida</label>
                                <x-text-input type="datetime-local" name="locations[0][scheduled_at]" class="w-full bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.0.scheduled_at')" />
                            </div>
                        </div>
                    </div>

                    {{-- Columna: Destino --}}
                    <div class="space-y-6">
                        <h4 class="text-blue-400 font-bold uppercase tracking-wider text-xs">Punto de Entrega (Destino)</h4>
                        <input type="hidden" name="locations[1][type]" value="delivery">
                        <input type="hidden" name="locations[1][sequence]" value="2">
                        
                        <div class="space-y-4">
                            <div>
                                <x-text-input name="locations[1][address]" placeholder="Dirección completa" class="w-full bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.1.address')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <x-text-input name="locations[1][city]" placeholder="Ciudad" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.1.city')" required />
                                <x-text-input name="locations[1][zip_code]" placeholder="Código Postal" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.1.zip_code')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <x-text-input name="locations[1][state]" placeholder="Provincia / Estado" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.1.state')" required />
                                <x-text-input name="locations[1][country]" placeholder="País" class="bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.1.country', 'España')" required />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Fecha/Hora preferente de entrega</label>
                                <x-text-input type="datetime-local" name="locations[1][scheduled_at]" class="w-full bg-black/50 border-gray-800 text-white rounded-xl" :value="old('locations.1.scheduled_at')" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detalles Extra --}}
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

                    {{-- Campo extra para temperatura controlada --}}
                    <div x-show="cargoType === 'Refrigerada'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="col-span-1">
                        <x-input-label for="temperature" value="Temperatura Requerida (ºC)" class="text-lime-brand mb-2" />
                        <x-text-input name="temperature" id="temperature" placeholder="Ej: +4ºC / -18ºC" class="w-full bg-black border-lime-brand/30 text-white rounded-xl focus:border-lime-brand focus:ring-lime-brand" :value="old('temperature')" />
                    </div>

                    <div :class="cargoType === 'Refrigerada' ? 'md:col-span-2' : ''">
                        <x-input-label for="notes" value="Observaciones / Instrucciones Especiales" class="text-gray-400 mb-2" />
                        <textarea name="notes" rows="1" class="w-full bg-black border-gray-800 text-white rounded-xl focus:border-lime-brand focus:ring-lime-brand" placeholder="Ej: Contactar 1h antes de llegar...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Sección: Gestión Documental --}}
            <div class="bg-[#1E1E1E]/50 backdrop-blur-xl rounded-3xl border border-gray-800 p-8">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <span class="w-8 h-8 bg-lime-brand/10 text-lime-brand rounded-full flex items-center justify-center mr-3 text-sm">03</span>
                    Gestión Documental Requerida
                </h3>
                
                <div class="flex flex-wrap gap-8">
                    <label class="inline-flex items-center group cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="request_cmr" value="1" class="sr-only peer" checked>
                            <div class="w-12 h-6 bg-gray-700 rounded-full transition peer-checked:bg-lime-brand"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition scroll-smooth peer-checked:translate-x-6"></div>
                        </div>
                        <span class="ml-3 text-gray-400 group-hover:text-white transition">Solicitar CMR Digital</span>
                    </label>

                    <label class="inline-flex items-center group cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="request_delivery_note" value="1" class="sr-only peer" checked>
                            <div class="w-12 h-6 bg-gray-700 rounded-full transition peer-checked:bg-blue-500"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition scroll-smooth peer-checked:translate-x-6"></div>
                        </div>
                        <span class="ml-3 text-gray-400 group-hover:text-white transition">Solicitar Albarán Digital</span>
                    </label>
                </div>
            </div>

            {{-- Botones de Acción --}}
            <div class="flex items-center justify-end gap-6 pt-4">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-white font-medium transition">Cancelar</a>
                
                <button type="submit" class="px-10 py-4 bg-lime-brand hover:bg-lime-400 text-black font-extrabold rounded-full transition-all duration-300 shadow-[0_0_30px_rgba(207,247,0,0.2)] hover:scale-105 active:scale-95">
                    Confirmar Envío
                </button>
            </div>
        </form>
    </div>
</x-app-layout>