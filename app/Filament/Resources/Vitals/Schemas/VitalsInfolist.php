<?php

namespace App\Filament\Resources\Vitals\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class VitalsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vitals Information')
                    ->schema([
                        TextEntry::make('recorded_at')->label('Recorded At')->dateTime(),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('encounter.encounter_number')->label('Encounter'),
                        TextEntry::make('bpLabel')->label('BP'),
                        TextEntry::make('heart_rate')->label('HR'),
                        TextEntry::make('temperature_c')->label('Temp'),
                        TextEntry::make('spo2')->label('SpO2'),
                        TextEntry::make('bmi')->label('BMI'),
                    ])
                    ->columns(2),
            ]);
    }
}
