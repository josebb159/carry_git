<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('clients.index') }}"
            class="text-lime-brand hover:text-lime-300 font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Clients
        </a>
    </div>

    <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 overflow-hidden max-w-2xl mx-auto">
        <div class="p-6 border-b border-gray-800">
            <h2 class="text-xl font-bold text-white">Add New Client</h2>
            <p class="text-gray-400 text-sm">Enter the client's information below</p>
        </div>

        <form method="POST" action="{{ route('clients.store') }}" class="p-6">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <!-- Company Info -->
                <div>
                    <x-input-label for="legal_name" value="Company Name" class="text-gray-300" />
                    <x-text-input id="legal_name"
                        class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                        type="text" name="legal_name" :value="old('legal_name')" required autofocus />
                    <x-input-error :messages="$errors->get('legal_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="vat_number" value="VAT Number" class="text-gray-300" />
                    <x-text-input id="vat_number"
                        class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                        type="text" name="vat_number" :value="old('vat_number')" required />
                    <x-input-error :messages="$errors->get('vat_number')" class="mt-2" />
                </div>

                <!-- Address Info -->
                <div>
                    <x-input-label for="address" value="Address" class="text-gray-300" />
                    <x-text-input id="address"
                        class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                        type="text" name="address" :value="old('address')" required />
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="city" value="City" class="text-gray-300" />
                        <x-text-input id="city"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="text" name="city" :value="old('city')" required />
                        <x-input-error :messages="$errors->get('city')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="state" value="State" class="text-gray-300" />
                        <x-text-input id="state"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="text" name="state" :value="old('state')" required />
                        <x-input-error :messages="$errors->get('state')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="zip_code" value="Zip Code" class="text-gray-300" />
                        <x-text-input id="zip_code"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="text" name="zip_code" :value="old('zip_code')" required />
                        <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="country" value="Country" class="text-gray-300" />
                        <x-text-input id="country"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="text" name="country" :value="old('country')" required />
                        <x-input-error :messages="$errors->get('country')" class="mt-2" />
                    </div>
                </div>

                <!-- Financial Info -->
                <div>
                    <x-input-label for="payment_terms" value="Payment Terms (Days)" class="text-gray-300" />
                    <x-text-input id="payment_terms"
                        class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                        type="number" name="payment_terms" :value="old('payment_terms', 30)" required min="0" />
                    <p class="text-xs text-gray-500 mt-1">Days to pay invoice after issuance.</p>
                    <x-input-error :messages="$errors->get('payment_terms')" class="mt-2" />
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('clients.index') }}"
                    class="px-4 py-2 border border-gray-700 rounded-md text-gray-400 hover:bg-gray-800 font-medium">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 bg-lime-brand hover:bg-lime-400 text-black rounded-md font-bold transition-colors">Create
                    Client</button>
            </div>
        </form>
    </div>
</x-app-layout>