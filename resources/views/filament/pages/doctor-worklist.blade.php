<x-filament-panels::page>
    @php $data = $this->getWorklistData(); @endphp

    <div class="space-y-6">
        {{-- Header banner --}}
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-600 rounded-xl p-5 text-white shadow-md">
            <h3 class="text-xl font-bold mb-1">My Daily Worklist</h3>
            <p class="text-sm text-emerald-50">
                Dr. {{ auth()->user()->name }} &middot; {{ now()->format('l, d M Y') }}
            </p>
        </div>

        {{-- Summary stat cards --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">My Appointments</div>
                <div class="text-2xl font-bold text-blue-700 mt-1">{{ $data['appointments']->count() }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Waiting for Doctor</div>
                <div class="text-2xl font-bold text-amber-700 mt-1">{{ $data['waitingForDoctor']->count() }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">My Inpatients</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $data['myInpatients']->count() }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pending Lab Results</div>
                <div class="text-2xl font-bold text-purple-700 mt-1">{{ $data['pendingLabResults']->count() }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Ward Rounds Due</div>
                <div class="text-2xl font-bold text-red-700 mt-1">{{ $data['inpatientsNeedingRound']->count() }}</div>
            </div>
        </div>

        {{-- 1. Patients Waiting for Doctor --}}
        @if($data['waitingForDoctor']->isNotEmpty())
        <div class="bg-white rounded-xl border border-amber-300 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-amber-50">
                <h3 class="text-base font-bold text-amber-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Patients Waiting for Doctor ({{ $data['waitingForDoctor']->count() }})
                </h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($data['waitingForDoctor'] as $appt)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-amber-50/50">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 text-amber-700 text-xs font-bold flex items-center justify-center">
                            {{ $appt->queue_number ?? '—' }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ $appt->patient->full_name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $appt->patient->hospital_no }} &middot;
                                {{ $appt->clinic->name }} &middot;
                                Room {{ $appt->consultationRoom?->code ?? '—' }} &middot;
                                {{ $appt->visit_type }}
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ \App\Filament\Resources\ConsultationNotes\ConsultationNoteResource::getUrl('create') }}?patient_id={{ $appt->patient_id }}&clinic_id={{ $appt->clinic_id }}&encounter_id={{ $appt->encounter_id }}"
                           class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-xs font-semibold rounded hover:bg-emerald-700">
                            Start Consultation
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- 2. Ward Rounds Due --}}
        @if($data['inpatientsNeedingRound']->isNotEmpty())
        <div class="bg-white rounded-xl border border-red-300 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-red-50">
                <h3 class="text-base font-bold text-red-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    Ward Rounds Due Today ({{ $data['inpatientsNeedingRound']->count() }})
                </h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($data['inpatientsNeedingRound'] as $adm)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-red-50/50">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded bg-red-100 text-red-700 text-xs font-bold flex items-center justify-center">
                            {{ $adm->lengthOfStayDays() }}d
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ $adm->patient->full_name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $adm->patient->hospital_no }} &middot;
                                {{ $adm->ward?->name }} &middot; Bed {{ $adm->bed?->bed_number }} &middot;
                                {{ $adm->admission_diagnosis ?? '—' }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ \App\Filament\Resources\WardRoundNotes\WardRoundNoteResource::getUrl('create') }}?patient_id={{ $adm->patient_id }}&admission_id={{ $adm->id }}&ward_id={{ $adm->ward_id }}&bed_id={{ $adm->bed_id }}"
                       class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700">
                        Do Ward Round
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- 3. My Appointments Today --}}
        @if($data['appointments']->isNotEmpty())
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-blue-50">
                <h3 class="text-base font-bold text-blue-800">My Appointments Today ({{ $data['appointments']->count() }})</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Time</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Patient</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Clinic</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['appointments'] as $appt)
                    <tr class="hover:bg-blue-50/50">
                        <td class="px-4 py-2 font-semibold">{{ $appt->appointment_time?->format('H:i') }}</td>
                        <td class="px-4 py-2">{{ $appt->patient->full_name }}</td>
                        <td class="px-4 py-2">{{ $appt->clinic->name }}</td>
                        <td class="px-4 py-2"><span class="inline-block px-2 py-0.5 text-xs rounded {{ $appt->visit_type === 'New' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">{{ $appt->visit_type }}</span></td>
                        <td class="px-4 py-2">{{ $appt->status }}</td>
                        <td class="px-4 py-2 text-xs text-gray-500">{{ mb_substr($appt->reason_for_visit, 0, 40) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- 4. Pending Lab Results --}}
        @if($data['pendingLabResults']->isNotEmpty())
        <div class="bg-white rounded-xl border border-purple-300 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-purple-50">
                <h3 class="text-base font-bold text-purple-800">Pending Lab Results to Review ({{ $data['pendingLabResults']->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($data['pendingLabResults'] as $lab)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-purple-50/50">
                    <div>
                        <div class="font-semibold text-gray-900">{{ $lab->order_number }} — {{ $lab->patient->full_name }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $lab->items->count() }} test(s) &middot; Status: {{ $lab->status }}
                        </div>
                    </div>
                    <a href="{{ \App\Filament\Resources\LabOrders\LabOrderResource::getUrl('view', ['record' => $lab]) }}"
                       class="text-purple-600 text-xs font-semibold hover:underline">View Results</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- 5. My Inpatients --}}
        @if($data['myInpatients']->isNotEmpty())
        <div class="bg-white rounded-xl border border-emerald-300 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-emerald-50">
                <h3 class="text-base font-bold text-emerald-800">My Inpatients ({{ $data['myInpatients']->count() }})</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Patient</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Ward / Bed</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Diagnosis</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Days In</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Last Round</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['myInpatients'] as $adm)
                    @php
                        $lastRound = \App\Models\WardRoundNote::where('admission_id', $adm->id)
                            ->latest('round_date')->first();
                    @endphp
                    <tr class="hover:bg-emerald-50/50">
                        <td class="px-4 py-2 font-semibold">{{ $adm->patient->full_name }}</td>
                        <td class="px-4 py-2 text-xs">{{ $adm->ward?->name }} / {{ $adm->bed?->bed_number }}</td>
                        <td class="px-4 py-2 text-xs">{{ mb_substr($adm->admission_diagnosis ?? '—', 0, 40) }}</td>
                        <td class="px-4 py-2 text-center">{{ $adm->lengthOfStayDays() }}</td>
                        <td class="px-4 py-2 text-xs">{{ $lastRound?->round_date?->format('d M') ?? 'Never' }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ \App\Filament\Resources\WardRoundNotes\WardRoundNoteResource::getUrl('create') }}?patient_id={{ $adm->patient_id }}&admission_id={{ $adm->id }}&ward_id={{ $adm->ward_id }}"
                               class="text-emerald-600 text-xs font-semibold hover:underline">Round</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- 6. My Consultation Notes Today --}}
        @if($data['myConsultationsToday']->isNotEmpty())
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="text-base font-bold text-gray-800">My Consultation Notes Today ({{ $data['myConsultationsToday']->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($data['myConsultationsToday'] as $cn)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50">
                    <div>
                        <div class="font-semibold text-gray-900">{{ $cn->note_number }} — {{ $cn->patient->full_name }}</div>
                        <div class="text-xs text-gray-500">{{ $cn->clinic?->name }} &middot; {{ $cn->status }}</div>
                    </div>
                    <div class="flex gap-2">
                        @if(in_array($cn->status, ['Finalized', 'Counter-signed', 'Amended']))
                        <a href="{{ route('consultation-notes.print', $cn) }}" target="_blank" class="text-gray-600 text-xs font-semibold hover:underline">Print</a>
                        @endif
                        <a href="{{ \App\Filament\Resources\ConsultationNotes\ConsultationNoteResource::getUrl('view', ['record' => $cn]) }}" class="text-blue-600 text-xs font-semibold hover:underline">View</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- 7. My Ward Round Notes Today --}}
        @if($data['myWardRoundsToday']->isNotEmpty())
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="text-base font-bold text-gray-800">My Ward Round Notes Today ({{ $data['myWardRoundsToday']->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($data['myWardRoundsToday'] as $wrn)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50">
                    <div>
                        <div class="font-semibold text-gray-900">{{ $wrn->note_number }} — {{ $wrn->patient->full_name }}</div>
                        <div class="text-xs text-gray-500">{{ $wrn->ward?->name }} &middot; {{ $wrn->round_type }} &middot; {{ $wrn->round_date->format('d M') }}</div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('ward-round-notes.print', $wrn) }}" target="_blank" class="text-gray-600 text-xs font-semibold hover:underline">Print</a>
                        <a href="{{ \App\Filament\Resources\WardRoundNotes\WardRoundNoteResource::getUrl('view', ['record' => $wrn]) }}" class="text-blue-600 text-xs font-semibold hover:underline">View</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Empty state --}}
        @if($data['appointments']->isEmpty() && $data['waitingForDoctor']->isEmpty() && $data['myInpatients']->isEmpty() && $data['pendingLabResults']->isEmpty() && $data['myConsultationsToday']->isEmpty() && $data['myWardRoundsToday']->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
            <div class="text-gray-400 text-4xl mb-3">✅</div>
            <h3 class="font-bold text-gray-800">All caught up!</h3>
            <p class="text-gray-500 text-sm mt-1">No pending tasks for today. Enjoy your day, Dr. {{ auth()->user()->name }}.</p>
        </div>
        @endif
    </div>
</x-filament-panels::page>
