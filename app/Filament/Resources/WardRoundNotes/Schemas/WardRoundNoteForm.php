<?php

namespace App\Filament\Resources\WardRoundNotes\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use App\Models\Admission;
use App\Models\Patient;
use App\Models\WardRoundNote;
class WardRoundNoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Patient & Round Info')
                ->icon('heroicon-m-link')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('patient_id')->label('Patient')
                            ->relationship('patient', 'hospital_no')
                            ->getOptionLabelFromRecordUsing(fn ($r) => "{$r->lastname} {$r->firstname} — {$r->hospital_no}")
                            ->searchable(['firstname', 'lastname', 'hospital_no', 'phonemain'])
                            ->preload()->native(false)->required()->live()
                            ->afterStateUpdated(function ($state, $set) {
                                // Auto-suggest admission, ward, bed from the active admission
                                $admission = Admission::where('patient_id', $state)
                                    ->where('status', 'Admitted')->latest()->first();
                                if ($admission) {
                                    $set('admission_id', $admission->id);
                                    $set('ward_id', $admission->ward_id);
                                    $set('bed_id', $admission->bed_id);
                                    $set('encounter_id', $admission->encounter_id);
                                }
                            }),
                        Select::make('admission_id')->label('Admission')
                            ->relationship('admission', 'admission_number')
                            ->searchable()->preload()->native(false)
                            ->helperText('Auto-filled from the active admission; required for inpatient rounds.'),
                        Select::make('ward_id')->label('Ward')
                            ->relationship('ward', 'name')->searchable()->preload()->native(false),
                    ]),
                    Grid::make(3)->schema([
                        Select::make('encounter_id')->label('Encounter')
                            ->relationship('encounter', 'encounter_number')
                            ->searchable()->preload()->native(false),
                        Select::make('doctor_id')->label('Doctor on Round')
                            ->relationship('doctor', 'name')
                            ->searchable()->preload()->native(false)->required()
                            ->default(auth()->id()),
                        Select::make('round_type')->label('Round Type')
                            ->options(WardRoundNote::ROUND_TYPES)->default('Morning')->native(false)->required(),
                    ]),
                    Grid::make(2)->schema([
                        DatePicker::make('round_date')->label('Round Date')->required()->default(now()),
                        TimePicker::make('round_time')->label('Round Time')->required()->default(now()),
                    ]),
                ]),

            Section::make('Vital Signs (quick capture)')
                ->icon('heroicon-m-heart')
                ->description('Optional — captured here so the doctor doesn\'t need to open the separate Vitals resource.')
                ->schema([
                    Grid::make(4)->schema([
                        TextInput::make('temperature')->label('Temp (°C)')->numeric()->step('0.1')->placeholder('36.5'),
                        TextInput::make('systolic_bp')->label('Systolic BP')->numeric()->placeholder('120'),
                        TextInput::make('diastolic_bp')->label('Diastolic BP')->numeric()->placeholder('80'),
                        TextInput::make('heart_rate')->label('Pulse')->numeric()->placeholder('80'),
                        TextInput::make('respiratory_rate')->label('Resp Rate')->numeric()->placeholder('18'),
                        TextInput::make('spo2')->label('SpO₂ (%)')->numeric()->placeholder('98'),
                        TextInput::make('blood_glucose')->label('Blood Glucose (mmol/L)')->numeric()->step('0.1')->placeholder('5.5'),
                        TextInput::make('weight_kg')->label('Weight (kg)')->numeric()->step('0.1')->placeholder('70'),
                    ]),
                ]),

            Section::make('SOAP Note')
                ->icon('heroicon-m-clipboard-document')
                ->schema([
                    Textarea::make('subjective')->label('S — Subjective (Patient-reported)')->rows(3)
                        ->placeholder('Patient complaints / symptoms since last review. e.g. "Patient reports decreased pain, sleeping well, appetite improving."'),
                    Textarea::make('objective')->label('O — Objective (Examination & Investigations)')->rows(4)
                        ->placeholder('Physical exam findings, vitals trends, lab/imaging results, drain/output volumes, etc.'),
                    Textarea::make('assessment')->label('A — Assessment (Diagnosis / Progress)')->rows(3)
                        ->placeholder('Working diagnosis, severity, progress (improving / stable / deteriorating), complications.'),
                    Textarea::make('plan')->label('P — Plan (Investigations, Medications, Procedures)')->rows(4)
                        ->placeholder('New investigations, medication changes, procedures planned, monitoring, physio, nutrition, consults.'),
                ]),

            Section::make('Plan & Instructions')
                ->icon('heroicon-m-check-circle')
                ->schema([
                    Textarea::make('investigations_ordered')->label('Investigations Ordered')->rows(3)
                        ->placeholder('e.g. FBC, U&E, blood culture, CXR, ECG...'),
                    Textarea::make('medications_changes')->label('Medications Changes (new / stopped / dose changed)')->rows(3)
                        ->placeholder('e.g. Started IV Ceftriaxone 2g BD. Stopped Metformin. Increased furosemide to 40mg.'),
                    Textarea::make('plan_for_discharge')->label('Plan for Discharge / Discharge Criteria')->rows(3)
                        ->placeholder('Discharge criteria met? Expected discharge date? Follow-up plan?'),
                    Textarea::make('nurse_action_required')->label('Nurse Action Required (next shift)')->rows(3)
                        ->placeholder('Specific instructions for the next nurse on shift. e.g. "Check wound dressing every 4 hrs, call doctor if temp >38.5°C."'),
                    Textarea::make('doctor_notes')->label('Doctor\'s Additional Notes')->rows(2),
                ]),
        ]);
    }
}
