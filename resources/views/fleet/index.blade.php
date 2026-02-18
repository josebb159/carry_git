<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">Fleet Management</h2>
            <p class="text-gray-400">Manage your vehicles and drivers</p>
        </div>
        <a href="{{ route('fleet.create') }}"
            class="bg-lime-brand hover:bg-lime-400 text-black px-4 py-2 rounded-lg text-sm font-bold transition-colors">
            Add Vehicle
        </a>
    </div>

    <!-- Fleet Table -->
    <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-black">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Carrier
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Refrigeration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Capacity</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Double Driver</th>
                    </tr>
                </thead>
                <tbody class="bg-[#1E1E1E] divide-y divide-gray-800">
                    @forelse($fleets as $fleet)
                        <tr class="hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium text-white">{{ $fleet->carrier->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $fleet->truck_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $fleet->refrigeration_type ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $fleet->capacity_cbm ? $fleet->capacity_cbm . ' CBM' : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($fleet->double_driver)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-lime-brand/20 text-lime-brand border border-lime-brand/30">Yes</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-800 text-gray-400 border border-gray-700">No</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                No vehicles found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-800">
            {{ $fleets->links() }}
        </div>
    </div>
</x-app-layout>