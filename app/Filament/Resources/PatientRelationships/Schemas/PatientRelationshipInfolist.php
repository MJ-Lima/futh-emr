<?php

namespace App\Filament\Resources\PatientRelationships\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class PatientRelationshipInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('PatientRelationship Information')
                    ->schema([
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('relatedPatient.full_name')->label('Related To'),
                        TextEntry::make('relationshipType.name')->label('Relationship'),
                        TextEntry::make('is_primary_contact')->label('Primary'),
                        TextEntry::make('is_emergency_contact')->label('Emergency'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
