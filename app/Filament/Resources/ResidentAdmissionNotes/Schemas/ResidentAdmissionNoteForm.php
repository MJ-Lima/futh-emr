<?php

namespace App\Filament\Resources\ResidentAdmissionNotes\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ResidentAdmissionNoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Patient & Admission Linkage')
                ->icon('heroicon-m-link')
                ->description('Link this note to a patient, admission (if inpatient), and encounter.')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('patient_id')->label('Patient')
                            ->relationship('patient', 'hospital_no')
                            ->getOptionLabelFromRecordUsing(fn ($r) => "{$r->lastname} {$r->firstname} — {$r->hospital_no}")
                            ->searchable()->preload()->native(false)->required(),
                        Select::make('admission_id')->label('Admission (inpatient)')
                            ->relationship('admission', 'admission_number')
                            ->searchable()->preload()->native(false)
                            ->helperText('Optional — required for inpatient admission notes.'),
                        Select::make('encounter_id')->label('Encounter')
                            ->relationship('encounter', 'encounter_number')
                            ->searchable()->preload()->native(false),
                    ]),
                ]),

            Section::make('Resident Doctor & Supervisor')
                ->icon('heroicon-m-user-group')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('resident_doctor_id')->label('Resident Doctor')
                            ->relationship('residentDoctor', 'name')
                            ->searchable()->preload()->native(false)->required()
                            ->default(auth()->id()),
                        Select::make('supervising_consultant_id')->label('Supervising Consultant')
                            ->relationship('supervisingConsultant', 'name')
                            ->searchable()->preload()->native(false)
                            ->helperText('The consultant who will counter-sign this note.'),
                    ]),
                ]),

            Section::make('Presenting Complaint & HPI')
                ->icon('heroicon-m-rectangle-list')
                ->schema([
                    Textarea::make('presenting_complaint')->label('Presenting Complaint')->rows(2)
                        ->placeholder('The main reason for admission (in patient\'s / relative\'s words).'),
                    Textarea::make('history_presenting_illness')->label('History of Presenting Illness (HPI)')->rows(4)
                        ->placeholder('Detailed narrative of the present illness — onset, character, duration, aggravating/relieving factors.'),
                ]),

            Section::make('History')
                ->icon('heroicon-m-clock')
                ->schema([
                    Grid::make(2)->schema([
                        Textarea::make('past_medical_history')->label('Past Medical History')->rows(3),
                        Textarea::make('drug_history')->label('Drug History (incl. allergies)')->rows(3),
                        Textarea::make('family_history')->label('Family History')->rows(3),
                        Textarea::make('social_history')->label('Social History')->rows(3)
                            ->placeholder('Occupation, smoking, alcohol, housing, etc.'),
                    ]),
                ]),

            Section::make('Examination')
                ->icon('heroicon-m-hand-raised')
                ->schema([
                    Textarea::make('physical_examination')->label('Physical Examination')->rows(4)
                        ->placeholder('General, vital signs, systemic examination findings on admission.'),
                    Textarea::make('systemic_review')->label('Systemic Review')->rows(4)
                        ->placeholder('Respiratory / CVS / Abdomen / CNS / MSS — relevant positive and negative findings.'),
                ]),

            Section::make('Diagnosis & Plan')
                ->icon('heroicon-m-book-open')
                ->schema([
                    Grid::make(2)->schema([
                        Textarea::make('provisional_diagnosis')->label('Provisional Diagnosis')->rows(3)
                            ->placeholder('Working diagnosis with ICD-10 code if available.'),
                        Textarea::make('differential_diagnosis')->label('Differential Diagnosis')->rows(3)
                            ->placeholder('Other possible diagnoses to consider.'),
                    ]),
                    Textarea::make('investigations_ordered')->label('Investigations Ordered')->rows(3)
                        ->placeholder('Lab, imaging, ECG, etc.'),
                    Textarea::make('management_plan')->label('Management Plan')->rows(4)
                        ->placeholder('Treatment plan, medications, procedures, monitoring.'),
                    Textarea::make('resident_notes')->label('Resident\'s Notes')->rows(3)
                        ->placeholder('Any additional notes from the resident doctor.'),
                ]),

            Section::make('Consultant Feedback (counter-signing)')
                ->icon('heroicon-m-chat-bubble-left-right')
                ->description('Filled by the supervising consultant when counter-signing.')
                ->schema([
                    Textarea::make('consultant_feedback')->label('Consultant Feedback')->rows(3)
                        ->placeholder('Feedback / corrections from the supervising consultant.'),
                    Grid::make(2)->schema([
                        ToggleButtons::make('status')
                            ->label('Status')
                            ->options([
                                'Draft' => 'Draft',
                                'Submitted' => 'Submitted to Consultant',
                                'Counter-signed' => 'Counter-signed',
                                'Amended' => 'Amended',
                            ])
                            ->colors([
                                'Draft' => 'warning',
                                'Submitted' => 'info',
                                'Counter-signed' => 'success',
                                'Amended' => 'gray',
                            ])
                            ->inline()
                            ->default('Draft'),
                    ]),
                ]),
        ]);
    }
}
