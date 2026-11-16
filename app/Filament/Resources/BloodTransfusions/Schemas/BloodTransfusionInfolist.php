<?php

namespace App\Filament\Resources\BloodTransfusions\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class BloodTransfusionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BloodTransfusion Information')
                    ->schema([
                        TextEntry::make('transfusion_number')->label('Transfusion #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('bloodRequest.request_number')->label('Request'),
                        TextEntry::make('bloodUnit.unit_number')->label('Unit'),
                        TextEntry::make('transfused_by.name')->label('By'),
                        TextEntry::make('transfusion_started_at')->label('Started')->dateTime(),
                        TextEntry::make('transfusion_ended_at')->label('Ended')->dateTime(),
                        TextEntry::make('reaction_severity')->label('Reactions'),
                    ])
                    ->columns(2),
            ]);
    }
}
