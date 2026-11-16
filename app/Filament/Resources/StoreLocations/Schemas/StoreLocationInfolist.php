<?php

namespace App\Filament\Resources\StoreLocations\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class StoreLocationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('StoreLocation Information')
                    ->schema([
                        TextEntry::make('code')->label('Code'),
                        TextEntry::make('name')->label('Location'),
                        TextEntry::make('type')->label('Type'),
                        TextEntry::make('department.name')->label('Department'),
                        TextEntry::make('building')->label('Building'),
                        TextEntry::make('floor')->label('Floor'),
                        TextEntry::make('room')->label('Room'),
                        TextEntry::make('inCharge.name')->label('In-Charge'),
                    ])
                    ->columns(2),
            ]);
    }
}
