<x-filament-panels::page>
    @php $data = $this->getRosterData(); @endphp

    <div class="space-y-6">
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-600 rounded-xl p-5 text-white shadow-md">
            <h3 class="text-xl font-bold mb-1">Staff Duty Roster Calendar</h3>
            <p class="text-sm text-emerald-50">Active and upcoming duty postings for all staff. Shows shift, role, department, clinic, and status.</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Postings</div>
                <div class="text-2xl font-bold text-gray-700 mt-1">{{ $data['totalPostings'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-emerald-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-emerald-500 uppercase tracking-wide">Active Now</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $data['activePostings'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Shifts</div>
                <div class="text-sm text-gray-600 mt-1">
                    @foreach($data['byShift'] as $shift => $count)
                        <span class="inline-block px-2 py-0.5 mr-1 mb-1 text-xs rounded bg-gray-100">{{ $shift }}: {{ $count }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Calendar grid --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="text-base font-bold text-gray-800">Duty Roster — Next 30 Days</h3>
            </div>
            @if(!empty($data['byDate']))
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @php $today = now()->format('Y-m-d'); @endphp
                @foreach($data['byDate'] as $dateStr => $postings)
                @php
                    $date = \Carbon\Carbon::parse($dateStr);
                    $isToday = $dateStr === $today;
                    $isPast = $date < now()->startOfDay();
                    $cardBorder = $isToday ? 'border-emerald-400 bg-emerald-50' : ($isPast ? 'border-gray-300 bg-gray-50 opacity-60' : 'border-gray-200');
                @endphp
                <div class="rounded-xl border {{ $cardBorder }} p-3 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-bold text-sm {{ $isToday ? 'text-emerald-700' : 'text-gray-800' }}">
                            {{ $date->format('D, d M') }}
                            @if($isToday)<span class="ml-1 text-xs bg-emerald-600 text-white px-1.5 py-0.5 rounded-full">TODAY</span>@endif
                        </div>
                        <span class="text-xs text-gray-400">{{ count($postings) }} staff</span>
                    </div>
                    <div class="space-y-1.5">
                        @foreach($postings as $p)
                        <div class="flex items-start gap-2 text-xs">
                            <div class="flex-shrink-0 w-2 h-2 mt-1 rounded-full {{ $p['shift'] === 'Night' ? 'bg-indigo-500' : ($p['shift'] === 'Evening' ? 'bg-amber-500' : 'bg-emerald-500') }}"></div>
                            <div>
                                <div class="font-semibold text-gray-800">{{ $p['staff_name'] }}</div>
                                <div class="text-gray-500">{{ $p['shift'] }} &middot; {{ $p['role_in_unit'] }} &middot; {{ $p['department'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-10 text-center">
                <div class="text-gray-400 text-4xl mb-3">📅</div>
                <h3 class="font-bold text-gray-800">No duty postings found</h3>
                <p class="text-gray-500 text-sm mt-1">Create duty postings under Administration → Duty Postings to populate this calendar.</p>
            </div>
            @endif
        </div>

        {{-- Export buttons --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('duty-roster.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700">📊 Export to Excel</a>
        </div>
    </div>
</x-filament-panels::page>
