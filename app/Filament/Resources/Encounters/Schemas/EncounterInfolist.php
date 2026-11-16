<?php

namespace App\Filament\Resources\Encounters\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
class EncounterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('hospital_id')
                    ->numeric(),
                TextEntry::make('patient_id')
                    ->numeric(),
                TextEntry::make('clinic_id')
                    ->numeric(),
                TextEntry::make('user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('encounter_number'),
                TextEntry::make('visit_type')
                    ->badge(),
                TextEntry::make('encounter_date')
                    ->date(),
                TextEntry::make('encounter_time')
                    ->time(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('chief_complaint')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('referred_by')
                    ->placeholder('-'),
                TextEntry::make('appointment_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_by')
                    ->numeric(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
