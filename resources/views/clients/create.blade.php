<x-app-layout>
    <style>
        .glass-container {
            background: rgba(26, 26, 26, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .custom-input {
            background: rgba(0, 0, 0, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            padding: 0.75rem 1rem !important;
            border-radius: 0.75rem !important;
            font-size: 0.875rem !important;
            transition: all 0.3s ease !important;
            width: 100% !important;
        }

        .custom-input:focus {
            border-color: #cff700 !important;
            background: rgba(0, 0, 0, 0.5) !important;
            outline: none !important;
            box-shadow: 0 0 10px rgba(207, 247, 0, 0.1) !important;
        }

        .label-style {
            display: block !important;
            font-size: 0.65rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            color: #9ca3af !important;
            margin-bottom: 0.4rem !important;
            margin-left: 0.2rem !important;
        }

        .section-header {
            border-left: 4px solid #cff700;
            padding-left: 1rem;
            margin-bottom: 1.5rem;
        }

        .pill-button {
            background: linear-gradient(135deg, #2a2a2a 0%, #000000 100%) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 9999px !important;
            padding: 0.75rem 2rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.15em !important;
            font-size: 0.75rem !important;
            transition: all 0.3s ease !important;
            cursor: pointer !important;
        }

        .pill-button:hover {
            transform: translateY(-2px);
            border-color: rgba(207, 247, 0, 0.4) !important;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        }

        .pill-button-primary {
            background: #cff700 !important;
            color: #000000 !important;
            border: none !important;
        }

        .pill-button-primary:hover {
            background: #e5ff00 !important;
            box-shadow: 0 10px 20px rgba(207, 247, 0, 0.2);
        }

        .custom-checkbox {
            border-radius: 0.25rem;
            background: rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.1);
            color: #cff700;
            transition: all 0.2s ease;
        }
        
        .custom-checkbox:focus {
            ring-color: #cff700;
        }

        .select-wrapper select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.25rem;
        }
    </style>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight italic">NUEVO <span class="text-lime-brand">CLIENTE</span></h1>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-[0.2em] mt-1">Registro Detallado de Operaciones</p>
                </div>
                <a href="{{ route('clients.index') }}" class="pill-button flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    VOLVER
                </a>
            </div>

            <form method="POST" action="{{ route('clients.store') }}" class="space-y-8">
                @csrf

                <!-- 1. SEGMENTACIÓN Y DATOS FISCALES -->
                <div class="glass-container p-8">
                    <div class="section-header">
                        <h2 class="text-xl font-bold text-white uppercase tracking-wider">Actividad y Datos Fiscales</h2>
                        <p class="text-gray-500 text-xs font-medium">Clasificación económica y legal</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="select-wrapper">
                            <label class="label-style">Actividad Económica</label>
                            <select name="activity_category" class="custom-input" required>
                                <option value="" disabled selected>Seleccione una categoría</option>
                                <option value="Productor" {{ old('activity_category') == 'Productor' ? 'selected' : '' }}>Productor</option>
                                <option value="Operador Logístico" {{ old('activity_category') == 'Operador Logístico' ? 'selected' : '' }}>Operador Logístico</option>
                                <option value="Agricultor" {{ old('activity_category') == 'Agricultor' ? 'selected' : '' }}>Agricultor</option>
                                <option value="Distribuidor" {{ old('activity_category') == 'Distribuidor' ? 'selected' : '' }}>Distribuidor</option>
                                <option value="Transportista" {{ old('activity_category') == 'Transportista' ? 'selected' : '' }}>Transportista</option>
                                <option value="Otros" {{ old('activity_category') == 'Otros' ? 'selected' : '' }}>Otros</option>
                            </select>
                            <x-input-error :messages="$errors->get('activity_category')" class="mt-1" />
                        </div>

                        <div>
                            <label class="label-style">Razón Social</label>
                            <input type="text" name="legal_name" value="{{ old('legal_name') }}" class="custom-input" placeholder="Nombre legal completo" required>
                            <x-input-error :messages="$errors->get('legal_name')" class="mt-1" />
                        </div>

                        <div>
                            <label class="label-style">NIF / No. VAT</label>
                            <input type="text" name="vat_number" value="{{ old('vat_number') }}" class="custom-input" placeholder="Ej: B12345678" required>
                            <x-input-error :messages="$errors->get('vat_number')" class="mt-1" />
                        </div>

                        <div>
                            <label class="label-style">N.º Registro Mercantil</label>
                            <input type="text" name="commercial_registry_number" value="{{ old('commercial_registry_number') }}" class="custom-input" placeholder="Opcional">
                            <x-input-error :messages="$errors->get('commercial_registry_number')" class="mt-1" />
                        </div>

                        <div>
                            <label class="label-style">País</label>
                            <input type="text" name="country" value="{{ old('country') }}" class="custom-input" placeholder="País de origen" required>
                            <x-input-error :messages="$errors->get('country')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <!-- 2. UBICACIONES -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Dirección Oficial -->
                    <div class="glass-container p-8">
                        <div class="section-header">
                            <h2 class="text-lg font-bold text-white uppercase tracking-wider">Dirección Oficial</h2>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="label-style">Calle y Número</label>
                                <input type="text" name="address" value="{{ old('address') }}" class="custom-input" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="label-style">Ciudad</label>
                                    <input type="text" name="city" value="{{ old('city') }}" class="custom-input" required>
                                </div>
                                <div>
                                    <label class="label-style">Cód. Postal</label>
                                    <input type="text" name="zip_code" value="{{ old('zip_code') }}" class="custom-input" required>
                                </div>
                            </div>
                            <div>
                                <label class="label-style">Estado / Provincia</label>
                                <input type="text" name="state" value="{{ old('state') }}" class="custom-input" required>
                            </div>
                        </div>
                    </div>

                    <!-- Dirección Correspondencia -->
                    <div class="glass-container p-8">
                        <div class="section-header">
                            <h2 class="text-lg font-bold text-white uppercase tracking-wider">Correspondencia</h2>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="label-style">Calle y Número</label>
                                <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" class="custom-input">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="label-style">Ciudad</label>
                                    <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" class="custom-input">
                                </div>
                                <div>
                                    <label class="label-style">Cód. Postal</label>
                                    <input type="text" name="shipping_zip_code" value="{{ old('shipping_zip_code') }}" class="custom-input">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="label-style">País</label>
                                    <input type="text" name="shipping_country" value="{{ old('shipping_country') }}" class="custom-input">
                                </div>
                                <div>
                                    <label class="label-style">E-Mail Correspondencia</label>
                                    <input type="email" name="shipping_email" value="{{ old('shipping_email') }}" class="custom-input">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. GESTIÓN DE CONTACTOS -->
                <div class="glass-container p-8">
                    <div class="section-header">
                        <h2 class="text-xl font-bold text-white uppercase tracking-wider">Contactos</h2>
                        <p class="text-gray-500 text-xs font-medium">Personal de enlace directo</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Finanzas -->
                        <div class="space-y-4">
                            <h3 class="text-lime-brand text-[10px] font-black uppercase tracking-widest bg-lime-brand/10 w-fit px-2 py-1 rounded">Facturación / Finanzas</h3>
                            <div>
                                <label class="label-style">Nombre Completo</label>
                                <input type="text" name="billing_contact_name" value="{{ old('billing_contact_name') }}" class="custom-input">
                            </div>
                            <div>
                                <label class="label-style">Teléfono</label>
                                <input type="text" name="billing_contact_phone" value="{{ old('billing_contact_phone') }}" class="custom-input">
                            </div>
                            <div>
                                <label class="label-style">E-mail</label>
                                <input type="email" name="billing_contact_email" value="{{ old('billing_contact_email') }}" class="custom-input">
                            </div>
                        </div>

                        <!-- Logística -->
                        <div class="space-y-4">
                            <h3 class="text-lime-brand text-[10px] font-black uppercase tracking-widest bg-lime-brand/10 w-fit px-2 py-1 rounded">Logística / Transporte</h3>
                            <div>
                                <label class="label-style">Nombre Completo</label>
                                <input type="text" name="logistics_contact_name" value="{{ old('logistics_contact_name') }}" class="custom-input">
                            </div>
                            <div>
                                <label class="label-style">Cargo</label>
                                <input type="text" name="logistics_contact_role" value="{{ old('logistics_contact_role') }}" class="custom-input">
                            </div>
                            <div>
                                <label class="label-style">Teléfono</label>
                                <input type="text" name="logistics_contact_phone" value="{{ old('logistics_contact_phone') }}" class="custom-input">
                            </div>
                        </div>

                        <!-- Emergencias -->
                        <div class="space-y-4">
                            <h3 class="text-red-500 text-[10px] font-black uppercase tracking-widest bg-red-500/10 w-fit px-2 py-1 rounded">Emergencias 24h</h3>
                            <div>
                                <label class="label-style">Nombre Completo</label>
                                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="custom-input">
                            </div>
                            <div>
                                <label class="label-style">Teléfono</label>
                                <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="custom-input">
                            </div>
                            <div>
                                <label class="label-style">E-mail</label>
                                <input type="email" name="emergency_contact_email" value="{{ old('emergency_contact_email') }}" class="custom-input">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. FINANZAS Y DOCUMENTACIÓN -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Datos Financieros -->
                    <div class="glass-container p-8">
                        <div class="section-header">
                            <h2 class="text-lg font-bold text-white uppercase tracking-wider">Finanzas</h2>
                        </div>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="label-style">Plazo Pago (Días)</label>
                                    <input type="number" name="payment_terms_days" value="{{ old('payment_terms_days', 30) }}" class="custom-input" required>
                                </div>
                                <div class="select-wrapper">
                                    <label class="label-style">Procedimiento</label>
                                    <select name="billing_procedure" class="custom-input">
                                        <option value="Factura por viaje">Factura por viaje</option>
                                        <option value="Autofacturación">Autofacturación</option>
                                        <option value="Otros">Otros</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="label-style">Condiciones Específicas</label>
                                <textarea name="payment_conditions" class="custom-input h-24">{{ old('payment_conditions') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Documentos -->
                    <div class="glass-container p-8">
                        <div class="section-header">
                            <h2 class="text-lg font-bold text-white uppercase tracking-wider">Gestión de Documentos</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="border-b border-white/5">
                                    <tr>
                                        <th class="py-2 text-[10px] font-black text-gray-500 uppercase tracking-widest">Documento</th>
                                        <th class="py-2 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">E-mail</th>
                                        <th class="py-2 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Postal</th>
                                    </tr>
                                </thead>
                                <tbody class="text-white text-sm">
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 font-medium">Factura</td>
                                        <td class="py-3 text-center"><input type="checkbox" name="doc_invoice_email" value="1" {{ old('doc_invoice_email') ? 'checked' : '' }} class="custom-checkbox"></td>
                                        <td class="py-3 text-center"><input type="checkbox" name="doc_invoice_postal" value="1" {{ old('doc_invoice_postal') ? 'checked' : '' }} class="custom-checkbox"></td>
                                    </tr>
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 font-medium">CMR</td>
                                        <td class="py-3 text-center"><input type="checkbox" name="doc_cmr_email" value="1" {{ old('doc_cmr_email') ? 'checked' : '' }} class="custom-checkbox"></td>
                                        <td class="py-3 text-center"><input type="checkbox" name="doc_cmr_postal" value="1" {{ old('doc_cmr_postal') ? 'checked' : '' }} class="custom-checkbox"></td>
                                    </tr>
                                    <tr class="border-b border-white/5">
                                        <td class="py-3 font-medium">Albarán / Packing List</td>
                                        <td class="py-3 text-center"><input type="checkbox" name="doc_delivery_note_email" value="1" {{ old('doc_delivery_note_email') ? 'checked' : '' }} class="custom-checkbox"></td>
                                        <td class="py-3 text-center"><input type="checkbox" name="doc_delivery_note_postal" value="1" {{ old('doc_delivery_note_postal') ? 'checked' : '' }} class="custom-checkbox"></td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-medium">Reporte de Temperatura</td>
                                        <td class="py-3 text-center"><input type="checkbox" name="doc_temp_report_email" value="1" {{ old('doc_temp_report_email') ? 'checked' : '' }} class="custom-checkbox"></td>
                                        <td class="py-3 text-center"><input type="checkbox" name="doc_temp_report_postal" value="1" {{ old('doc_temp_report_postal') ? 'checked' : '' }} class="custom-checkbox"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 5. FIRMA -->
                <div class="glass-container p-8">
                    <div class="section-header">
                        <h2 class="text-xl font-bold text-white uppercase tracking-wider">Validación de Registro</h2>
                        <p class="text-gray-500 text-xs font-medium">Responsable del llenado de información</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="label-style">Nombre</label>
                            <input type="text" name="filled_by_name" value="{{ old('filled_by_name', auth()->user()->name) }}" class="custom-input" required>
                        </div>
                        <div>
                            <label class="label-style">Cargo</label>
                            <input type="text" name="filled_by_role" value="{{ old('filled_by_role') }}" class="custom-input" placeholder="Ej: Gestor Comercial" required>
                        </div>
                        <div>
                            <label class="label-style">Teléfono</label>
                            <input type="text" name="filled_by_phone" value="{{ old('filled_by_phone') }}" class="custom-input" required>
                        </div>
                        <div>
                            <label class="label-style">Fecha</label>
                            <input type="date" name="filled_by_date" value="{{ old('filled_by_date', date('Y-m-d')) }}" class="custom-input" required>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-8">
                    <button type="reset" class="pill-button">LIMPIAR FORMULARIO</button>
                    <button type="submit" class="pill-button pill-button-primary">REGISTRAR CLIENTE</button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>