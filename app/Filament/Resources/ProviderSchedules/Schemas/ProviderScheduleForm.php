<?php

namespace App\Filament\Resources\ProviderSchedules\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ProviderScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ProviderSchedule Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('user_id')->label('Provider')->required()->relationship('user', 'name')->searchable()->preload(),
                            Select::make('clinic_id')->label('Clinic')->relationship('clinic', 'name')->searchable()->preload(),
                            Select::make('weekday')->label('Weekday')->required()->options(['Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'])->searchable(),
                            TimePicker::make('start_time')->label('Start Time')->required(),
                            TimePicker::make('end_time')->label('End Time')->required(),
                            TimePicker::make('break_start')->label('Break Start'),
                            TimePicker::make('break_end')->label('Break End'),
                            TextInput::make('slot_duration_minutes')->label('Slot Duration (min)')->numeric(),
                            TextInput::make('max_patients_per_slot')->label('Max Patients/Slot')->numeric(),
                            DatePicker::make('effective_from')->label('Effective From'),
                            DatePicker::make('effective_until')->label('Effective Until'),
                            Toggle::make('is_active')->label('Active'),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
