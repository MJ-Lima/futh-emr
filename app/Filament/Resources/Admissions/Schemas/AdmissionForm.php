<?php

namespace App\Filament\Resources\Admissions\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class AdmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Admission Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            Select::make('ward_id')->label('Ward')->relationship('ward', 'name')->searchable()->preload(),
                            Select::make('bed_id')->label('Bed')->relationship('bed', 'bed_number')->searchable()->preload(),
                            Select::make('admitting_doctor_id')->label('Admitting Doctor')->relationship('admitting_doctor', 'name')->searchable()->preload(),
                            Select::make('attending_doctor_id')->label('Attending Doctor')->relationship('attending_doctor', 'name')->searchable()->preload(),
                            DateTimePicker::make('admission_date')->label('Admission Date')->required(),
                            Select::make('admission_type')->label('Admission Type')->required()->options(['Emergency' => 'Emergency', 'Routine' => 'Routine', 'Elective' => 'Elective', 'Transfer In' => 'Transfer In', 'Maternity' => 'Maternity', 'Day Case' => 'Day Case'])->searchable(),
                            Select::make('admission_source')->label('Admission Source')->required()->options(['Emergency Room' => 'Emergency Room', 'Outpatient Clinic' => 'Outpatient Clinic', 'Referral' => 'Referral', 'Transfer' => 'Transfer', 'Direct Admission' => 'Direct Admission', 'Theatre' => 'Theatre'])->searchable(),
                            Textarea::make('admission_diagnosis')->label('Admission Diagnosis'),
                            Textarea::make('admission_reason')->label('Admission Reason'),
                            Textarea::make('clinical_notes')->label('Clinical Notes'),
                            Select::make('status')->label('Status')->required()->options(['Admitted' => 'Admitted', 'Discharged' => 'Discharged', 'Transferred' => 'Transferred', 'Absconded' => 'Absconded', 'Death' => 'Death', 'Cancelled' => 'Cancelled'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
