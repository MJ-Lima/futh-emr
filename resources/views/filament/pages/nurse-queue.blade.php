<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Stats summary --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            @php
                $user = auth()->user();
                $today = today()->toDateString();
                $clinicId = $user->default_clinic_id;
                $clinic = $clinicId ? \App\Models\Clinic::find($clinicId) : null;
            @endphp

            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Today's Queue</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">
                    {{ \App\Models\Appointment::where('appointment_date', $today)
                        ->whereIn('status', ['Checked In', 'In Nurse Queue'])
                        ->where('vitals_status', '!=', 'Completed')
                        ->when($clinicId, fn ($q) => $q->where('clinic_id', $clinicId))
                        ->count() }}
                </div>
                <div class="text-xs text-gray-500">patients waiting for vitals</div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Vitals Completed</div>
                <div class="text-2xl font-bold text-green-700 mt-1">
                    {{ \App\Models\Appointment::where('appointment_date', $today)
                        ->where('vitals_status', 'Completed')
                        ->when($clinicId, fn ($q) => $q->where('clinic_id', $clinicId))
                        ->count() }}
                </div>
                <div class="text-xs text-gray-500">patients seen today</div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Clinic</div>
                <div class="text-lg font-bold text-emerald-700 mt-1">
                    {{ $clinic?->name ?? 'All clinics (Admin)' }}
                </div>
                <div class="text-xs text-gray-500">{{ $clinic ? $clinic->active_consultation_rooms_count . ' rooms' : 'Super Admin view' }}</div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Awaiting Card Fee</div>
                <div class="text-2xl font-bold text-amber-700 mt-1">
                    {{ \App\Models\Appointment::where('appointment_date', $today)
                        ->where('visit_type', 'New')
                        ->where('card_fee_paid', false)
                        ->when($clinicId, fn ($q) => $q->where('clinic_id', $clinicId))
                        ->count() }}
                </div>
                <div class="text-xs text-gray-500">new patients blocked at front desk</div>
            </div>
        </div>

        {{-- Workflow legend --}}
        <div class="bg-emerald-50 border-l-4 border-emerald-700 rounded-r-lg p-4 mb-4">
            <div class="flex items-start gap-3">
                <div class="text-emerald-700 text-xl mt-0.5">📋</div>
                <div class="text-sm text-gray-700">
                    <strong class="text-emerald-800">Patient Journey:</strong>
                    Record Officer books appointment → Front Desk takes Card Fee (new patients only) →
                    Patient is checked in and joins the Nurse Queue → Nurse starts vitals →
                    On completion, patient is <strong>auto-allocated</strong> to the next available consultation room
                    in the clinic, based on their queue number and the number of rooms configured for the clinic.
                </div>
            </div>
        </div>

        {{-- Queue table --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Today's Nurse Queue</h3>
                <p class="text-sm text-gray-500">Patients waiting for vital signs at your clinic. Click <strong>Start Vitals</strong> when you begin, then <strong>Record Vitals</strong>, and finally <strong>Complete &amp; Allocate Room</strong> to send the patient to the doctor.</p>
            </div>
            <div class="p-0">
                {{ $this->table }}
            </div>
        </div>
    </div>
</x-filament-panels::page>
