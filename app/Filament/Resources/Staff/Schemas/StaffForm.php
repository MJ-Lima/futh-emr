<?php

namespace App\Filament\Resources\Staff\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Staff Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('user_id')->label('User Account')->required()->relationship('user', 'name')->searchable()->preload(),
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            Select::make('department_id')->label('Department')->relationship('department', 'name')->searchable()->preload(),
                            Select::make('clinic_id')->label('Clinic')->relationship('clinic', 'name')->searchable()->preload(),
                            TextInput::make('staff_number')->label('Staff Number')->required(),
                            Select::make('title')->label('Title')->options(['Dr.' => 'Dr.', 'Mr.' => 'Mr.', 'Mrs.' => 'Mrs.', 'Miss' => 'Miss', 'Ms.' => 'Ms.', 'Prof.' => 'Prof.', 'Chief' => 'Chief', 'Alhaji' => 'Alhaji', 'Hajia' => 'Hajia'])->searchable(),
                            TextInput::make('first_name')->label('First Name')->required(),
                            TextInput::make('middle_name')->label('Middle Name'),
                            TextInput::make('last_name')->label('Last Name')->required(),
                            Select::make('gender')->label('Gender')->required()->options(['Male' => 'Male', 'Female' => 'Female'])->searchable(),
                            DatePicker::make('date_of_birth')->label('Date of Birth'),
                            TextInput::make('phone')->label('Phone')->tel(),
                            Textarea::make('address')->label('Address'),
                            TextInput::make('designation')->label('Designation'),
                            Select::make('staff_type')->label('Staff Type')->required()->options(['Doctor' => 'Doctor', 'Nurse' => 'Nurse', 'Pharmacist' => 'Pharmacist', 'Laboratory Scientist' => 'Laboratory Scientist', 'Radiographer' => 'Radiographer', 'Physiotherapist' => 'Physiotherapist', 'Administrator' => 'Administrator', 'Accountant' => 'Accountant', 'ICT' => 'ICT', 'Medical Records' => 'Medical Records', 'Other' => 'Other'])->searchable(),
                            Toggle::make('status')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
