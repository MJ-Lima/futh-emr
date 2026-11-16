<?php

namespace App\Filament\Resources\AppointmentSlots\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class AppointmentSlotInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('AppointmentSlot Information')
                    ->schema([
                        TextEntry::make('slot_date')->label('Date')->date(),
                        TextEntry::make('clinic.name')->label('Clinic'),
                        TextEntry::make('provider.name')->label('Provider'),
                        TextEntry::make('appointmentType.name')->label('Type'),
                        TextEntry::make('start_time')->label('Start'),
                        TextEntry::make('end_time')->label('End'),
                        TextEntry::make('duration_minutes')->label('Min'),
                        TextEntry::make('booked_count')->label('Booked'),
                    ])
                    ->columns(2),
            ]);
    }
}
