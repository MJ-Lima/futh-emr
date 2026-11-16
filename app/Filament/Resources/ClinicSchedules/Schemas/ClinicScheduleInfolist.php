<?php

namespace App\Filament\Resources\ClinicSchedules\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class ClinicScheduleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ClinicSchedule Information')
                    ->schema([
                        TextEntry::make('clinic.name')->label('Clinic'),
                        TextEntry::make('appointmentType.name')->label('Appt Type'),
                        TextEntry::make('weekday')->label('Day'),
                        TextEntry::make('start_time')->label('Start'),
                        TextEntry::make('end_time')->label('End'),
                        TextEntry::make('slot_duration_minutes')->label('Slot (min)'),
                        TextEntry::make('max_patients_per_slot')->label('Max/Slot'),
                        TextEntry::make('is_active')->label('Active'),
                    ])
                    ->columns(2),
            ]);
    }
}
