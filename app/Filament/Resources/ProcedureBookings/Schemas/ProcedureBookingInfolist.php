<?php

namespace App\Filament\Resources\ProcedureBookings\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class ProcedureBookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ProcedureBooking Information')
                    ->schema([
                        TextEntry::make('booking_number')->label('Booking #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('procedure.name')->label('Procedure'),
                        TextEntry::make('surgeon.name')->label('Surgeon'),
                        TextEntry::make('scheduled_start')->label('Scheduled')->dateTime(),
                        TextEntry::make('priority')->label('Priority'),
                        TextEntry::make('consent_signed')->label('Consent'),
                        TextEntry::make('booking_status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
