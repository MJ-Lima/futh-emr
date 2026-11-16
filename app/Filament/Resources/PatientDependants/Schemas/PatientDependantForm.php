<?php

namespace App\Filament\Resources\PatientDependants\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class PatientDependantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('PatientDependant Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('principal_patient_id')->label('Principal Patient')->required()->relationship('principal_patient', 'full_name')->searchable()->preload(),
                            Select::make('patient_id')->label('Patient Record (if registered)')->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('patient_insurance_id')->label('Insurance Policy')->relationship('patient_insurance', 'policy_number')->searchable()->preload(),
                            TextInput::make('first_name')->label('First Name')->required(),
                            TextInput::make('middle_name')->label('Middle Name'),
                            TextInput::make('last_name')->label('Last Name')->required(),
                            DatePicker::make('date_of_birth')->label('Date of Birth')->required(),
                            Select::make('gender')->label('Gender')->required()->options(['Male' => 'Male', 'Female' => 'Female'])->searchable(),
                            Select::make('relationship')->label('Relationship')->required()->options(['Spouse' => 'Spouse', 'Child' => 'Child', 'Parent' => 'Parent', 'Sibling' => 'Sibling', 'Dependant' => 'Dependant', 'Other' => 'Other'])->searchable(),
                            TextInput::make('national_id')->label('National ID'),
                            Select::make('national_id_type')->label('ID Type'),
                            TextInput::make('photo_path')->label('Photo Path'),
                            TextInput::make('thumbprint_path')->label('Thumbprint Path'),
                            Select::make('verification_status')->label('Verification Status')->required()->options(['Pending' => 'Pending', 'Verified' => 'Verified', 'Rejected' => 'Rejected', 'Expired' => 'Expired'])->searchable(),
                            TextInput::make('phone')->label('Phone')->tel(),
                            TextInput::make('email')->label('Email')->email(),
                            Textarea::make('address')->label('Address'),
                            Textarea::make('notes')->label('Notes'),
                            Select::make('status')->label('Status')->required()->options(['Active' => 'Active', 'Inactive' => 'Inactive', 'Suspended' => 'Suspended'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
