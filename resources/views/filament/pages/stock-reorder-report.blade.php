<x-filament-panels::page>
    @php $data = $this->getLowStockDrugs(); @endphp

    <div class="space-y-6">
        {{-- Header banner --}}
        <div class="bg-gradient-to-r from-amber-600 to-red-600 rounded-xl p-5 text-white shadow-md">
            <h3 class="text-xl font-bold mb-1">Stock Reorder Report</h3>
            <p class="text-sm text-amber-50">
                Drugs at or below their reorder level. Includes out-of-stock, low-stock, and expiring-soon batches.
                Use the export buttons below to generate a purchase order.
            </p>
        </div>

        {{-- Summary stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Drugs</div>
                <div class="text-2xl font-bold text-gray-700 mt-1">{{ $data['totalDrugs'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-red-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-red-500 uppercase tracking-wide">Out of Stock</div>
                <div class="text-2xl font-bold text-red-700 mt-1">{{ $data['outOfStockCount'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-amber-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-amber-500 uppercase tracking-wide">Low Stock</div>
                <div class="text-2xl font-bold text-amber-700 mt-1">{{ $data['lowStockCount'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-orange-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-orange-500 uppercase tracking-wide">Expiring ≤90 days</div>
                <div class="text-2xl font-bold text-orange-700 mt-1">{{ $data['expiringSoonCount'] }}</div>
            </div>
        </div>

        {{-- Export buttons --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('stock-reorder.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700">
                📊 Export to Excel
            </a>
            <a href="{{ route('stock-reorder.export-csv') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg hover:bg-gray-700">
                📄 Export to CSV
            </a>
        </div>

        {{-- Out of Stock --}}
        @if(!empty($data['outOfStock']))
        <div class="bg-white rounded-xl border border-red-300 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-red-50">
                <h3 class="text-base font-bold text-red-800">🔴 Out of Stock ({{ $data['outOfStockCount'] }})</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Drug Name</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Generic</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Form / Strength</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Current Stock</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Reorder Level</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Reorder Qty</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Cost Price</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Est. Reorder Cost</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['outOfStock'] as $item)
                    <tr class="hover:bg-red-50/50">
                        <td class="px-4 py-2 font-semibold text-red-900">{{ $item['drug']->name }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $item['drug']->generic_name ?? '—' }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $item['drug']->dosage_form ?? '—' }} / {{ $item['drug']->strength ?? '—' }}</td>
                        <td class="px-4 py-2 text-center"><span class="px-2 py-1 rounded text-xs font-bold bg-red-100 text-red-700">0</span></td>
                        <td class="px-4 py-2 text-center">{{ $item['reorder_level'] }}</td>
                        <td class="px-4 py-2 text-center font-semibold">{{ $item['reorder_qty'] }}</td>
                        <td class="px-4 py-2 text-center">₦{{ number_format($item['drug']->cost_price, 2) }}</td>
                        <td class="px-4 py-2 text-center font-bold text-red-700">₦{{ number_format($item['reorder_qty'] * $item['drug']->cost_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Low Stock --}}
        @if(!empty($data['lowStock']))
        <div class="bg-white rounded-xl border border-amber-300 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-amber-50">
                <h3 class="text-base font-bold text-amber-800">🟡 Low Stock ({{ $data['lowStockCount'] }})</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Drug Name</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Generic</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Form / Strength</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Current Stock</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Reorder Level</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Reorder Qty</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Cost Price</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Est. Reorder Cost</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['lowStock'] as $item)
                    <tr class="hover:bg-amber-50/50">
                        <td class="px-4 py-2 font-semibold text-amber-900">{{ $item['drug']->name }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $item['drug']->generic_name ?? '—' }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $item['drug']->dosage_form ?? '—' }} / {{ $item['drug']->strength ?? '—' }}</td>
                        <td class="px-4 py-2 text-center"><span class="px-2 py-1 rounded text-xs font-bold bg-amber-100 text-amber-700">{{ $item['stock'] }}</span></td>
                        <td class="px-4 py-2 text-center">{{ $item['reorder_level'] }}</td>
                        <td class="px-4 py-2 text-center font-semibold">{{ $item['reorder_qty'] }}</td>
                        <td class="px-4 py-2 text-center">₦{{ number_format($item['drug']->cost_price, 2) }}</td>
                        <td class="px-4 py-2 text-center font-bold text-amber-700">₦{{ number_format($item['reorder_qty'] * $item['drug']->cost_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Expiring Soon --}}
        @if(!empty($data['expiringSoon']))
        <div class="bg-white rounded-xl border border-orange-300 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-orange-50">
                <h3 class="text-base font-bold text-orange-800">🟠 Batches Expiring Within 90 Days ({{ $data['expiringSoonCount'] }})</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Drug Name</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Batch #</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Expiry Date</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Days Left</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Qty Remaining</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['expiringSoon'] as $batch)
                    <tr class="hover:bg-orange-50/50">
                        <td class="px-4 py-2 font-semibold">{{ $batch['drug_name'] }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $batch['batch_number'] }}</td>
                        <td class="px-4 py-2 text-center">{{ $batch['expiry_date']->format('d M Y') }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $batch['days_to_expiry'] <= 30 ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }}">{{ $batch['days_to_expiry'] }} days</span>
                        </td>
                        <td class="px-4 py-2 text-center">{{ $batch['quantity_remaining'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Empty state --}}
        @if(empty($data['outOfStock']) && empty($data['lowStock']) && empty($data['expiringSoon']))
        <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
            <div class="text-gray-400 text-4xl mb-3">✅</div>
            <h3 class="font-bold text-gray-800">All stock levels are healthy!</h3>
            <p class="text-gray-500 text-sm mt-1">No drugs are at or below their reorder level, and no batches are expiring within 90 days.</p>
        </div>
        @endif
    </div>
</x-filament-panels::page>
