<?php

namespace App\Filament\Resources\AppointmentSlots\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class AppointmentSlotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('AppointmentSlot Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('clinic_id')->label('Clinic')->required()->relationship('clinic', 'name')->searchable()->preload(),
                            Select::make('provider_id')->label('Provider')->relationship('provider', 'name')->searchable()->preload(),
                            Select::make('appointment_type_id')->label('Appointment Type')->relationship('appointment_type', 'name')->searchable()->preload(),
                            Select::make('clinic_schedule_id')->label('Clinic Schedule')->relationship('clinic_schedule', 'id')->searchable()->preload(),
                            Select::make('provider_schedule_id')->label('Provider Schedule')->relationship('provider_schedule', 'id')->searchable()->preload(),
                            DatePicker::make('slot_date')->label('Slot Date')->required(),
                            TimePicker::make('start_time')->label('Start Time')->required(),
                            TimePicker::make('end_time')->label('End Time')->required(),
                            TextInput::make('duration_minutes')->label('Duration (min)')->numeric(),
                            TextInput::make('capacity')->label('Capacity')->numeric(),
                            Select::make('status')->label('Status')->required()->options(['Available' => 'Available', 'Booked' => 'Booked', 'Blocked' => 'Blocked', 'Completed' => 'Completed', 'Cancelled' => 'Cancelled', 'No Show' => 'No Show'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
