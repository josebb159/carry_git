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

    <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 overflow-hidden max-w-3xl mx-auto">
        <div class="p-6 border-b border-gray-800">
            <h2 class="text-xl font-bold text-white">Add New Carrier</h2>
            <p class="text-gray-400 text-sm">Register a new transport provider</p>
        </div>

        <form method="POST" action="{{ route('carriers.store') }}" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <x-input-label for="name" value="Company Name" class="text-gray-300" />
                        <x-text-input id="name"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="vat_number" value="VAT Number / Tax ID" class="text-gray-300" />
                        <x-text-input id="vat_number"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="text" name="vat_number" :value="old('vat_number')" required />
                        <x-input-error :messages="$errors->get('vat_number')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email Address" class="text-gray-300" />
                        <x-text-input id="email"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                <!-- Licensing & Insurance -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="transport_license" value="Transport License Number" class="text-gray-300" />
                        <x-text-input id="transport_license"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="text" name="transport_license" :value="old('transport_license')" />
                        <x-input-error :messages="$errors->get('transport_license')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="insurance_policy" value="Insurance Policy Number" class="text-gray-300" />
                        <x-text-input id="insurance_policy"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="text" name="insurance_policy" :value="old('insurance_policy')" />
                        <x-input-error :messages="$errors->get('insurance_policy')" class="mt-2" />
                    </div>
                </div>

                <!-- Payment Terms -->
                <div>
                    <x-input-label for="payment_terms_days" value="Payment Terms (Days)" class="text-gray-300" />
                    <x-text-input id="payment_terms_days"
                        class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                        type="number" name="payment_terms_days" :value="old('payment_terms_days', 30)" required
                        min="0" />
                    <x-input-error :messages="$errors->get('payment_terms_days')" class="mt-2" />
                </div>

                <!-- Capabilities -->
                <div class="space-y-3">
                    <h3 class="text-sm font-medium text-gray-300">Capabilities</h3>

                    <div class="flex items-center">
                        <input id="gps_tracking" type="checkbox" name="gps_tracking" value="1" {{ old('gps_tracking') ? 'checked' : '' }}
                            class="w-4 h-4 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand focus:ring-offset-gray-900">
                        <label for="gps_tracking" class="ml-2 block text-sm text-gray-300">GPS Tracking
                            Available</label>
                    </div>

                    <div class="flex items-center">
                        <input id="adr_enabled" type="checkbox" name="adr_enabled" value="1" {{ old('adr_enabled') ? 'checked' : '' }}
                            class="w-4 h-4 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand focus:ring-offset-gray-900">
                        <label for="adr_enabled" class="ml-2 block text-sm text-gray-300">ADR (Hazardous Goods)
                            Certified</label>
                    </div>

                    <div class="flex items-center">
                        <input id="pallet_exchange" type="checkbox" name="pallet_exchange" value="1" {{ old('pallet_exchange') ? 'checked' : '' }}
                            class="w-4 h-4 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand focus:ring-offset-gray-900">
                        <label for="pallet_exchange" class="ml-2 block text-sm text-gray-300">Pallet Exchange
                            Program</label>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('carriers.index') }}"
                    class="px-4 py-2 border border-gray-700 rounded-md text-gray-400 hover:bg-gray-800 font-medium">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 bg-lime-brand hover:bg-lime-400 text-black rounded-md font-bold transition-colors">Create
                    Carrier</button>
            </div>
        </form>
    </div>
</x-app-layout>