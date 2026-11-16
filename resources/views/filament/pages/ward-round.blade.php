<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header banner --}}
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-600 rounded-xl p-5 text-white shadow-md">
            <h3 class="text-xl font-bold mb-1">Ward Round — Inpatient Review</h3>
            <p class="text-sm text-emerald-50">
                Patients grouped by ward. Click <strong>New Ward Round Note</strong> on any patient to record today's SOAP note,
                vital signs, medication changes, and plan for discharge.
            </p>
        </div>

        @php $grouped = $this->getWardsWithPatients(); @endphp

        @if(empty($grouped))
            <div class="bg-white rounded-xl border border-amber-300 p-6 text-center">
                <div class="text-amber-700 text-3xl mb-2">🛏️</div>
                <h3 class="font-bold text-gray-800">No active admissions</h3>
                <p class="text-gray-600 text-sm mt-1">
                    There are no patients currently admitted to any ward. Visit <strong>Inpatient → Admissions</strong> to admit a patient.
                </p>
            </div>
        @endif

        @php
            $totalAdmitted = 0;
            foreach ($grouped as $g) { $totalAdmitted += $g['count']; }
        @endphp

        {{-- Quick stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Admitted</div>
                <div class="text-2xl font-bold text-amber-700 mt-1">{{ number_format($totalAdmitted) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Active Wards</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">{{ count($grouped) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Ward Rounds Today</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">
                    {{ \App\Models\WardRoundNote::whereDate('round_date', today())->count() }}
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">My Rounds Today</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">
                    {{ \App\Models\WardRoundNote::whereDate('round_date', today())->where('doctor_id', auth()->id())->count() }}
                </div>
            </div>
        </div>

        {{-- Wards grouped --}}
        @foreach($grouped as $g)
            <div class="space-y-3">
                {{-- Ward header --}}
                <div class="flex items-center gap-3 pt-2">
                    <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $g['ward']->name }}</h3>
                    <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                        {{ $g['count'] }} / {{ $g['capacity'] }} beds
                    </span>
                    <span class="text-xs text-gray-500">{{ $g['ward']->type }}</span>
                </div>

                {{-- Patient cards grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($g['patients'] as $p)
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            {{-- Patient header --}}
                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $p['patient']->full_name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $p['patient']->hospital_no }} &middot;
                                            {{ $p['patient']->gender ?? '—' }}/{{ $p['patient']->age ?? '—' }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-semibold px-2 py-0.5 rounded">
                                            Bed {{ $p['bed']?->bed_number ?? '—' }}
                                        </span>
                                        <div class="text-xs text-gray-400 mt-1">{{ $p['days_admitted'] }} days in</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Admission info --}}
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="text-xs text-gray-500 uppercase font-semibold mb-1">Admission</div>
                                <div class="text-sm text-gray-700">{{ $p['admission']->admission_number }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    Admitted: {{ $p['admission']->admission_date?->format('d M Y') }}
                                    &middot; Type: {{ $p['admission']->admission_type }}
                                </div>
                                <div class="text-xs text-gray-700 mt-1 italic">
                                    <strong>Dx:</strong> {{ $p['admission']->admission_diagnosis ? mb_substr($p['admission']->admission_diagnosis, 0, 80) : '—' }}
                                </div>
                            </div>

                            {{-- Last ward round note --}}
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="text-xs text-gray-500 uppercase font-semibold mb-1">Last Ward Round</div>
                                @if($p['last_note'])
                                    <div class="text-xs text-gray-400">
                                        {{ $p['last_note']->round_date?->format('d M Y') }} &middot;
                                        {{ $p['last_note']->round_type }}
                                        &middot; Dr. {{ $p['last_note']->doctor?->name }}
                                    </div>
                                    <div class="text-xs text-gray-700 mt-1">
                                        <strong>Assessment:</strong> {{ $p['last_note_summary'] ?? '—' }}
                                    </div>
                                @else
                                    <div class="text-xs text-gray-400 italic">No previous ward round notes recorded.</div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="px-4 py-3 flex items-center gap-2">
                                <a href="{{ \App\Filament\Resources\WardRoundNotes\WardRoundNoteResource::getUrl('create') }}?patient_id={{ $p['patient']->id }}&admission_id={{ $p['admission']->id }}&ward_id={{ $g['ward']->id }}&bed_id={{ $p['bed']?->id }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-xs font-semibold rounded hover:bg-emerald-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    New Ward Round Note
                                </a>
                                @if($p['last_note'])
                                <a href="{{ \App\Filament\Resources\WardRoundNotes\WardRoundNoteResource::getUrl('view', ['record' => $p['last_note']]) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-gray-700 border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                                    View Last Note
                                </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Help note --}}
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mt-6">
            <div class="flex items-start gap-3">
                <div class="text-gray-500 text-xl">ℹ️</div>
                <div class="text-sm text-gray-600">
                    <strong>Ward Round Workflow:</strong>
                    Each morning / evening / night, the doctor on call reviews each inpatient and writes a SOAP note
                    (Subjective / Objective / Assessment / Plan). The vital signs can be captured directly in the ward
                    round note for speed. Use <strong>Plan for Discharge</strong> to record discharge readiness, and
                    <strong>Nurse Action Required</strong> for shift handover instructions.
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
