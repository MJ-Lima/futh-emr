<?php

namespace App\Filament\Resources\DutyPostings\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class DutyPostingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('DutyPosting Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('duty_roster_id')->label('Roster')->relationship('roster', 'name')->searchable()->preload(),
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            Select::make('user_id')->label('User')->required()->relationship('user', 'name')->searchable()->preload(),
                            Select::make('staff_id')->label('Staff Record')->relationship('staff', 'staff_number')->searchable()->preload(),
                            Select::make('department_id')->label('Department')->relationship('department', 'name')->searchable()->preload(),
                            Select::make('clinic_id')->label('Unit / Clinic')->relationship('clinic', 'name')->searchable()->preload(),
                            DatePicker::make('start_date')->label('Start Date')->required(),
                            DatePicker::make('end_date')->label('End Date')->required(),
                            Select::make('shift')->label('Shift')->required()->options(['Morning' => 'Morning', 'Afternoon' => 'Afternoon', 'Night' => 'Night', 'On-Call' => 'On-Call', 'Full Day' => 'Full Day'])->searchable(),
                            TimePicker::make('shift_start')->label('Shift Start'),
                            TimePicker::make('shift_end')->label('Shift End'),
                            Select::make('role_in_unit')->label('Role in Unit')->required()->options(['In-Charge' => 'In-Charge', 'Supervisor' => 'Supervisor', 'Officer' => 'Officer', 'Assistant' => 'Assistant', 'Trainee' => 'Trainee'])->searchable(),
                            Select::make('status')->label('Status')->required()->options(['Scheduled' => 'Scheduled', 'Active' => 'Active', 'Completed' => 'Completed', 'Cancelled' => 'Cancelled', 'Swapped' => 'Swapped'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
