<?php

namespace App\Filament\Resources\ClinicSchedules\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ClinicScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ClinicSchedule Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('clinic_id')->label('Clinic')->required()->relationship('clinic', 'name')->searchable()->preload(),
                            Select::make('appointment_type_id')->label('Appointment Type')->relationship('appointment_type', 'name')->searchable()->preload(),
                            Select::make('weekday')->label('Weekday')->required()->options(['Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'])->searchable(),
                            TimePicker::make('start_time')->label('Start Time')->required(),
                            TimePicker::make('end_time')->label('End Time')->required(),
                            TimePicker::make('break_start')->label('Break Start'),
                            TimePicker::make('break_end')->label('Break End'),
                            TextInput::make('slot_duration_minutes')->label('Slot Duration (min)')->numeric(),
                            TextInput::make('max_patients_per_slot')->label('Max Patients/Slot')->numeric(),
                            TextInput::make('buffer_minutes_before')->label('Buffer Before (min)')->numeric(),
                            TextInput::make('buffer_minutes_after')->label('Buffer After (min)')->numeric(),
                            Toggle::make('is_active')->label('Active'),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
