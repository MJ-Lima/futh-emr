<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header summary --}}
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-600 rounded-xl p-5 text-white shadow-md">
            <h3 class="text-xl font-bold mb-1">Consultation Rooms — Live Status</h3>
            <p class="text-sm text-emerald-50">
                Patients who have completed vitals are <strong>auto-allocated</strong> to the next available room in their clinic. Doctors see the patient queue per room below, ordered by queue position.
            </p>
        </div>

        @php $grouped = $this->getRoomsByClinic(); @endphp

        @if(empty($grouped))
            <div class="bg-white rounded-xl border border-amber-300 p-6 text-center">
                <div class="text-amber-700 text-3xl mb-2">🏥</div>
                <h3 class="font-bold text-gray-800">No consultation rooms configured</h3>
                <p class="text-gray-600 text-sm mt-1">
                    Add consultation rooms for your clinics at <strong>Front Desk → Consultation Rooms</strong>.
                    The auto-allocation will not work without rooms.
                </p>
            </div>
        @endif

        @foreach($grouped as $clinicName => $data)
            <div class="space-y-3">
                {{-- Clinic header --}}
                <div class="flex items-center gap-3 pt-2">
                    <div class="h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $clinicName }}</h3>
                    <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                        {{ count($data['rooms']) }} room(s)
                    </span>
                </div>

                {{-- Rooms grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($data['rooms'] as $roomData)
                        @php
                            $room = $roomData['room'];
                            $patients = $roomData['patients'];
                            $load = $roomData['load'];
                            $isAvailable = $roomData['is_available'];
                        @endphp
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            {{-- Room header --}}
                            <div class="px-4 py-3 border-b border-gray-100 {{ $isAvailable ? 'bg-emerald-50' : 'bg-amber-50' }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="font-bold text-gray-900 text-base">{{ $room->code }}</span>
                                        <span class="text-gray-600 text-sm ml-2">{{ $room->name }}</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $isAvailable ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $isAvailable ? 'Available' : 'Busy' }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    Load: <strong>{{ $load }}/{{ $room->capacity }}</strong>
                                    @if($room->assignedDoctor)
                                        &middot; Doctor: <strong>{{ $room->assignedDoctor->name }}</strong>
                                    @else
                                        &middot; <em>No default doctor</em>
                                    @endif
                                </div>
                            </div>

                            {{-- Patient list --}}
                            <div class="p-3">
                                @if($patients->isEmpty())
                                    <div class="text-center py-6 text-gray-400">
                                        <div class="text-3xl mb-2">🪑</div>
                                        <p class="text-sm">No patients in this room</p>
                                    </div>
                                @else
                                    <ol class="space-y-2">
                                        @foreach($patients as $patient)
                                            <li class="flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="flex-shrink-0 w-7 h-7 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center">
                                                    {{ $patient->room_queue_position ?? '?' }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-semibold text-gray-900 truncate">{{ $patient->patient->full_name }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $patient->patient->hospital_no }} &middot;
                                                        Queue: {{ $patient->queue_number ?? '—' }} &middot;
                                                        {{ $patient->visit_type }}
                                                    </div>
                                                    @if($patient->status === 'In Consultation')
                                                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-700">
                                                            In Consultation
                                                        </span>
                                                    @elseif($patient->status === 'Waiting for Doctor')
                                                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-700">
                                                            Waiting ({{ $patient->room_queue_position ?? 1 }} in line)
                                                        </span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ol>
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
                    <strong>How allocation works:</strong> When the nurse completes vitals,
                    the system picks the consultation room in the patient's clinic with the smallest current load.
                    If all rooms are at capacity, the patient is queued in the room with the smallest load (position 1 = next to be seen).
                    Add more rooms at <strong>Front Desk → Consultation Rooms</strong> to speed up patient flow.
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
