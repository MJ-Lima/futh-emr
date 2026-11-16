<?php

namespace App\Filament\Pages;

use App\Models\Admission;
use Filament\Pages\Page;

class MortalityReviewReport extends Page
{
    protected string $view = 'filament.pages.mortality-review-report';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Clinical';

    protected static ?string $navigationLabel = 'Mortality Review Report';

    protected static ?string $title = 'Mortality Review Report';

    protected static ?int $navigationSort = 16;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'doctor', 'medical_administrator', 'medical_records_officer']);
    }

    public function getTitle(): string
    {
        return 'Mortality Review Report';
    }

    public function getMortalityData(): array
    {
        $deaths = Admission::where('status', 'Death')
            ->with(['patient', 'ward', 'admittingDoctor', 'attendingDoctor', 'residentDoctor', 'encounter.diagnoses'])
            ->latest('discharge_date')
            ->get();

        $totalDeaths = $deaths->count();

        // By month (current year)
        $byMonth = [];
        foreach ($deaths as $d) {
            if ($d->discharge_date) {
                $month = $d->discharge_date->format('M Y');
                $byMonth[$month] = ($byMonth[$month] ?? 0) + 1;
            }
        }

        // By ward
        $byWard = [];
        foreach ($deaths as $d) {
            $wardName = $d->ward?->name ?? 'Unassigned';
            $byWard[$wardName] = ($byWard[$wardName] ?? 0) + 1;
        }

        // By age group
        $byAgeGroup = ['0-14' => 0, '15-44' => 0, '45-64' => 0, '65+' => 0, 'Unknown' => 0];
        foreach ($deaths as $d) {
            $age = $d->patient?->dob ? $d->patient->dob->age : null;
            if ($age === null) $byAgeGroup['Unknown']++;
            elseif ($age <= 14) $byAgeGroup['0-14']++;
            elseif ($age <= 44) $byAgeGroup['15-44']++;
            elseif ($age <= 64) $byAgeGroup['45-64']++;
            else $byAgeGroup['65+']++;
        }

        // By gender
        $byGender = ['Male' => 0, 'Female' => 0, 'Unknown' => 0];
        foreach ($deaths as $d) {
            $g = $d->patient?->gender ?? 'Unknown';
            $byGender[$g] = ($byGender[$g] ?? 0) + 1;
        }

        // Admission type
        $byAdmissionType = [];
        foreach ($deaths as $d) {
            $type = $d->admission_type ?? 'Unknown';
            $byAdmissionType[$type] = ($byAdmissionType[$type] ?? 0) + 1;
        }

        // Average LOS before death
        $losValues = [];
        foreach ($deaths as $d) {
            if ($d->admission_date && $d->discharge_date) {
                $losValues[] = $d->admission_date->diffInDays($d->discharge_date);
            }
        }
        $avgLOS = count($losValues) > 0 ? round(array_sum($losValues) / count($losValues), 1) : 0;

        // Detailed records
        $records = $deaths->map(fn ($d) => [
            'admission_number' => $d->admission_number,
            'patient' => $d->patient?->full_name ?? '—',
            'code' => $d->patient?->hospital_no ?? '—',
            'age' => $d->patient?->age ?? '—',
            'gender' => $d->patient?->gender ?? '—',
            'ward' => $d->ward?->name ?? '—',
            'admission_date' => $d->admission_date?->format('d M Y'),
            'death_date' => $d->discharge_date?->format('d M Y'),
            'los' => $d->lengthOfStayDays(),
            'admission_diagnosis' => mb_substr($d->admission_diagnosis ?? '—', 0, 60),
            'discharge_diagnosis' => mb_substr($d->discharge_diagnosis ?? '—', 0, 60),
            'discharge_reason' => mb_substr($d->discharge_reason ?? '—', 0, 60),
            'attending_doctor' => $d->attendingDoctor?->name ?? '—',
            'admission_type' => $d->admission_type,
        ])->toArray();

        return [
            'totalDeaths' => $totalDeaths,
            'byMonth' => $byMonth,
            'byWard' => $byWard,
            'byAgeGroup' => $byAgeGroup,
            'byGender' => $byGender,
            'byAdmissionType' => $byAdmissionType,
            'avgLOS' => $avgLOS,
            'records' => $records,
        ];
    }
}
