<?php

namespace App\Filament\Resources\Diagnoses\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class DiagnosisInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Diagnosis Information')
                    ->schema([
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('icd10_code')->label('ICD-10'),
                        TextEntry::make('description')->label('Description'),
                        TextEntry::make('type')->label('Type'),
                        TextEntry::make('status')->label('Status'),
                        TextEntry::make('onset_date')->label('Onset')->date(),
                        TextEntry::make('recorder.name')->label('Recorded By'),
                    ])
                    ->columns(2),
            ]);
    }
}
