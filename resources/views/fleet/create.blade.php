<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('fleet.index') }}" class="text-lime-brand hover:text-lime-300 font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Fleet
        </a>
    </div>

    <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 overflow-hidden max-w-3xl mx-auto">
        <div class="p-6 border-b border-gray-800">
            <h2 class="text-xl font-bold text-white">Add New Vehicle</h2>
            <p class="text-gray-400 text-sm">Register a new vehicle to a carrier</p>
        </div>

        <form method="POST" action="{{ route('fleet.store') }}" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Carrier Selection -->
                <div>
                    <x-input-label for="carrier_id" value="Carrier" class="text-gray-300" />
                    <select id="carrier_id" name="carrier_id"
                        class="bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand rounded-md shadow-sm block mt-1 w-full"
                        required autofocus>
                        <option value="">Select Carrier</option>
                        @foreach($carriers as $carrier)
                            <option value="{{ $carrier->id }}" {{ old('carrier_id') == $carrier->id ? 'selected' : '' }}>
                                {{ $carrier->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('carrier_id')" class="mt-2" />
                </div>

                <!-- Vehicle Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="truck_type" value="Truck Type (e.g., Trailer, Van)" class="text-gray-300" />
                        <x-text-input id="truck_type"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="text" name="truck_type" :value="old('truck_type')" required />
                        <x-input-error :messages="$errors->get('truck_type')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="refrigeration_type" value="Refrigeration Type (Optional)"
                            class="text-gray-300" />
                        <x-text-input id="refrigeration_type"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="text" name="refrigeration_type" :value="old('refrigeration_type')"
                            placeholder="e.g., Frozen, Chilled" />
                        <x-input-error :messages="$errors->get('refrigeration_type')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="capacity_cbm" value="Capacity (CBM)" class="text-gray-300" />
                        <x-text-input id="capacity_cbm"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="number" step="0.01" name="capacity_cbm" :value="old('capacity_cbm')" />
                        <x-input-error :messages="$errors->get('capacity_cbm')" class="mt-2" />
                    </div>

                    <div class="flex items-end pb-2">
                        <div class="flex items-center">
                            <input id="double_driver" type="checkbox" name="double_driver" value="1" {{ old('double_driver') ? 'checked' : '' }}
                                class="w-4 h-4 text-lime-brand border-gray-700 bg-black rounded focus:ring-lime-brand focus:ring-offset-gray-900">
                            <label for="double_driver" class="ml-2 block text-sm text-gray-300">Double Driver
                                Available</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('fleet.index') }}"
                    class="px-4 py-2 border border-gray-700 rounded-md text-gray-400 hover:bg-gray-800 font-medium">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 bg-lime-brand hover:bg-lime-400 text-black rounded-md font-bold transition-colors">Add
                    Vehicle</button>
            </div>
        </form>
    </div>
</x-app-layout>