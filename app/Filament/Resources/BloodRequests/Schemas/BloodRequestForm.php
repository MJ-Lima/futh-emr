<?php

namespace App\Filament\Resources\BloodRequests\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class BloodRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BloodRequest Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            Select::make('admission_id')->label('Admission')->relationship('admission', 'admission_number')->searchable()->preload(),
                            Select::make('requested_by')->label('Requested By')->required()->relationship('requested_by', 'name')->searchable()->preload(),
                            Select::make('requested_blood_group')->label('Requested Blood Group')->required()->options(['A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'AB+' => 'AB+', 'AB-' => 'AB-', 'O+' => 'O+', 'O-' => 'O-', 'Unknown' => 'Unknown'])->searchable(),
                            Select::make('component_requested')->label('Component Requested')->required()->options(['Whole Blood' => 'Whole Blood', 'Packed Red Cells' => 'Packed Red Cells', 'Plasma' => 'Plasma', 'Platelets' => 'Platelets', 'Cryoprecipitate' => 'Cryoprecipitate', 'Fresh Frozen Plasma' => 'Fresh Frozen Plasma'])->searchable(),
                            TextInput::make('units_requested')->label('Units Requested')->numeric(),
                            Select::make('urgency')->label('Urgency')->required()->options(['Routine' => 'Routine', 'Urgent' => 'Urgent', 'Emergency' => 'Emergency', 'STAT' => 'STAT'])->searchable(),
                            DateTimePicker::make('required_by')->label('Required By')->required(),
                            Textarea::make('clinical_indication')->label('Clinical Indication'),
                            Textarea::make('diagnosis')->label('Diagnosis'),
                            TextInput::make('patient_hb_level')->label('Patient Hb Level')->numeric(),
                            Select::make('patient_blood_group')->label('Patient Blood Group')->required()->options(['A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'AB+' => 'AB+', 'AB-' => 'AB-', 'O+' => 'O+', 'O-' => 'O-', 'Unknown' => 'Unknown'])->searchable(),
                            Select::make('patient_rh_factor')->label('Patient Rh Factor')->required()->options(['Positive' => 'Positive', 'Negative' => 'Negative', 'Unknown' => 'Unknown'])->searchable(),
                            Textarea::make('crossmatch_notes')->label('Crossmatch Notes'),
                            Select::make('status')->label('Status')->required()->options(['Pending' => 'Pending', 'Approved' => 'Approved', 'Crossmatched' => 'Crossmatched', 'Issued' => 'Issued', 'Transfused' => 'Transfused', 'Cancelled' => 'Cancelled', 'Rejected' => 'Rejected'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
