<?php

namespace App\Filament\Resources\ProcedureSessions\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class ProcedureSessionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ProcedureSession Information')
                    ->schema([
                        TextEntry::make('session_number')->label('Session #'),
                        TextEntry::make('booking.booking_number')->label('Booking'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('procedure.name')->label('Procedure'),
                        TextEntry::make('surgeon.name')->label('Surgeon'),
                        TextEntry::make('actual_start')->label('Started')->dateTime(),
                        TextEntry::make('actual_end')->label('Ended')->dateTime(),
                        TextEntry::make('duration_minutes')->label('Duration (min)'),
                    ])
                    ->columns(2),
            ]);
    }
}
