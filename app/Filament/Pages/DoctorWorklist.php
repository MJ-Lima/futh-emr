<?php

namespace App\Filament\Pages;

use App\Models\Admission;
use App\Models\Appointment;
use App\Models\ConsultationNote;
use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\Prescription;
use App\Models\WardRoundNote;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class DoctorWorklist extends Page
{
    protected string $view = 'filament.pages.doctor-worklist';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Clinical';

    protected static ?string $navigationLabel = 'My Daily Worklist';

    protected static ?string $title = 'My Daily Worklist';

    protected static ?int $navigationSort = 1;

    public function getTitle(): string
    {
        return 'My Daily Worklist — Dr. ' . (auth()->user()?->name ?? '');
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'doctor', 'resident_doctor', 'medical_administrator']);
    }

    /**
     * Get today's data for the worklist.
     */
    public function getWorklistData(): array
    {
        $user = auth()->user();
        $today = today()->toDateString();

        // 1. Today's appointments (where this doctor is the provider)
        $appointments = Appointment::whereDate('appointment_date', $today)
            ->where('provider_id', $user->id)
            ->with(['patient', 'clinic', 'consultationRoom'])
            ->orderBy('appointment_time')
            ->get();

        // Also get unassigned appointments at clinics the doctor might cover
        $unassignedAppointments = Appointment::whereDate('appointment_date', $today)
            ->whereNull('provider_id')
            ->whereIn('status', ['Waiting for Doctor', 'Vitals Taken'])
            ->with(['patient', 'clinic', 'consultationRoom'])
            ->orderBy('queue_number')
            ->get();

        // 2. Patients waiting for doctor (all, not just this doctor's)
        $waitingForDoctor = Appointment::whereDate('appointment_date', $today)
            ->whereIn('status', ['Waiting for Doctor', 'Vitals Taken'])
            ->with(['patient', 'clinic', 'consultationRoom', 'nurse'])
            ->orderBy('queue_number')
            ->get();

        // 3. Pending lab results to review
        $pendingLabResults = LabOrder::where('status', 'Completed')
            ->whereHas('items', function ($q) {
                $q->whereHas('result', function ($r) {
                    $r->where('is_verified', false);
                });
            })
            ->with(['patient', 'items.result'])
            ->latest()
            ->limit(10)
            ->get();

        // 4. Prescriptions pending (Pending status)
        $pendingPrescriptions = Prescription::where('status', 'Pending')
            ->with(['patient', 'items'])
            ->latest()
            ->limit(10)
            ->get();

        // 5. Inpatients under my care
        $myInpatients = Admission::where('status', 'Admitted')
            ->where(function ($q) use ($user) {
                $q->where('attending_doctor_id', $user->id)
                  ->orWhere('resident_doctor_id', $user->id)
                  ->orWhere('admitting_doctor_id', $user->id);
            })
            ->with(['patient', 'ward', 'bed'])
            ->latest('admission_date')
            ->get();

        // 6. My consultation notes today
        $myConsultationsToday = ConsultationNote::where('author_id', $user->id)
            ->whereDate('created_at', $today)
            ->with(['patient', 'clinic'])
            ->latest()
            ->get();

        // 7. My ward round notes today
        $myWardRoundsToday = WardRoundNote::where('doctor_id', $user->id)
            ->whereDate('round_date', $today)
            ->with(['patient', 'ward'])
            ->latest()
            ->get();

        // 8. Inpatients needing ward round (admitted but no ward round today)
        $inpatientsNeedingRound = $myInpatients->filter(function ($adm) use ($today) {
            $lastRound = WardRoundNote::where('admission_id', $adm->id)
                ->whereDate('round_date', $today)->first();
            return !$lastRound;
        });

        return [
            'appointments' => $appointments,
            'unassignedAppointments' => $unassignedAppointments,
            'waitingForDoctor' => $waitingForDoctor,
            'pendingLabResults' => $pendingLabResults,
            'pendingPrescriptions' => $pendingPrescriptions,
            'myInpatients' => $myInpatients,
            'myConsultationsToday' => $myConsultationsToday,
            'myWardRoundsToday' => $myWardRoundsToday,
            'inpatientsNeedingRound' => $inpatientsNeedingRound,
        ];
    }
}
