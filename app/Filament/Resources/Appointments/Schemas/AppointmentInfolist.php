<?php

namespace App\Filament\Resources\Appointments\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class AppointmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Appointment Information')
                    ->schema([
                        TextEntry::make('appointment_number')->label('Appt #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('clinic.name')->label('Clinic'),
                        TextEntry::make('provider.name')->label('Provider'),
                        TextEntry::make('appointment_date')->label('Date')->date(),
                        TextEntry::make('appointment_time')->label('Time'),
                        TextEntry::make('visit_type')->label('Type'),
                        TextEntry::make('priority')->label('Priority'),
                    ])
                    ->columns(2),
            ]);
    }
}
