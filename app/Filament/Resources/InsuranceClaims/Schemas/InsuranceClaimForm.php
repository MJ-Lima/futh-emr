<?php

namespace App\Filament\Resources\InsuranceClaims\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class InsuranceClaimForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('InsuranceClaim Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('patient_insurance_id')->label('Policy')->required()->relationship('policy', 'policy_number')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            Select::make('claim_type')->label('Claim Type')->required()->options(['Outpatient' => 'Outpatient', 'Inpatient' => 'Inpatient', 'Emergency' => 'Emergency', 'Pharmacy' => 'Pharmacy', 'Laboratory' => 'Laboratory', 'Radiology' => 'Radiology', 'Procedure' => 'Procedure', 'Maternity' => 'Maternity'])->searchable(),
                            DatePicker::make('service_date_start')->label('Service Date Start')->required(),
                            DatePicker::make('service_date_end')->label('Service Date End')->required(),
                            TextInput::make('diagnosis_icd10')->label('Diagnosis ICD-10'),
                            TextInput::make('diagnosis_description')->label('Diagnosis Description'),
                            Textarea::make('treatment_summary')->label('Treatment Summary'),
                            TextInput::make('total_claimed')->label('Total Claimed')->numeric(),
                            TextInput::make('patient_copay')->label('Patient Copay')->numeric(),
                            TextInput::make('pre_authorization_code')->label('Pre-Auth Code'),
                            Select::make('status')->label('Status')->required()->options(['Draft' => 'Draft', 'Submitted' => 'Submitted', 'Acknowledged' => 'Acknowledged', 'Processing' => 'Processing', 'Approved' => 'Approved', 'Partially Approved' => 'Partially Approved', 'Rejected' => 'Rejected', 'Paid' => 'Paid', 'Reversed' => 'Reversed'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
