<?php

namespace App\Filament\Pages;

use App\Models\Appointment;
use App\Models\ConsultationRoom;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;

class ConsultationQueue extends Page
{
    protected string $view = 'filament.pages.consultation-queue';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static string|\UnitEnum|null $navigationGroup = 'Front Desk';

    protected static ?string $navigationLabel = 'Consultation Queue (Doctors)';

    protected static ?string $title = 'Consultation Queue — Per Room';

    protected static ?int $navigationSort = 3;

    public function getTitle(): string
    {
        return 'Consultation Queue — Patients Per Room';
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'doctor', 'nurse', 'medical_administrator']);
    }

    /**
     * Get rooms + waiting patients grouped per clinic (or just the user's clinic).
     */
    public function getRoomsByClinic(): array
    {
        $user = auth()->user();
        $today = today()->toDateString();

        $roomsQuery = ConsultationRoom::with(['clinic', 'assignedDoctor'])
            ->where('is_active', true);

        if (!$user->hasRole(['super_admin', 'medical_administrator'])) {
            $clinicId = $user->default_clinic_id
                ?? ($user->currentDutyPosting?->clinic_id ?? null);
            if ($clinicId) {
                $roomsQuery->where('clinic_id', $clinicId);
            }
        }

        $rooms = $roomsQuery->orderBy('clinic_id')->orderBy('code')->get();

        // Group rooms by clinic
        $grouped = [];
        foreach ($rooms as $room) {
            $clinicName = $room->clinic?->name ?? 'Unknown Clinic';
            if (!isset($grouped[$clinicName])) {
                $grouped[$clinicName] = [
                    'clinic' => $room->clinic,
                    'rooms' => [],
                ];
            }

            $waitingPatients = $room->appointments()
                ->whereDate('appointment_date', $today)
                ->whereIn('status', ['Waiting for Doctor', 'In Consultation', 'Vitals Taken'])
                ->orderBy('room_queue_position')
                ->with(['patient', 'clinic'])
                ->get();

            $grouped[$clinicName]['rooms'][] = [
                'room' => $room,
                'patients' => $waitingPatients,
                'load' => $waitingPatients->count(),
                'is_available' => $room->is_available,
            ];
        }

        return $grouped;
    }
}
