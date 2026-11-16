<?php

namespace App\Filament\Resources\BloodDonors\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class BloodDonorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BloodDonor Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('donor_type')->label('Donor Type')->required()->options(['Voluntary' => 'Voluntary', 'Replacement' => 'Replacement', 'Autologous' => 'Autologous', 'Directed' => 'Directed', 'Paid' => 'Paid'])->searchable(),
                            TextInput::make('first_name')->label('First Name')->required(),
                            TextInput::make('middle_name')->label('Middle Name'),
                            TextInput::make('last_name')->label('Last Name')->required(),
                            Select::make('gender')->label('Gender')->required()->options(['Male' => 'Male', 'Female' => 'Female'])->searchable(),
                            DatePicker::make('date_of_birth')->label('Date of Birth')->required(),
                            TextInput::make('phone')->label('Phone')->tel(),
                            TextInput::make('email')->label('Email')->email(),
                            Textarea::make('address')->label('Address'),
                            TextInput::make('occupation')->label('Occupation'),
                            Select::make('blood_group')->label('Blood Group')->required()->options(['A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'AB+' => 'AB+', 'AB-' => 'AB-', 'O+' => 'O+', 'O-' => 'O-'])->searchable(),
                            Select::make('rh_factor')->label('Rh Factor')->required()->options(['Positive' => 'Positive', 'Negative' => 'Negative'])->searchable(),
                            DatePicker::make('last_donation_date')->label('Last Donation Date'),
                            TextInput::make('total_donations')->label('Total Donations')->numeric(),
                            TextInput::make('total_volume_donated_ml')->label('Total Volume (ml)')->numeric(),
                            Toggle::make('is_eligible')->label('Eligible'),
                            Textarea::make('ineligibility_reason')->label('Ineligibility Reason'),
                            Textarea::make('medical_history')->label('Medical History'),
                            Textarea::make('notes')->label('Notes'),
                            Select::make('status')->label('Status')->required()->options(['Active' => 'Active', 'Inactive' => 'Inactive', 'Deferred' => 'Deferred', 'Blacklisted' => 'Blacklisted'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
