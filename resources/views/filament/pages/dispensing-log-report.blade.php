<x-filament-panels::page>
    @php $data = $this->getDispensingData(); @endphp

    <div class="space-y-6">
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-600 rounded-xl p-5 text-white shadow-md">
            <h3 class="text-xl font-bold mb-1">Pharmacy Dispensing Log Report</h3>
            <p class="text-sm text-emerald-50">Recent dispensing records, top dispensed drugs, and dispensing point activity.</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Dispensed</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">{{ number_format($data['totalDispensed']) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Quantity</div>
                <div class="text-2xl font-bold text-blue-700 mt-1">{{ number_format($data['totalQuantity']) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Unique Patients</div>
                <div class="text-2xl font-bold text-amber-700 mt-1">{{ number_format($data['uniquePatients']) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Unique Drugs</div>
                <div class="text-2xl font-bold text-purple-700 mt-1">{{ number_format($data['uniqueDrugs']) }}</div>
            </div>
        </div>

        {{-- Export buttons --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('dispensing-log.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700">📊 Export to Excel</a>
            <a href="{{ route('dispensing-log.export-csv') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg hover:bg-gray-700">📄 Export to CSV</a>
        </div>

        {{-- Two column: Top drugs + By dispensing point --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="text-sm font-bold text-gray-800 mb-3">Top 10 Dispensed Drugs</h3>
                @if(!empty($data['topDrugs']))
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-gray-200"><th class="text-left py-1 text-xs text-gray-500 uppercase">Drug</th><th class="text-center py-1 text-xs text-gray-500 uppercase">Dispenses</th><th class="text-center py-1 text-xs text-gray-500 uppercase">Total Qty</th></tr></thead>
                    <tbody>
                        @foreach($data['topDrugs'] as $name => $stats)
                        <tr class="border-b border-gray-100"><td class="py-1.5 font-medium">{{ $name ?? '—' }}</td><td class="text-center py-1.5">{{ $stats['count'] }}</td><td class="text-center py-1.5">{{ $stats['qty'] }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-gray-400 text-sm text-center py-4">No dispensing records.</p>
                @endif
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="text-sm font-bold text-gray-800 mb-3">Dispensing by Point</h3>
                @if(!empty($data['byPoint']))
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-gray-200"><th class="text-left py-1 text-xs text-gray-500 uppercase">Dispensing Point</th><th class="text-center py-1 text-xs text-gray-500 uppercase">Dispenses</th></tr></thead>
                    <tbody>
                        @foreach($data['byPoint'] as $name => $count)
                        <tr class="border-b border-gray-100"><td class="py-1.5 font-medium">{{ $name ?? '—' }}</td><td class="text-center py-1.5">{{ $count }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-gray-400 text-sm text-center py-4">No dispensing points recorded.</p>
                @endif
            </div>
        </div>

        {{-- Detailed log --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="text-base font-bold text-gray-800">Recent Dispensing Records ({{ $data['totalDispensed'] }})</h3>
            </div>
            @if($data['logs']->isNotEmpty())
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Date/Time</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Patient</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Drug</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Batch</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Qty Dispensed</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Balance After</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Dispensing Point</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Dispensed By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['logs'] as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-xs">{{ $log->dispensed_at?->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-2 font-medium">{{ $log->patient?->full_name ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $log->drug?->name ?? '—' }}</td>
                        <td class="px-4 py-2 text-xs text-gray-500">{{ $log->drugBatch?->batch_number ?? '—' }}</td>
                        <td class="px-4 py-2 text-center font-semibold">{{ $log->quantity_dispensed }}</td>
                        <td class="px-4 py-2 text-center text-gray-500">{{ $log->balance_after }}</td>
                        <td class="px-4 py-2 text-xs">{{ $log->dispensingPoint?->name ?? '—' }}</td>
                        <td class="px-4 py-2 text-xs">{{ $log->user?->name ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-10 text-center">
                <div class="text-gray-400 text-4xl mb-3">📋</div>
                <h3 class="font-bold text-gray-800">No dispensing records found</h3>
                <p class="text-gray-500 text-sm mt-1">Dispensing records will appear here once prescriptions are dispensed from the Pharmacy module.</p>
            </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
