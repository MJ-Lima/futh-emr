<?php

namespace App\Filament\Resources\Prescriptions\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class PrescriptionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Prescription Information')
                    ->schema([
                        TextEntry::make('prescription_number')->label('RX #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('prescriber.name')->label('Prescriber'),
                        TextEntry::make('dispensingPoint.name')->label('Dispensing Point'),
                        TextEntry::make('prescribed_at')->label('Prescribed')->dateTime(),
                        TextEntry::make('priority')->label('Priority'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
