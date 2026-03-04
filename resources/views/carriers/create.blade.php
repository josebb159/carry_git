<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('carriers.index') }}"
            class="text-lime-brand hover:text-lime-300 font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Carriers
        </a>
    </div>

    <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 overflow-hidden max-w-4xl mx-auto">
        <div class="p-6 border-b border-gray-800">
            <h2 class="text-xl font-bold text-white">Add New Carrier</h2>
            <p class="text-gray-400 text-sm">Registro de Transportista</p>
        </div>

        <form method="POST" action="{{ route('carriers.store') }}" class="p-6" enctype="multipart/form-data">
            @csrf

            <div class="space-y-8">
                <!-- 1. Datos de Identificación y Legales -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-800 pb-2">1. Datos de Identificación y Legales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <x-input-label for="name" value="Razón Social / Nombre Legal" class="text-gray-300" />
                            <x-text-input id="name" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="vat_number" value="CIF/NIF (Tax ID)" class="text-gray-300" />
                            <x-text-input id="vat_number" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="text" name="vat_number" :value="old('vat_number')" required />
                            <x-input-error :messages="$errors->get('vat_number')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="company_registration_number" value="N.º de Registro Mercantil" class="text-gray-300" />
                            <x-text-input id="company_registration_number" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="text" name="company_registration_number" :value="old('company_registration_number')" />
                            <x-input-error :messages="$errors->get('company_registration_number')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="website" value="Sitio Web" class="text-gray-300" />
                            <x-text-input id="website" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="url" name="website" :value="old('website')" />
                            <x-input-error :messages="$errors->get('website')" class="mt-2" />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="full_address" value="Dirección Completa (Calle, Ciudad, Código Postal, País)" class="text-gray-300" />
                            <textarea id="full_address" name="full_address" rows="2" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand rounded-md shadow-sm">{{ old('full_address') }}</textarea>
                            <x-input-error :messages="$errors->get('full_address')" class="mt-2" />
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <x-input-label for="email" value="Email Principal" class="text-gray-300" />
                            <x-text-input id="email" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- 2. Documentación Requerida -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-800 pb-2">2. Documentación Requerida</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="doc_company_registration" value="Escritura / Certificado de Registro de la Empresa" class="text-gray-300" />
                            <input type="file" id="doc_company_registration" name="doc_company_registration" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-lime-brand hover:file:bg-gray-700 mt-1" />
                            <x-input-error :messages="$errors->get('doc_company_registration')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="doc_cmr_insurance" value="Póliza de Seguro de Mercancías (CMR)" class="text-gray-300" />
                            <input type="file" id="doc_cmr_insurance" name="doc_cmr_insurance" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-lime-brand hover:file:bg-gray-700 mt-1" />
                            <x-input-error :messages="$errors->get('doc_cmr_insurance')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="doc_transport_license" value="Licencia de Transporte" class="text-gray-300" />
                            <input type="file" id="doc_transport_license" name="doc_transport_license" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-lime-brand hover:file:bg-gray-700 mt-1" />
                            <x-input-error :messages="$errors->get('doc_transport_license')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="doc_bank_certificate" value="Certificado de Cuenta Bancaria (Header)" class="text-gray-300" />
                            <input type="file" id="doc_bank_certificate" name="doc_bank_certificate" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-lime-brand hover:file:bg-gray-700 mt-1" />
                            <x-input-error :messages="$errors->get('doc_bank_certificate')" class="mt-2" />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="doc_tax_residence" value="Certificado de Residencia Fiscal" class="text-gray-300" />
                            <input type="file" id="doc_tax_residence" name="doc_tax_residence" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-lime-brand hover:file:bg-gray-700 mt-1" />
                            <x-input-error :messages="$errors->get('doc_tax_residence')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- 3. Información Operativa y de Flota -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-800 pb-2">3. Información Operativa y de Flota</h3>
                    
                    <h4 class="text-sm font-medium text-gray-300 mb-3">Tipos de Camiones disponibles (Cantidad)</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <x-input-label for="fleet_tauliner_count" value="Semirremolques Lona" class="text-gray-400 text-xs" />
                            <x-text-input id="fleet_tauliner_count" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="number" min="0" name="fleet_tauliner_count" :value="old('fleet_tauliner_count', 0)" />
                        </div>
                        <div>
                            <x-input-label for="fleet_mega_count" value="Mega Tráilers" class="text-gray-400 text-xs" />
                            <x-text-input id="fleet_mega_count" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="number" min="0" name="fleet_mega_count" :value="old('fleet_mega_count', 0)" />
                        </div>
                        <div>
                            <x-input-label for="fleet_frigo_count" value="Frigoríficos" class="text-gray-400 text-xs" />
                            <x-text-input id="fleet_frigo_count" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="number" min="0" name="fleet_frigo_count" :value="old('fleet_frigo_count', 0)" />
                        </div>
                        <div>
                            <x-input-label for="fleet_double_deck_count" value="Doble Piso" class="text-gray-400 text-xs" />
                            <x-text-input id="fleet_double_deck_count" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="number" min="0" name="fleet_double_deck_count" :value="old('fleet_double_deck_count', 0)" />
                        </div>
                    </div>

                    <h4 class="text-sm font-medium text-gray-300 mb-3">Opciones Adicionales</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start">
                            <input id="adr_enabled" type="checkbox" name="adr_enabled" value="1" {{ old('adr_enabled') ? 'checked' : '' }} class="w-4 h-4 mt-1 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                            <div class="ml-2 w-full">
                                <label for="adr_enabled" class="block text-sm text-gray-300">ADR (Mercancías Peligrosas)</label>
                                <x-text-input id="adr_classes" placeholder="Clases específicas (opcional)" class="block mt-1 w-full text-sm bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="text" name="adr_classes" :value="old('adr_classes')" />
                            </div>
                        </div>

                        <div class="flex items-center space-x-6">
                            <div class="flex items-center">
                                <input id="pallet_exchange" type="checkbox" name="pallet_exchange" value="1" {{ old('pallet_exchange') ? 'checked' : '' }} class="w-4 h-4 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                                <label for="pallet_exchange" class="ml-2 block text-sm text-gray-300">Intercambio de Palets</label>
                            </div>
                            <div class="flex items-center">
                                <input id="gps_tracking" type="checkbox" name="gps_tracking" value="1" {{ old('gps_tracking') ? 'checked' : '' }} class="w-4 h-4 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                                <label for="gps_tracking" class="ml-2 block text-sm text-gray-300">GPS Tracking System</label>
                            </div>
                        </div>
                        <div class="flex items-center space-x-6">
                            <div class="flex items-center">
                                <input id="xl_certification" type="checkbox" name="xl_certification" value="1" {{ old('xl_certification') ? 'checked' : '' }} class="w-4 h-4 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                                <label for="xl_certification" class="ml-2 block text-sm text-gray-300">Certificación XL (Código XL)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Departamentos de Contacto -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-800 pb-2">4. Departamentos de Contacto</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tráfico/Logística -->
                        <div class="bg-gray-900/50 p-4 rounded border border-gray-800">
                            <h4 class="text-sm font-medium text-lime-brand mb-3">Departamento de Tráfico / Logística</h4>
                            <div class="space-y-3">
                                <div>
                                    <x-input-label for="contact_traffic_email" value="Email" class="text-gray-400 text-xs" />
                                    <x-text-input id="contact_traffic_email" class="block w-full bg-black border-gray-700 text-white text-sm" type="email" name="contact_traffic_email" :value="old('contact_traffic_email')" />
                                </div>
                                <div>
                                    <x-input-label for="contact_traffic_phone" value="Teléfono" class="text-gray-400 text-xs" />
                                    <x-text-input id="contact_traffic_phone" class="block w-full bg-black border-gray-700 text-white text-sm" type="text" name="contact_traffic_phone" :value="old('contact_traffic_phone')" />
                                </div>
                            </div>
                        </div>

                        <!-- Administración/Contabilidad -->
                        <div class="bg-gray-900/50 p-4 rounded border border-gray-800">
                            <h4 class="text-sm font-medium text-lime-brand mb-3">Departamento de Administración / Contabilidad</h4>
                            <div class="space-y-3">
                                <div>
                                    <x-input-label for="contact_admin_email" value="Email" class="text-gray-400 text-xs" />
                                    <x-text-input id="contact_admin_email" class="block w-full bg-black border-gray-700 text-white text-sm" type="email" name="contact_admin_email" :value="old('contact_admin_email')" />
                                </div>
                                <div>
                                    <x-input-label for="contact_admin_phone" value="Teléfono" class="text-gray-400 text-xs" />
                                    <x-text-input id="contact_admin_phone" class="block w-full bg-black border-gray-700 text-white text-sm" type="text" name="contact_admin_phone" :value="old('contact_admin_phone')" />
                                </div>
                            </div>
                        </div>

                        <!-- Comercial -->
                        <div class="bg-gray-900/50 p-4 rounded border border-gray-800">
                            <h4 class="text-sm font-medium text-lime-brand mb-3">Departamento Comercial</h4>
                            <div class="space-y-3">
                                <div>
                                    <x-input-label for="contact_sales_email" value="Email" class="text-gray-400 text-xs" />
                                    <x-text-input id="contact_sales_email" class="block w-full bg-black border-gray-700 text-white text-sm" type="email" name="contact_sales_email" :value="old('contact_sales_email')" />
                                </div>
                                <div>
                                    <x-input-label for="contact_sales_phone" value="Teléfono" class="text-gray-400 text-xs" />
                                    <x-text-input id="contact_sales_phone" class="block w-full bg-black border-gray-700 text-white text-sm" type="text" name="contact_sales_phone" :value="old('contact_sales_phone')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Destinos Preferidos (Cargas FTL) -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-800 pb-2">5. Destinos Preferidos (Cargas FTL)</h3>
                    @php
                        $destinations = [
                            'Europa del Sur', 'Europa Occidental', 'Europa Central', 
                            'Europa del Este', 'Escandinavia', 'Países Balcánicos', 
                            'Rusia/CEI', 'Reino Unido/Irlanda', 'Cáucaso', 
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

                <!-- 6. Datos Bancarios -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-800 pb-2">6. Datos Bancarios</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="bank_name" value="Nombre del Banco" class="text-gray-300" />
                            <x-text-input id="bank_name" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="text" name="bank_name" :value="old('bank_name')" />
                        </div>
                        <div>
                            <x-input-label for="bank_iban" value="Número de Cuenta (IBAN)" class="text-gray-300" />
                            <x-text-input id="bank_iban" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="text" name="bank_iban" :value="old('bank_iban')" />
                        </div>
                        <div>
                            <x-input-label for="bank_swift" value="Código SWIFT/BIC" class="text-gray-300" />
                            <x-text-input id="bank_swift" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="text" name="bank_swift" :value="old('bank_swift')" />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="bank_address" value="Dirección del Banco" class="text-gray-300" />
                            <textarea id="bank_address" name="bank_address" rows="2" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand rounded-md shadow-sm">{{ old('bank_address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 7. Condiciones de Pago -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-gray-800 pb-2">7. Condiciones de Pago</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <div>
                            <x-input-label for="payment_terms_days" value="Plazo de pago (días tras recepción de documentos)" class="text-gray-300" />
                            <p class="text-xs text-gray-500 mb-2">Por defecto es 30 días.</p>
                            <x-text-input id="payment_terms_days" class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand" type="number" name="payment_terms_days" :value="old('payment_terms_days', 30)" required min="0" />
                            <x-input-error :messages="$errors->get('payment_terms_days')" class="mt-2" />
                        </div>
                        <div class="pt-4 md:pt-6">
                            <div class="flex items-start bg-gray-900/50 p-4 rounded border border-gray-800">
                                <input id="accept_e_invoicing" type="checkbox" name="accept_e_invoicing" value="1" {{ old('accept_e_invoicing', true) ? 'checked' : '' }} class="w-5 h-5 mt-0.5 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand">
                                <label for="accept_e_invoicing" class="ml-3 block text-sm text-gray-300">
                                    <span class="font-bold text-white block">Aceptación de facturación electrónica (e-invoicing)</span>
                                    Al marcar esta casilla confirmas que aceptas recibir los pagos mediante nuestro sistema de facturación electrónica.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-10 pt-6 border-t border-gray-800 flex justify-end gap-3">
                <a href="{{ route('carriers.index') }}" class="px-5 py-2.5 border border-gray-700 rounded-md text-gray-400 hover:bg-gray-800 hover:text-white font-medium transition-colors">Cancelar</a>
                <button type="submit" class="px-6 py-2.5 bg-lime-brand hover:bg-lime-400 text-black rounded-md font-bold transition-all shadow-lg shadow-lime-brand/20">Registrar Transportista</button>
            </div>
        </form>
    </div>
</x-app-layout>