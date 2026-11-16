<?php

namespace App\Filament\Resources\MedicalAudits\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class MedicalAuditForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('MedicalAudit Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('audit_category_id')->label('Audit Category')->relationship('audit_category', 'name')->searchable()->preload(),
                            Select::make('department_id')->label('Department')->relationship('department', 'name')->searchable()->preload(),
                            Select::make('clinic_id')->label('Clinic')->relationship('clinic', 'name')->searchable()->preload(),
                            Select::make('patient_id')->label('Patient')->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            Select::make('admission_id')->label('Admission')->relationship('admission', 'admission_number')->searchable()->preload(),
                            Select::make('audit_type')->label('Audit Type')->required()->options(['Case Review' => 'Case Review', 'Mortality Review' => 'Mortality Review', 'Morbidity Review' => 'Morbidity Review', 'Documentation Audit' => 'Documentation Audit', 'Prescription Audit' => 'Prescription Audit', 'Lab Utilization' => 'Lab Utilization', 'Infection Control' => 'Infection Control', 'Patient Safety' => 'Patient Safety', 'Clinical Guideline Compliance' => 'Clinical Guideline Compliance', 'Surgical Audit' => 'Surgical Audit', 'Medication Safety' => 'Medication Safety', 'Other' => 'Other'])->searchable(),
                            DatePicker::make('audit_date')->label('Audit Date')->required(),
                            DatePicker::make('period_from')->label('Period From'),
                            DatePicker::make('period_to')->label('Period To'),
                            Textarea::make('audit_scope')->label('Audit Scope'),
                            Textarea::make('objectives')->label('Objectives'),
                            Textarea::make('methodology')->label('Methodology'),
                            Textarea::make('summary')->label('Summary'),
                            Textarea::make('key_findings')->label('Key Findings'),
                            Textarea::make('recommendations')->label('Recommendations'),
                            Textarea::make('action_plan')->label('Action Plan'),
                            Select::make('risk_level')->label('Risk Level')->required()->options(['Low' => 'Low', 'Moderate' => 'Moderate', 'High' => 'High', 'Critical' => 'Critical'])->searchable(),
                            Select::make('status')->label('Status')->required()->options(['Planned' => 'Planned', 'In Progress' => 'In Progress', 'Completed' => 'Completed', 'Reviewed' => 'Reviewed', 'Closed' => 'Closed', 'Cancelled' => 'Cancelled'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
