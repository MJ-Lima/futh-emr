<?php

namespace App\Filament\Resources\MedicalCertificates\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use App\Models\MedicalCertificate;
class MedicalCertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Certificate Details')
                ->icon('heroicon-m-document-check')
                ->description('Basic certificate information')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('patient_id')
                            ->label('Patient')
                            ->required()
                            ->relationship('patient', 'hospital_no')
                            ->getOptionLabelFromRecordUsing(fn ($r) => "{$r->lastname} {$r->firstname} — {$r->hospital_no}")
                            ->searchable(['firstname', 'lastname', 'hospital_no', 'phonemain'])
                            ->preload()
                            ->native(false)
                            ->columnSpan(2),
                        Select::make('patient_category')
                            ->label('Patient Category')
                            ->required()
                            ->options(MedicalCertificate::CATEGORIES)
                            ->default('Outpatient')
                            ->native(false)
                            ->helperText('Outpatient / Inpatient / Emergency'),
                    ]),
                    Grid::make(3)->schema([
                        Select::make('encounter_id')
                            ->label('Encounter (Outpatient visit)')
                            ->relationship('encounter', 'id')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->helperText('Leave blank for inpatient certificates'),
                        Select::make('admission_id')
                            ->label('Admission (Inpatient)')
                            ->relationship('admission', 'id')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->helperText('Required for inpatient certificates'),
                        Select::make('certificate_type')
                            ->label('Certificate Type')
                            ->required()
                            ->options(MedicalCertificate::TYPES)
                            ->default('Medical Report')
                            ->native(false)
                            ->live(),
                    ]),
                ]),

            Section::make('Validity & Rest Days')
                ->icon('heroicon-m-calendar-days')
                ->schema([
                    Grid::make(3)->schema([
                        DatePicker::make('issued_date')->label('Issued Date')->required()->default(now()),
                        DatePicker::make('valid_from')->label('Valid From'),
                        DatePicker::make('valid_until')->label('Valid Until'),
                        TextInput::make('rest_days')->label('Rest Days (Sick Leave)')
                            ->numeric()->minValue(0)
                            ->helperText('Number of days off work — only used for Sick Leave certificates'),
                        TextInput::make('issued_at_location')->label('Issued At (Location)')
                            ->placeholder('e.g. FUTH Lafia, Outpatient Department'),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options(MedicalCertificate::STATUSES)
                            ->default('Draft')
                            ->native(false),
                    ]),
                ]),

            Section::make('Clinical Content')
                ->icon('heroicon-m-clipboard-document')
                ->schema([
                    Textarea::make('diagnosis')->label('Diagnosis')->rows(2)
                        ->placeholder('Primary diagnosis (ICD-10 if available)'),
                    Textarea::make('clinical_findings')->label('Clinical Findings')->rows(3)
                        ->placeholder('Symptoms, examination findings, vital signs'),
                    Textarea::make('treatment_given')->label('Treatment Given')->rows(3)
                        ->placeholder('Medications, procedures, interventions'),
                    Textarea::make('recommendations')->label('Recommendations')->rows(3)
                        ->placeholder('Follow-up advice, lifestyle modifications'),
                    Textarea::make('restrictions')->label('Restrictions / Activity Limitations')->rows(2)
                        ->placeholder('e.g. No heavy lifting for 2 weeks, bed rest, etc.'),
                    Textarea::make('notes')->label('Additional Notes')->rows(2),
                ]),
        ]);
    }
}
