<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('fleet.index') }}" class="text-lime-brand hover:text-lime-300 font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Fleet
        </a>
    </div>

    <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 overflow-hidden max-w-4xl mx-auto">
        <div class="p-6 border-b border-gray-800">
            <h2 class="text-xl font-bold text-white">Registrar Flota</h2>
            <p class="text-gray-400 text-sm">Configuración de capacidades y opciones operativas</p>
        </div>

        <form method="POST" action="{{ route('fleet.store') }}" class="p-6">
            @csrf

            <div class="space-y-8">
                <!-- Carrier Selection -->
                <div>
                    <x-input-label for="carrier_id" value="Transportista Asociado" class="text-gray-300" />
                    <select id="carrier_id" name="carrier_id"
                        class="bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand rounded-md shadow-sm block mt-1 w-full"
                        required autofocus>
                        <option value="">Seleccionar Transportista</option>
                        @foreach($carriers as $carrier)
                            <option value="{{ $carrier->id }}" {{ old('carrier_id') == $carrier->id ? 'selected' : '' }}>
                                {{ $carrier->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('carrier_id')" class="mt-2" />
                </div>

                <!-- 1. Datos de la Flota Propia -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-800 pb-2 flex items-center">
                        <span class="mr-2">🚛</span> 1. Datos de la Flota Propia
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <x-input-label for="total_owned_trucks" value="Total camiones propios" class="text-gray-400 text-xs" />
                            <x-text-input id="total_owned_trucks" class="block mt-1 w-full bg-black border-gray-700 text-white" type="number" min="0" name="total_owned_trucks" :value="old('total_owned_trucks', 0)" />
                        </div>
                        <div>
                            <x-input-label for="three_axle_trucks" value="Camiones de 3 ejes" class="text-gray-400 text-xs" />
                            <x-text-input id="three_axle_trucks" class="block mt-1 w-full bg-black border-gray-700 text-white" type="number" min="0" name="three_axle_trucks" :value="old('three_axle_trucks', 0)" />
                        </div>
                        <div>
                            <x-input-label for="tauliner_trucks" value="Semirremolques Lona" class="text-gray-400 text-xs" />
                            <x-text-input id="tauliner_trucks" class="block mt-1 w-full bg-black border-gray-700 text-white" type="number" min="0" name="tauliner_trucks" :value="old('tauliner_trucks', 0)" />
                        </div>
                        <div>
                            <x-input-label for="container_chassis" value="Portacontenedores" class="text-gray-400 text-xs" />
                            <x-text-input id="container_chassis" class="block mt-1 w-full bg-black border-gray-700 text-white" type="number" min="0" name="container_chassis" :value="old('container_chassis', 0)" />
                        </div>
                        <div>
                            <x-input-label for="mega_trailers" value="Mega Tráilers (120 cb)" class="text-gray-400 text-xs" />
                            <x-text-input id="mega_trailers" class="block mt-1 w-full bg-black border-gray-700 text-white" type="number" min="0" name="mega_trailers" :value="old('mega_trailers', 0)" />
                        </div>
                        <div>
                            <x-input-label for="frigo_trucks" value="Semirremolques Frigo" class="text-gray-400 text-xs" />
                            <x-text-input id="frigo_trucks" class="block mt-1 w-full bg-black border-gray-700 text-white" type="number" min="0" name="frigo_trucks" :value="old('frigo_trucks', 0)" />
                        </div>
                        <div>
                            <x-input-label for="frigo_bitemp_trucks" value="Frigo Bi-Temperatura" class="text-gray-400 text-xs" />
                            <x-text-input id="frigo_bitemp_trucks" class="block mt-1 w-full bg-black border-gray-700 text-white" type="number" min="0" name="frigo_bitemp_trucks" :value="old('frigo_bitemp_trucks', 0)" />
                        </div>
                        <div>
                            <x-input-label for="double_deck_trucks" value="Semirremolques Doble Piso" class="text-gray-400 text-xs" />
                            <x-text-input id="double_deck_trucks" class="block mt-1 w-full bg-black border-gray-700 text-white" type="number" min="0" name="double_deck_trucks" :value="old('double_deck_trucks', 0)" />
                        </div>
                    </div>
                </div>

                <!-- 2. Destinos Preferidos -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-800 pb-2 flex items-center">
                        <span class="mr-2">🌍</span> 2. Destinos Preferidos (Cargas FTL)
                    </h3>
                    @php
                        $destinations = [
                            'Europa del Sur', 'Europa Occidental (Oeste)', 'Europa Central', 
                            'Europa del Este', 'Países Balcánicos', 'Escandinavia', 
                            'Reino Unido / Irlanda', 'Rusia / Países CEI', 'Países del Cáucaso', 
                            'Asia Central', 'Oriente Próximo'
                        ];
                        $oldDestinations = old('preferred_destinations', []);
                    @endphp
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($destinations as $dest)
                            <div class="flex items-center border border-gray-800 p-2 rounded bg-black">
                                <input id="dest_{{ Str::slug($dest) }}" type="checkbox" name="preferred_destinations[]" value="{{ $dest }}" {{ in_array($dest, $oldDestinations) ? 'checked' : '' }} class="w-4 h-4 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                                <label for="dest_{{ Str::slug($dest) }}" class="ml-2 block text-xs text-gray-300">{{ $dest }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Opciones Operativas Adicionales -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-800 pb-2 flex items-center">
                        <span class="mr-2">⚙️</span> 3. Opciones Operativas Adicionales
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <!-- ADR -->
                            <div class="flex items-start">
                                <input id="adr_enabled" type="checkbox" name="adr_enabled" value="1" {{ old('adr_enabled') ? 'checked' : '' }} class="w-4 h-4 mt-1 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                                <div class="ml-2 w-full">
                                    <label for="adr_enabled" class="block text-sm text-gray-300 font-medium">ADR (Mercancías Peligrosas)</label>
                                    <x-text-input id="adr_classes" placeholder="Especificar Clases" class="block mt-1 w-full text-sm bg-black border-gray-700 text-white placeholder-gray-600" type="text" name="adr_classes" :value="old('adr_classes')" />
                                </div>
                            </div>

                            <!-- Palet Exchange -->
                            <div class="flex items-center p-3 border border-gray-800 rounded bg-black/50 hover:border-gray-700 transition-colors">
                                <input id="pallet_exchange" type="checkbox" name="pallet_exchange" value="1" {{ old('pallet_exchange') ? 'checked' : '' }} class="w-5 h-5 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                                <label for="pallet_exchange" class="ml-3 block text-sm text-gray-300">Intercambio de Palets</label>
                            </div>

                            <!-- GPS -->
                            <div class="flex items-center p-3 border border-gray-800 rounded bg-black/50 hover:border-gray-700 transition-colors">
                                <input id="gps_tracking" type="checkbox" name="gps_tracking" value="1" {{ old('gps_tracking') ? 'checked' : '' }} class="w-5 h-5 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                                <label for="gps_tracking" class="ml-3 block text-sm text-gray-300">GPS Tracking System <span class="text-lime-brand/60 text-[10px] ml-1 uppercase">Live</span></label>
                            </div>

                            <!-- Subcontractors -->
                            <div class="flex items-center p-3 border border-gray-800 rounded bg-black/50 hover:border-gray-700 transition-colors">
                                <input id="subcontractors_trucks" type="checkbox" name="subcontractors_trucks" value="1" {{ old('subcontractors_trucks') ? 'checked' : '' }} class="w-5 h-5 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                                <label for="subcontractors_trucks" class="ml-3 block text-sm text-gray-300">Subcontractors trucks (Subcontratados)</label>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- Double Driver -->
                            <div class="flex items-center p-3 border border-gray-800 rounded bg-black/50 hover:border-gray-700 transition-colors">
                                <input id="double_driver" type="checkbox" name="double_driver" value="1" {{ old('double_driver') ? 'checked' : '' }} class="w-5 h-5 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                                <label for="double_driver" class="ml-3 block text-sm text-gray-300">Doble Conductor (Dobles)</label>
                            </div>

                            <!-- Multimodal -->
                            <div class="flex items-center p-3 border border-gray-800 rounded bg-black/50 hover:border-gray-700 transition-colors">
                                <input id="multimodal_solutions" type="checkbox" name="multimodal_solutions" value="1" {{ old('multimodal_solutions') ? 'checked' : '' }} class="w-5 h-5 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                                <label for="multimodal_solutions" class="ml-3 block text-sm text-gray-300">Soluciones Multimodales</label>
                            </div>

                            <!-- Partial Loads -->
                            <div class="flex items-center p-3 border border-gray-800 rounded bg-black/50 hover:border-gray-700 transition-colors">
                                <input id="partial_loads" type="checkbox" name="partial_loads" value="1" {{ old('partial_loads') ? 'checked' : '' }} class="w-5 h-5 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                                <label for="partial_loads" class="ml-3 block text-sm text-gray-300">Partial loads (Grupajes)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-800 flex justify-end gap-3">
                <a href="{{ route('fleet.index') }}"
                    class="px-5 py-2.5 border border-gray-700 rounded-md text-gray-400 hover:bg-gray-800 transition-colors font-medium">Cancelar</a>
                <button type="submit"
                    class="px-6 py-2.5 bg-lime-brand hover:bg-lime-400 text-black rounded-md font-bold transition-all shadow-lg shadow-lime-brand/20">Finalizar Registro</button>
            </div>
        </form>
    </div>
</x-app-layout>