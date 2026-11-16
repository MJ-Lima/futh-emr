<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Queue Management</h2>
                <p class="mt-1 text-sm text-gray-500">Manage patient queue and track consultations</p>
            </div>
            <a href="/admin/appointments/create" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                <x-filament::icon icon="heroicon-o-plus" class="h-4 w-4" />
                Check In Patient
            </a>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach ($kpis as $kpi)
                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="rounded-lg p-2 bg-{{ $kpi['color'] }}-100">
                            <x-filament::icon icon="{{ $kpi['icon'] }}" class="h-5 w-5 text-{{ $kpi['color'] }}-600" />
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $kpi['value'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $kpi['label'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $kpi['subtitle'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Main Content: Table + Right Sidebar --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Queue Table (left, wide) --}}
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm">
                {{-- Tabs --}}
                <div class="border-b border-gray-200 px-5 pt-4">
                    <div class="flex gap-4">
                        <button class="pb-3 px-1 text-sm font-semibold text-emerald-600 border-b-2 border-emerald-600">
                            All ({{ $tabs['all'] }})
                        </button>
                        <button class="pb-3 px-1 text-sm font-medium text-gray-500 hover:text-gray-700">
                            New ({{ $tabs['new'] }})
                        </button>
                        <button class="pb-3 px-1 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Returning ({{ $tabs['returning'] }})
                        </button>
                    </div>
                </div>

                {{-- Search & Filter Bar --}}
                <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100">
                    <input type="text" placeholder="Search by NIN, Hosp No, Name..." class="flex-1 rounded-lg border-gray-300 text-sm px-3 py-2" />
                    <select class="rounded-lg border-gray-300 text-sm px-3 py-2">
                        <option>All Status</option>
                        <option>Waiting</option>
                        <option>In Consultation</option>
                        <option>Completed</option>
                        <option>Missed</option>
                    </select>
                    <button class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                        <x-filament::icon icon="heroicon-o-arrow-path" class="h-4 w-4" />
                        Refresh
                    </button>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-400 border-b border-gray-100">
                                <th class="py-3 px-5">Queue No</th>
                                <th class="py-3 px-4">Hosp No</th>
                                <th class="py-3 px-4">Name & NIN</th>
                                <th class="py-3 px-4">Doctor</th>
                                <th class="py-3 px-4">Arrival</th>
                                <th class="py-3 px-4">Type</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($queue as $item)
                                <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50">
                                    <td class="py-3 px-5 font-mono text-xs text-gray-700">
                                        {{ $item->queue_number ?? ($item->clinic?->code ?? 'GOPD') . '-' . str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">
                                        {{ $item->patient?->hospital_no ?? '—' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <p class="font-medium text-gray-900">{{ $item->patient?->fullName() ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-400">{{ $item->patient?->nin ?? 'No NIN' }}</p>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">
                                        {{ $item->provider?->name ?? 'Unassigned' }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">
                                        {{ optional($item->appointment_time)->format('H:i') ?? '—' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if ($item->visit_type === 'New')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">New</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Returning</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        @php
                                            $statusColors = [
                                                'Scheduled' => 'bg-gray-100 text-gray-700',
                                                'Checked In' => 'bg-orange-100 text-orange-700',
                                                'In Progress' => 'bg-blue-100 text-blue-700',
                                                'Completed' => 'bg-green-100 text-green-700',
                                                'No Show' => 'bg-red-100 text-red-700',
                                            ];
                                            $badgeColor = $statusColors[$item->status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                            {{ $item->status }}
                                        </span>
                                        @if ($item->priority === 'Emergency' || $item->priority === 'Urgent')
                                            <span class="inline-flex items-center px-2 py-0.5 ml-1 rounded-full text-xs font-medium bg-pink-100 text-pink-700">
                                                High Priority
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-1">
                                            <a href="/admin/appointments/{{ $item->id }}" class="text-gray-400 hover:text-gray-600">
                                                <x-filament::icon icon="heroicon-o-eye" class="h-4 w-4" />
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-gray-400">No patients in queue for today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between px-5 py-3 border-t border-gray-100">
                    <p class="text-xs text-gray-500">Showing 1 to {{ $queue->count() }} of {{ $queue->count() }} entries</p>
                    <div class="flex gap-1">
                        <button class="px-3 py-1 text-xs rounded border border-gray-200 text-gray-500">Previous</button>
                        <button class="px-3 py-1 text-xs rounded bg-emerald-600 text-white">1</button>
                        <button class="px-3 py-1 text-xs rounded border border-gray-200 text-gray-500">Next</button>
                    </div>
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="space-y-4">
                {{-- Queue Summary --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Queue Summary</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-pink-500"></span>
                                <span class="text-sm text-gray-600">High Priority</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ $summary['high_priority'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <span class="text-sm text-gray-600">Total Checked In</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ $summary['checked_in'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                                <span class="text-sm text-gray-600">Waiting</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ $summary['waiting'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                                <span class="text-sm text-gray-600">In Consultation</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ $summary['in_consultation'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                                <span class="text-sm text-gray-600">Completed</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ $summary['completed'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                <span class="text-sm text-gray-600">Missed</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ $summary['missed'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Average Wait Time --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg p-3 bg-emerald-50">
                            <x-filament::icon icon="heroicon-o-clock" class="h-6 w-6 text-emerald-600" />
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Average Wait Time</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $avgWaitTime }} <span class="text-lg text-gray-400">mins</span></p>
                        </div>
                    </div>
                </div>

                {{-- Clinic Selector --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Filter by Clinic</h3>
                    <select class="w-full rounded-lg border-gray-300 text-sm px-3 py-2">
                        <option value="">All Clinics</option>
                        @foreach ($clinics as $clinic)
                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
