<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="text-lime-brand hover:text-lime-300 font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Orders
        </a>
    </div>

    <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 overflow-hidden">
        <div class="p-6 border-b border-gray-800">
            <h2 class="text-xl font-bold text-white">Create New Order</h2>
            <p class="text-gray-400 text-sm">Enter the order details below</p>
        </div>

        <form method="POST" action="{{ route('orders.store') }}" class="p-6">
            @csrf

            <!-- General Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <x-input-label for="client_id" value="Client" class="text-gray-300" />
                    <select name="client_id" id="client_id"
                        class="block mt-1 w-full bg-black border-gray-700 text-white rounded-md shadow-sm focus:border-lime-brand focus:ring-lime-brand">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->legal_name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="carrier_id" value="Carrier" class="text-gray-300" />
                    <select name="carrier_id" id="carrier_id"
                        class="block mt-1 w-full bg-black border-gray-700 text-white rounded-md shadow-sm focus:border-lime-brand focus:ring-lime-brand">
                        <option value="">Select Carrier (Optional)</option>
                        @foreach($carriers as $carrier)
                            <option value="{{ $carrier->id }}" {{ old('carrier_id') == $carrier->id ? 'selected' : '' }}>
                                {{ $carrier->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('carrier_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="order_number" value="Order Number" class="text-gray-300" />
                    <x-text-input id="order_number"
                        class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                        type="text" name="order_number" :value="old('order_number', 'ORD-' . strtoupper(uniqid()))"
                        required />
                    <x-input-error :messages="$errors->get('order_number')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="status" value="Status" class="text-gray-300" />
                    <select name="status" id="status"
                        class="block mt-1 w-full bg-black border-gray-700 text-white rounded-md shadow-sm focus:border-lime-brand focus:ring-lime-brand">
                        @foreach(\App\Shared\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                                {{ ucfirst($status->value) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="payment_status" value="Payment Status" class="text-gray-300" />
                    <select name="payment_status" id="payment_status"
                        class="block mt-1 w-full bg-black border-gray-700 text-white rounded-md shadow-sm focus:border-lime-brand focus:ring-lime-brand">
                        @foreach(\App\Shared\Enums\PaymentStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ old('payment_status') == $status->value ? 'selected' : '' }}>{{ ucfirst($status->value) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('payment_status')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="total_amount" value="Total Amount" class="text-gray-300" />
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <x-text-input id="total_amount"
                            class="block w-full pl-7 bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="number" step="0.01" name="total_amount" :value="old('total_amount')" required
                            placeholder="0.00" />
                    </div>
                    <x-input-error :messages="$errors->get('total_amount')" class="mt-2" />
                </div>
            </div>

            <!-- Locations Mockup -->
            <div class="border-t border-gray-800 pt-6 mt-6">
                <h3 class="text-lg font-medium text-white mb-4">Locations</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pickup -->
                    <div class="bg-black p-4 rounded-lg border border-gray-800">
                        <h4 class="font-bold text-gray-300 mb-3">Pickup Location</h4>
                        <input type="hidden" name="locations[0][type]" value="pickup">
                        <input type="hidden" name="locations[0][sequence]" value="1">

                        <div class="space-y-3">
                            <div>
                                <x-input-label value="Address" class="text-gray-400" />
                                <x-text-input name="locations[0][address]"
                                    class="w-full text-sm bg-[#1E1E1E] border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                                    :value="old('locations.0.address')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <x-input-label value="City" class="text-gray-400" />
                                    <x-text-input name="locations[0][city]"
                                        class="w-full text-sm bg-[#1E1E1E] border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                                        :value="old('locations.0.city')" required />
                                </div>
                                <div>
                                    <x-input-label value="State" class="text-gray-400" />
                                    <x-text-input name="locations[0][state]"
                                        class="w-full text-sm bg-[#1E1E1E] border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                                        :value="old('locations.0.state')" required />
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <x-input-label value="Zip Code" class="text-gray-400" />
                                    <x-text-input name="locations[0][zip_code]"
                                        class="w-full text-sm bg-[#1E1E1E] border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                                        :value="old('locations.0.zip_code')" required />
                                </div>
                                <div>
                                    <x-input-label value="Country" class="text-gray-400" />
                                    <x-text-input name="locations[0][country]"
                                        class="w-full text-sm bg-[#1E1E1E] border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                                        :value="old('locations.0.country', 'US')" />
                                </div>
                            </div>
                            <div>
                                <x-input-label value="Scheduled At" class="text-gray-400" />
                                <x-text-input type="datetime-local" name="locations[0][scheduled_at]"
                                    class="w-full text-sm bg-[#1E1E1E] border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                                    :value="old('locations.0.scheduled_at')" />
                            </div>
                        </div>
                    </div>

                    <!-- Delivery -->
                    <div class="bg-black p-4 rounded-lg border border-gray-800">
                        <h4 class="font-bold text-gray-300 mb-3">Delivery Location</h4>
                        <input type="hidden" name="locations[1][type]" value="delivery">
                        <input type="hidden" name="locations[1][sequence]" value="2">

                        <div class="space-y-3">
                            <div>
                                <x-input-label value="Address" class="text-gray-400" />
                                <x-text-input name="locations[1][address]"
                                    class="w-full text-sm bg-[#1E1E1E] border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                                    :value="old('locations.1.address')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <x-input-label value="City" class="text-gray-400" />
                                    <x-text-input name="locations[1][city]"
                                        class="w-full text-sm bg-[#1E1E1E] border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                                        :value="old('locations.1.city')" required />
                                </div>
                                <div>
                                    <x-input-label value="State" class="text-gray-400" />
                                    <x-text-input name="locations[1][state]"
                                        class="w-full text-sm bg-[#1E1E1E] border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                                        :value="old('locations.1.state')" required />
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <x-input-label value="Zip Code" class="text-gray-400" />
                                    <x-text-input name="locations[1][zip_code]"
                                        class="w-full text-sm bg-[#1E1E1E] border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                                        :value="old('locations.1.zip_code')" required />
                                </div>
                                <div>
                                    <x-input-label value="Country" class="text-gray-400" />
                                    <x-text-input name="locations[1][country]"
                                        class="w-full text-sm bg-[#1E1E1E] border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                                        :value="old('locations.1.country', 'US')" />
                                </div>
                            </div>
                            <div>
                                <x-input-label value="Scheduled At" class="text-gray-400" />
                                <x-text-input type="datetime-local" name="locations[1][scheduled_at]"
                                    class="w-full text-sm bg-[#1E1E1E] border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                                    :value="old('locations.1.scheduled_at')" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <x-input-label for="notes" value="Notes" class="text-gray-300" />
                <textarea name="notes" id="notes" rows="3"
                    class="block mt-1 w-full bg-black border-gray-700 text-white rounded-md shadow-sm focus:border-lime-brand focus:ring-lime-brand">{{ old('notes') }}</textarea>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('orders.index') }}"
                    class="px-4 py-2 border border-gray-700 rounded-md text-gray-400 hover:bg-gray-800 font-medium">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 bg-lime-brand hover:bg-lime-400 text-black rounded-md font-bold transition-colors">Create
                    Order</button>
            </div>
        </form>
    </div>
</x-app-layout>