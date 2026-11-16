<?php

namespace App\Filament\Resources\PreAuthorizations\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class PreAuthorizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('PreAuthorization Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('patient_insurance_id')->label('Policy')->required()->relationship('policy', 'policy_number')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            Select::make('service_type')->label('Service Type')->required()->options(['Consultation' => 'Consultation', 'Pharmacy' => 'Pharmacy', 'Laboratory' => 'Laboratory', 'Radiology' => 'Radiology', 'Procedure' => 'Procedure', 'Surgery' => 'Surgery', 'Inpatient Admission' => 'Inpatient Admission', 'Maternity' => 'Maternity', 'Specialist Referral' => 'Specialist Referral', 'Other' => 'Other'])->searchable(),
                            Textarea::make('clinical_justification')->label('Clinical Justification')->required(),
                            TextInput::make('diagnosis_icd10')->label('Diagnosis ICD-10'),
                            Textarea::make('diagnosis_description')->label('Diagnosis Description'),
                            TextInput::make('estimated_cost')->label('Estimated Cost')->numeric(),
                            DatePicker::make('required_by_date')->label('Required By'),
                            Select::make('priority')->label('Priority')->required()->options(['Routine' => 'Routine', 'Urgent' => 'Urgent', 'Emergency' => 'Emergency'])->searchable(),
                            Select::make('status')->label('Status')->required()->options(['Pending' => 'Pending', 'Approved' => 'Approved', 'Partially Approved' => 'Partially Approved', 'Rejected' => 'Rejected', 'Expired' => 'Expired', 'Utilized' => 'Utilized', 'Cancelled' => 'Cancelled'])->searchable(),
                            Textarea::make('provider_response')->label('Provider Response'),
                            Textarea::make('rejection_reason')->label('Rejection Reason'),
                            DatePicker::make('valid_until')->label('Valid Until'),
                        ]),
                    ]),
            ]);
    }
}
