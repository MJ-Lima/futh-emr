<?php

namespace App\Filament\Pages;

use App\Models\Admission;
use App\Models\Ward;
use App\Models\WardRoundNote;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class WardRound extends Page
{
    protected string $view = 'filament.pages.ward-round';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Inpatient';

    protected static ?string $navigationLabel = 'Ward Round';

    protected static ?string $title = 'Ward Round — Inpatient Review';

    protected static ?int $navigationSort = 5;

    public function getTitle(): string
    {
        return 'Ward Round — Inpatient Review';
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'doctor', 'resident_doctor', 'nurse', 'medical_administrator']);
    }

    /**
     * Get wards + currently admitted patients grouped per ward.
     */
    public function getWardsWithPatients(): array
    {
        $wards = Ward::where('status', 'Active')
            ->with(['beds'])
            ->orderBy('name')
            ->get();

        $grouped = [];
        foreach ($wards as $ward) {
            // Currently admitted patients on this ward
            $admissions = Admission::where('ward_id', $ward->id)
                ->where('status', 'Admitted')
                ->with(['patient', 'bed', 'admittingDoctor', 'attendingDoctor', 'residentDoctor'])
                ->latest('admission_date')
                ->get();

            // Skip wards with no patients
            if ($admissions->isEmpty()) {
                continue;
            }

            $patients = $admissions->map(function ($admission) {
                $lastNote = WardRoundNote::where('admission_id', $admission->id)
                    ->latest('round_date')->latest('id')->first();
                return [
                    'admission' => $admission,
                    'patient' => $admission->patient,
                    'bed' => $admission->bed,
                    'last_note' => $lastNote,
                    'last_note_summary' => $lastNote?->assessment ? mb_substr($lastNote->assessment, 0, 80) : null,
                    'last_note_date' => $lastNote?->round_date,
                    'days_admitted' => $admission->lengthOfStayDays(),
                ];
            });

            $grouped[] = [
                'ward' => $ward,
                'patients' => $patients,
                'count' => $patients->count(),
                'capacity' => $ward->total_beds ?? $ward->beds->count(),
            ];
        }

        return $grouped;
    }
}
