<x-filament-panels::page>
    @php $data = $this->getMortalityData(); @endphp

    <div class="space-y-6">
        <div class="bg-gradient-to-r from-red-700 to-red-600 rounded-xl p-5 text-white shadow-md">
            <h3 class="text-xl font-bold mb-1">Mortality Review Report</h3>
            <p class="text-sm text-red-50">Review all inpatient deaths: by ward, age group, gender, admission type, and average length of stay before death.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('mortality-review.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700">📊 Export to Excel</a>
            <a href="{{ route('mortality-review.export-csv') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg hover:bg-gray-700">📄 Export to CSV</a>
        </div>

        {{-- Summary stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-red-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-red-500 uppercase tracking-wide">Total Deaths</div>
                <div class="text-2xl font-bold text-red-700 mt-1">{{ number_format($data['totalDeaths']) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Avg LOS Before Death</div>
                <div class="text-2xl font-bold text-gray-700 mt-1">{{ $data['avgLOS'] }} days</div>
            </div>
            <div class="bg-white rounded-xl border border-blue-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-blue-500 uppercase tracking-wide">Male Deaths</div>
                <div class="text-2xl font-bold text-blue-700 mt-1">{{ $data['byGender']['Male'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-xl border border-pink-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-pink-500 uppercase tracking-wide">Female Deaths</div>
                <div class="text-2xl font-bold text-pink-700 mt-1">{{ $data['byGender']['Female'] ?? 0 }}</div>
            </div>
        </div>

        {{-- Two-column: By Ward + By Age Group --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="text-sm font-bold text-gray-800 mb-3">Deaths by Ward</h3>
                @if(!empty($data['byWard']))
                <table class="w-full text-sm"><tbody>
                    @foreach($data['byWard'] as $ward => $count)
                    <tr class="border-b border-gray-100"><td class="py-1.5 font-medium">{{ $ward }}</td><td class="text-center py-1.5 font-bold text-red-600">{{ $count }}</td></tr>
                    @endforeach
                </tbody></table>
                @else<p class="text-gray-400 text-sm text-center py-4">No data.</p>@endif
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="text-sm font-bold text-gray-800 mb-3">Deaths by Age Group</h3>
                <table class="w-full text-sm"><tbody>
                    @foreach($data['byAgeGroup'] as $group => $count)
                    <tr class="border-b border-gray-100"><td class="py-1.5 font-medium">{{ $group }}</td><td class="text-center py-1.5 font-bold text-red-600">{{ $count }}</td></tr>
                    @endforeach
                </tbody></table>
            </div>
        </div>

        {{-- Detailed records --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50"><h3 class="text-base font-bold text-gray-800">Death Records ({{ $data['totalDeaths'] }})</h3></div>
            @if(!empty($data['records']))
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Admission #</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Patient</th>
                    <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Age/Sex</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Ward</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Admitted</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Died</th>
                    <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">LOS (d)</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Admission Dx</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Discharge Dx</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Attending Doctor</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['records'] as $r)
                    <tr class="hover:bg-red-50/30">
                        <td class="px-3 py-2 font-semibold">{{ $r['admission_number'] }}</td>
                        <td class="px-3 py-2">{{ $r['patient'] }}</td>
                        <td class="px-3 py-2 text-center">{{ $r['age'] }}/{{ $r['gender'] }}</td>
                        <td class="px-3 py-2 text-xs">{{ $r['ward'] }}</td>
                        <td class="px-3 py-2 text-xs">{{ $r['admission_date'] }}</td>
                        <td class="px-3 py-2 text-xs font-semibold text-red-600">{{ $r['death_date'] }}</td>
                        <td class="px-3 py-2 text-center">{{ $r['los'] }}</td>
                        <td class="px-3 py-2 text-xs">{{ $r['admission_diagnosis'] }}</td>
                        <td class="px-3 py-2 text-xs">{{ $r['discharge_diagnosis'] }}</td>
                        <td class="px-3 py-2 text-xs">{{ $r['attending_doctor'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-10 text-center"><div class="text-gray-400 text-4xl mb-3">📋</div><h3 class="font-bold text-gray-800">No death records found</h3></div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
