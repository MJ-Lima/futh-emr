<?php

namespace App\Filament\Resources\ProviderSchedules\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class ProviderScheduleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ProviderSchedule Information')
                    ->schema([
                        TextEntry::make('user.name')->label('Provider'),
                        TextEntry::make('clinic.name')->label('Clinic'),
                        TextEntry::make('weekday')->label('Day'),
                        TextEntry::make('start_time')->label('Start'),
                        TextEntry::make('end_time')->label('End'),
                        TextEntry::make('slot_duration_minutes')->label('Slot (min)'),
                        TextEntry::make('effective_from')->label('From')->date(),
                        TextEntry::make('effective_until')->label('Until')->date(),
                    ])
                    ->columns(2),
            ]);
    }
}
