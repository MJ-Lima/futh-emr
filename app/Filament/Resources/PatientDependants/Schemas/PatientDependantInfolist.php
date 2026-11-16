<?php

namespace App\Filament\Resources\PatientDependants\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class PatientDependantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('PatientDependant Information')
                    ->schema([
                        TextEntry::make('principalPatient.full_name')->label('Principal'),
                        TextEntry::make('full_name')->label('Dependant'),
                        TextEntry::make('relationship')->label('Relationship'),
                        TextEntry::make('date_of_birth')->label('DOB')->date(),
                        TextEntry::make('gender')->label('Gender'),
                        TextEntry::make('verification_status')->label('Verification'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
