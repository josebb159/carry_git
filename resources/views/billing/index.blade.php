<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">Billing</h2>
            <p class="text-gray-400">Financial overview and invoices</p>
        </div>
    </div>

    <!-- Invoice Filters -->
    <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 p-4 mb-6">
        <div class="flex space-x-2 overflow-x-auto">
            <a href="{{ route('billing.index') }}" class="px-3 py-1 rounded-full text-sm font-medium {{ !request('status') ? 'bg-lime-brand text-black' : 'bg-black text-gray-400 border border-gray-700 hover:bg-gray-800' }}">All</a>
            @foreach($statuses as $status)
                <a href="{{ route('billing.index', ['status' => $status->value]) }}" class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == $status->value ? 'bg-lime-brand text-black' : 'bg-black text-gray-400 border border-gray-700 hover:bg-gray-800' }}">
                    {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Billing Table -->
    <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-black">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-[#1E1E1E] divide-y divide-gray-800">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium text-white hover:text-lime-brand transition-colors">{{ $invoice->invoice_number }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                {{ $invoice->client->legal_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                #{{ $invoice->order->order_number }}
                            </td>
                             <td class="px-6 py-4 whitespace-nowrap font-medium text-white">
                                ${{ number_format($invoice->total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $invoice->due_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = match($invoice->status->value) {
                                        'draft' => 'bg-gray-800 text-gray-300 border border-gray-700',
                                        'sent' => 'bg-blue-900/30 text-blue-400 border border-blue-800/50',
                                        'paid' => 'bg-lime-brand/20 text-lime-brand border border-lime-brand/30',
                                        'overdue' => 'bg-red-900/30 text-red-400 border border-red-800/50',
                                        'cancelled' => 'bg-gray-800 text-gray-500 border border-gray-700',
                                        default => 'bg-gray-800 text-gray-300',
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                    {{ ucfirst($invoice->status->value) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-lime-brand hover:text-lime-300">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                No invoices found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-800">
            {{ $invoices->links() }}
        </div>
    </div>
</x-app-layout>
