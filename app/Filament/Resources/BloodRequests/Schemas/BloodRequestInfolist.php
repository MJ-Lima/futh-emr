<?php

namespace App\Filament\Resources\BloodRequests\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class BloodRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BloodRequest Information')
                    ->schema([
                        TextEntry::make('request_number')->label('Request #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('requested_blood_group')->label('Group'),
                        TextEntry::make('component_requested')->label('Component'),
                        TextEntry::make('units_requested')->label('Units'),
                        TextEntry::make('urgency')->label('Urgency'),
                        TextEntry::make('required_by')->label('Required By')->dateTime(),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
