<?php

namespace App\Filament\Resources\BloodCrossmatches\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class BloodCrossmatchInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BloodCrossmatch Information')
                    ->schema([
                        TextEntry::make('crossmatch_number')->label('XM #'),
                        TextEntry::make('bloodRequest.request_number')->label('Request'),
                        TextEntry::make('bloodUnit.unit_number')->label('Unit'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('method')->label('Method'),
                        TextEntry::make('major_crossmatch')->label('Major XM'),
                        TextEntry::make('minor_crossmatch')->label('Minor XM'),
                        TextEntry::make('performed_at')->label('Performed')->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }
}
