<?php

namespace App\Filament\Resources\ConsultationNotes\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use App\Models\Clinic;
use App\Models\ClinicTemplate;
use App\Models\PresentingComplaint;
use App\Models\Icd10Diagnosis;
class ConsultationNoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Encounter / patient / clinic linkage
            Section::make('Encounter Linkage')
                ->icon('heroicon-m-link')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('encounter_id')->label('Encounter')
                            ->relationship('encounter', 'encounter_number')
                            ->searchable()->preload()->native(false)->required(),
                        Select::make('patient_id')->label('Patient')
                            ->relationship('patient', 'hospital_no')
                            ->getOptionLabelFromRecordUsing(fn ($r) => "{$r->lastname} {$r->firstname} — {$r->hospital_no}")
                            ->searchable()->preload()->native(false)->required(),
                        Select::make('clinic_id')->label('Clinic')
                            ->relationship('clinic', 'name')
                            ->searchable()->preload()->native(false)->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $set('clinic_template_id', null);
                            }),
                    ]),
                ]),

            // 1. Presenting Complaints — selected from the database list
            Section::make('Presenting Complaints')
                ->icon('heroicon-m-rectangle-list')
                ->description('Select from the database list of complaints. Duration and HPI description can be added per complaint.')
                ->schema([
                    Select::make('presentingComplaints')
                        ->label('Presenting Complaints')
                        ->relationship('presentingComplaints', 'name')
                        ->multiple()
                        ->searchable()->preload()
                        ->native(false)
                        ->optionsLimit(200)
                        ->helperText('Select one or more complaints from the database. To add new ones, visit Administration → Presenting Complaints.'),
                ]),

            // 2. New Diagnosis — selected from ICD-10 database list
            // PERFORMANCE: Do NOT eager-load all 74k ICD-10 codes into the options array.
            // Use ->searchOptions() with a getSearchResultsUsing() callback that queries
            // the DB on-demand with a LIKE filter, returning only matching rows (max 50).
            // This keeps the page fast even with 74,000+ ICD-10 codes in the database.
            Section::make('New Diagnosis (ICD-10)')
                ->icon('heroicon-m-book-open')
                ->description('ICD-10 diagnoses are stored in the database. To add new ICD-10 codes, visit Administration → ICD-10 Diagnoses.')
                ->schema([
                    Select::make('new_diagnosis_icd10')
                        ->label('Search ICD-10 diagnosis')
                        ->getSearchResultsUsing(function (string $search): array {
                            // Only query the DB when the user types a search term.
                            // Limit to 50 results to keep the dropdown fast.
                            if (strlen($search) < 2) return [];

                            return Icd10Diagnosis::where('is_active', true)
                                ->where(function ($q) use ($search) {
                                    $q->where('code', 'like', "%{$search}%")
                                      ->orWhere('description', 'like', "%{$search}%");
                                })
                                ->orderBy('code')
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn ($d) => [$d->code => "{$d->code} — {$d->description}"])
                                ->toArray();
                        })
                        ->getOptionLabelsUsing(function (array $values): array {
                            // When the form is loaded with existing values, fetch just those labels
                            if (empty($values)) return [];

                            return Icd10Diagnosis::whereIn('code', $values)
                                ->get()
                                ->mapWithKeys(fn ($d) => [$d->code => "{$d->code} — {$d->description}"])
                                ->toArray();
                        })
                        ->searchable()
                        ->native(false)
                        ->multiple()
                        ->helperText('Type at least 2 characters to search ICD-10 codes. Select one or more for the new diagnosis. These will be saved as the principal diagnosis on the encounter.'),
                ]),

            // 3. Physical Examination — using the clinic's specific template
            Section::make('Physical Examination')
                ->icon('heroicon-m-hand-raised')
                ->description('General and clinic-specific physical examination findings.')
                ->schema([
                    Textarea::make('physical_examination_general')->label('General Examination')
                        ->rows(3)->placeholder('General appearance, build, nutrition, hydration, vitals signs, pallor, jaundice, cyanosis, clubbing, lymph nodes...'),
                    Textarea::make('physical_examination_systemic')->label('Systemic Examination')
                        ->rows(3)->placeholder('Respiratory / CVS / Abdomen / CNS / MSS — relevant systemic findings'),
                    Textarea::make('physical_examination_specific')->label('Clinic-Specific Examination')
                        ->rows(3)->placeholder('e.g. Pelvic exam (ANC/O&G), Dental chart (Dental), ENT exam (ENT), Fundoscopy (Eye), Mental State Examination (Psychiatry)'),
                ]),

            // 4. General History
            Section::make('General History')
                ->icon('heroicon-m-clock')
                ->schema([
                    Grid::make(2)->schema([
                        Textarea::make('history_presenting_illness')->label('History of Presenting Illness (HPI)')->rows(3),
                        Textarea::make('history_past_medical')->label('Past Medical History')->rows(3),
                        Textarea::make('history_past_surgical')->label('Past Surgical History')->rows(3),
                        Textarea::make('history_drug_allergies')->label('Drug Allergies')->rows(3),
                        Textarea::make('history_family')->label('Family History')->rows(3),
                        Textarea::make('history_social')->label('Social History')->rows(3)
                            ->placeholder('Smoking, alcohol, occupation, housing, etc.'),
                        Textarea::make('history_obstetric')->label('Obstetric History (ANC / O&G only)')->rows(3)
                            ->placeholder('Gravida, Para, last child, complications, etc.'),
                        Textarea::make('history_developmental')->label('Developmental History (Paediatric only)')->rows(3)
                            ->placeholder('Milestones, school performance, etc.'),
                    ]),
                ]),

            // 5. Clinic-Specific Template Fields (rendered dynamically)
            Section::make('Clinic-Specific Template')
                ->icon('heroicon-m-document-text')
                ->description('Fields pulled from the clinic\'s specific template (e.g. ANC, SOPD, Dental, ENT, Paediatric, Eye, Psychiatry, Psychology).')
                ->schema([
                    Select::make('clinic_template_id')->label('Template')
                        ->options(function ($get) {
                            $clinicId = $get('clinic_id');
                            if (!$clinicId) return [];
                            $clinic = Clinic::find($clinicId);
                            if (!$clinic) return [];
                            return ClinicTemplate::where('is_active', true)
                                ->where('section_type', 'specific_template')
                                ->where(function ($q) use ($clinic) {
                                    $q->where('clinic_id', $clinic->id)
                                      ->orWhere('clinic_type', $clinic->clinic_type);
                                })
                                ->orderBy('sort_order')
                                ->pluck('name', 'id');
                        })
                        ->native(false)
                        ->searchable()
                        ->helperText('Choose a clinic-specific template to fill in. Fields shown below come from the selected template.'),
                    // The clinic_specific_data JSON is captured as a key-value textarea for simplicity
                    Textarea::make('clinic_specific_data_input')
                        ->label('Clinic-Specific Findings (key: value per line)')
                        ->rows(8)
                        ->placeholder("BP: 120/80\nPulse: 80 bpm\nFundal height: 36 cm\nFetal heart rate: 140 bpm\nLie: Longitudinal\nPresentation: Cephalic\nEngagement: 3/5")
                        ->dehydrated(false)
                        ->afterStateHydrated(function ($component, $state, $record) {
                            if ($record && $record->clinic_specific_data) {
                                $lines = [];
                                foreach ($record->clinic_specific_data as $k => $v) {
                                    $lines[] = "$k: $v";
                                }
                                $component->state(implode("\n", $lines));
                            }
                        }),
                ]),

            // 6. Doctor's Clinic Note (Findings)
            Section::make("Doctor's Clinic Note")
                ->icon('heroicon-m-pencil-square')
                ->description('Doctor writes his/her findings here.')
                ->schema([
                    Textarea::make('examination_findings')->label('Examination Findings')->rows(3),
                    Textarea::make('assessment')->label('Assessment')->rows(3)
                        ->placeholder('Working diagnosis, severity, risk stratification...'),
                    Textarea::make('plan')->label('Plan')->rows(4)
                        ->placeholder('Investigations, prescriptions, procedures, follow-up date...'),
                    Textarea::make('doctor_notes')->label('Additional Doctor\'s Notes')->rows(3)
                        ->placeholder('Any other notes the doctor wants to record.'),
                ]),

            // 7. Status (Draft, Finalized, Counter-signed, Amended)
            Section::make('Note Status')
                ->icon('heroicon-m-check-badge')
                ->schema([
                    Grid::make(2)->schema([
                        ToggleButtons::make('status')
                            ->label('Status')
                            ->options(ConsultationNote::STATUSES)
                            ->colors([
                                'Draft' => 'warning',
                                'Finalized' => 'info',
                                'Counter-signed' => 'success',
                                'Amended' => 'gray',
                            ])
                            ->inline()
                            ->default('Draft'),
                        Select::make('countersigned_by')->label('Counter-signed by (Senior Doctor)')
                            ->relationship('countersigner', 'name')
                            ->native(false)->searchable()->preload()
                            ->helperText('Fill in when counter-signing the note.'),
                    ]),
                ]),
        ]);
    }
}
