<?php

namespace App\Filament\Resources\Admissions\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class AdmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Admission Information')
                    ->schema([
                        TextEntry::make('admission_number')->label('Admission #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('ward.name')->label('Ward'),
                        TextEntry::make('bed.bed_number')->label('Bed'),
                        TextEntry::make('admittingDoctor.name')->label('Admitting Dr'),
                        TextEntry::make('admission_type')->label('Type'),
                        TextEntry::make('admission_date')->label('Admitted')->dateTime(),
                        TextEntry::make('lengthOfStayDays')->label('LOS (days)'),
                    ])
                    ->columns(2),
            ]);
    }
}
