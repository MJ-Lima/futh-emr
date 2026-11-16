<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header banner --}}
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-600 rounded-xl p-5 text-white shadow-md">
            <h3 class="text-xl font-bold mb-1">Reporting Table</h3>
            <p class="text-sm text-emerald-50">
                Patient demographics, diagnosis, admission / discharge dates, reason for discharge, attending doctors —
                for Scientific Officer and Medical Records Officer reporting.
            </p>
        </div>

        {{-- Quick stats --}}
        @php
            $today = today()->toDateString();
            $totalAdmissions = \App\Models\Admission::count();
            $currentlyAdmitted = \App\Models\Admission::where('status', 'Admitted')->count();
            $dischargedThisMonth = \App\Models\Admission::where('status', 'Discharged')
                ->whereMonth('discharge_date', now()->month)
                ->whereYear('discharge_date', now()->year)
                ->count();
            $deathsThisYear = \App\Models\Admission::where('status', 'Death')
                ->whereYear('discharge_date', now()->year)
                ->count();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Admissions</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">{{ number_format($totalAdmissions) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Currently Admitted</div>
                <div class="text-2xl font-bold text-amber-700 mt-1">{{ number_format($currentlyAdmitted) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Discharged This Month</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">{{ number_format($dischargedThisMonth) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Deaths This Year</div>
                <div class="text-2xl font-bold text-red-700 mt-1">{{ number_format($deathsThisYear) }}</div>
            </div>
        </div>

        {{-- Export buttons --}}
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('reporting-table.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export to Excel (.xls)
            </a>
            <a href="{{ route('reporting-table.export-csv') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export to CSV
            </a>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Admissions Reporting</h3>
                <p class="text-sm text-gray-500">
                    Use the filters to narrow down by ward, status, admission date, or discharge date.
                    Click <strong>View</strong> on any row to open the full admission record.
                </p>
            </div>
            <div class="p-0">
                {{ $this->table }}
            </div>
        </div>
    </div>
</x-filament-panels::page>
