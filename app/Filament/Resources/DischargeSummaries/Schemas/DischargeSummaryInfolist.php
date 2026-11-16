<?php

namespace App\Filament\Resources\DischargeSummaries\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class DischargeSummaryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('DischargeSummary Information')
                    ->schema([
                        TextEntry::make('admission.admission_number')->label('Admission'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('dischargingDoctor.name')->label('Discharging Dr'),
                        TextEntry::make('discharge_date')->label('Discharge Date')->dateTime(),
                        TextEntry::make('discharge_type')->label('Type'),
                        TextEntry::make('follow_up_date')->label('Follow-up')->date(),
                        TextEntry::make('is_signed')->label('Signed'),
                    ])
                    ->columns(2),
            ]);
    }
}
