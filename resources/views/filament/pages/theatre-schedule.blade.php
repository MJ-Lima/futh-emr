<x-filament-panels::page>
    @php $data = $this->getScheduleData(); @endphp

    <div class="space-y-6">
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-600 rounded-xl p-5 text-white shadow-md">
            <h3 class="text-xl font-bold mb-1">Theatre / Operation Scheduling</h3>
            <p class="text-sm text-emerald-50">7-day forward schedule showing all procedure bookings per theatre room. Monitor elective, urgent, and emergency cases with pre-op checklist status.</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Active Theatres</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $data['stats']['totalTheatres'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-blue-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-blue-500 uppercase tracking-wide">Scheduled</div>
                <div class="text-2xl font-bold text-blue-700 mt-1">{{ $data['stats']['scheduled'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-amber-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-amber-500 uppercase tracking-wide">Confirmed</div>
                <div class="text-2xl font-bold text-amber-700 mt-1">{{ $data['stats']['confirmed'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-green-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-green-500 uppercase tracking-wide">In Progress</div>
                <div class="text-2xl font-bold text-green-700 mt-1">{{ $data['stats']['inProgress'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Completed (7d)</div>
                <div class="text-2xl font-bold text-gray-700 mt-1">{{ $data['stats']['completed'] }}</div>
            </div>
        </div>

        {{-- Priority badges --}}
        <div class="flex items-center gap-4 text-sm">
            <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-gray-400"></span> Elective ({{ $data['stats']['elective'] }})</span>
            <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-500"></span> Urgent ({{ $data['stats']['urgent'] }})</span>
            <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500"></span> Emergency ({{ $data['stats']['emergency'] }})</span>
        </div>

        {{-- Schedule grid --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-x-auto">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="text-base font-bold text-gray-800">7-Day Theatre Schedule</h3>
            </div>
            <table class="w-full text-sm min-w-[800px]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase sticky left-0 bg-gray-50">Theatre</th>
                        @foreach($data['dayHeaders'] as $day)
                        <th class="px-3 py-3 text-center text-xs font-semibold {{ $day['isToday'] ? 'text-emerald-600 bg-emerald-50' : 'text-gray-500' }} uppercase min-w-[140px]">
                            {{ $day['label'] }}
                            @if($day['isToday'])<span class="ml-1 text-[10px] bg-emerald-600 text-white px-1.5 py-0.5 rounded-full">TODAY</span>@endif
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['theatres'] as $theatreData)
                    @php $theatre = $theatreData['theatre']; @endphp
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-3 font-semibold sticky left-0 bg-white border-r border-gray-200">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $theatreData['in_use'] ? 'bg-red-500 animate-pulse' : 'bg-green-400' }}"></span>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $theatre->code }}</div>
                                    <div class="text-xs text-gray-500">{{ $theatre->name }}</div>
                                    <div class="text-[10px] text-gray-400">Today: {{ $theatreData['today_count'] }} booking(s)</div>
                                </div>
                            </div>
                        </td>
                        @foreach($data['dayHeaders'] as $day)
                        @php $bookings = $theatreData['days'][$day['date']] ?? []; @endphp
                        <td class="px-2 py-2 align-top {{ $day['isToday'] ? 'bg-emerald-50/30' : '' }}">
                            @if(!empty($bookings))
                            <div class="space-y-1.5">
                                @foreach($bookings as $b)
                                <div class="rounded-lg p-2 text-xs {{ $b['priority'] === 'Emergency' ? 'bg-red-50 border border-red-200' : ($b['priority'] === 'Urgent' ? 'bg-amber-50 border border-amber-200' : 'bg-blue-50 border border-blue-200') }}">
                                    <div class="font-bold text-gray-800">{{ $b['start'] }}–{{ $b['end'] }}</div>
                                    <div class="text-gray-700">{{ $b['patient'] }}</div>
                                    <div class="text-gray-500">{{ $b['procedure'] }}</div>
                                    <div class="text-gray-400 text-[10px]">Dr. {{ $b['surgeon'] }}</div>
                                    <div class="flex items-center gap-1 mt-1">
                                        <span class="inline-block px-1.5 py-0.5 text-[9px] rounded {{ $b['status'] === 'In Progress' ? 'bg-green-200 text-green-800' : ($b['status'] === 'Completed' ? 'bg-gray-200 text-gray-600' : 'bg-blue-200 text-blue-800') }}">{{ $b['status'] }}</span>
                                        @if($b['consent_signed'])<span class="text-[9px] text-green-600" title="Consent signed">✓C</span>@endif
                                        @if($b['fasting_confirmed'])<span class="text-[9px] text-green-600" title="Fasting confirmed">✓F</span>@endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center text-gray-300 text-xs py-2">—</div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Legend --}}
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <div class="flex items-start gap-3">
                <div class="text-gray-500 text-xl">ℹ️</div>
                <div class="text-sm text-gray-600">
                    <strong>Pre-op Checklist:</strong>
                    <span class="text-green-600 font-semibold">✓C</span> = Consent signed,
                    <span class="text-green-600 font-semibold">✓F</span> = Fasting confirmed.
                    A red dot next to the theatre name means a procedure is currently in progress.
                    Create or manage bookings under <strong>Inpatient → Procedure Bookings</strong>.
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
