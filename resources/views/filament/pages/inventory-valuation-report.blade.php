<x-filament-panels::page>
    @php $data = $this->getValuationData(); @endphp

    <div class="space-y-6">
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-600 rounded-xl p-5 text-white shadow-md">
            <h3 class="text-xl font-bold mb-1">Inventory Valuation Report</h3>
            <p class="text-sm text-emerald-50">Stock valuation at cost and selling price for pharmacy and general stores. Includes margin analysis and total asset value.</p>
        </div>

        {{-- Export buttons --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory-valuation.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700">📊 Export to Excel</a>
            <a href="{{ route('inventory-valuation.export-csv') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg hover:bg-gray-700">📄 Export to CSV</a>
        </div>

        {{-- Summary cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pharmacy Items</div>
                <div class="text-2xl font-bold text-blue-700 mt-1">{{ number_format($data['pharmacyTotalItems']) }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $data['pharmacyTotalBatches'] }} batches</div>
            </div>
            <div class="bg-white rounded-xl border border-emerald-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-emerald-500 uppercase tracking-wide">Pharmacy Cost Value</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">₦{{ number_format($data['pharmacyTotalCost'], 2) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-amber-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-amber-500 uppercase tracking-wide">Pharmacy Sell Value</div>
                <div class="text-2xl font-bold text-amber-700 mt-1">₦{{ number_format($data['pharmacyTotalSell'], 2) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-green-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-green-500 uppercase tracking-wide">Potential Margin</div>
                <div class="text-2xl font-bold text-green-700 mt-1">₦{{ number_format($data['pharmacyMargin'], 2) }}</div>
            </div>
        </div>

        {{-- Grand total banner --}}
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-xl p-5 text-white shadow-md flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-gray-300 uppercase tracking-wide">Total Inventory Asset Value (at Cost)</div>
                <div class="text-3xl font-bold mt-1">₦{{ number_format($data['grandTotal'], 2) }}</div>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-400">Potential Revenue</div>
                <div class="text-2xl font-bold text-emerald-400">₦{{ number_format($data['potentialRevenue'], 2) }}</div>
            </div>
        </div>

        {{-- Pharmacy valuation table --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="text-base font-bold text-gray-800">Pharmacy Inventory Valuation ({{ $data['pharmacyTotalItems'] }} items)</h3>
            </div>
            @if(!empty($data['pharmacyItems']))
            <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Drug Name</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Generic</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Stock Qty</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Cost Price ₦</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Sell Price ₦</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Cost Value ₦</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Sell Value ₦</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Margin ₦</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['pharmacyItems'] as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-semibold">{{ $item['name'] }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $item['generic'] }}</td>
                        <td class="px-4 py-2 text-center">{{ $item['stock'] }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($item['cost_price'], 2) }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($item['sell_price'], 2) }}</td>
                        <td class="px-4 py-2 text-right font-semibold text-emerald-700">{{ number_format($item['cost_value'], 2) }}</td>
                        <td class="px-4 py-2 text-right font-semibold text-amber-700">{{ number_format($item['sell_value'], 2) }}</td>
                        <td class="px-4 py-2 text-right font-semibold text-green-700">{{ number_format($item['margin'], 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-emerald-50 font-bold">
                        <td colspan="5" class="px-4 py-3 text-right">TOTAL PHARMACY</td>
                        <td class="px-4 py-3 text-right text-emerald-700">₦{{ number_format($data['pharmacyTotalCost'], 2) }}</td>
                        <td class="px-4 py-3 text-right text-amber-700">₦{{ number_format($data['pharmacyTotalSell'], 2) }}</td>
                        <td class="px-4 py-3 text-right text-green-700">₦{{ number_format($data['pharmacyMargin'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
            @else
            <div class="p-10 text-center"><div class="text-gray-400 text-4xl mb-3">💊</div><h3 class="font-bold text-gray-800">No pharmacy stock</h3></div>
            @endif
        </div>

        {{-- General stores valuation --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="text-base font-bold text-gray-800">General Stores Inventory ({{ $data['storeTotalItems'] }} items)</h3>
            </div>
            @if(!empty($data['storeValuation']))
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Item</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Qty</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">UoM</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Value ₦</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['storeValuation'] as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-semibold">{{ $item['name'] }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $item['code'] }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $item['category'] }}</td>
                        <td class="px-4 py-2 text-center">{{ $item['quantity'] }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $item['unit'] }}</td>
                        <td class="px-4 py-2 text-right font-semibold">{{ number_format($item['value'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-10 text-center"><div class="text-gray-400 text-4xl mb-3">📦</div><h3 class="font-bold text-gray-800">No general store items with stock</h3></div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
