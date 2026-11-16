<?php

namespace App\Filament\Resources\StoreMovements\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class StoreMovementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('StoreMovement Information')
                    ->schema([
                        TextEntry::make('movement_number')->label('Movement #'),
                        TextEntry::make('item.name')->label('Item'),
                        TextEntry::make('type')->label('Type'),
                        TextEntry::make('fromLocation.name')->label('From'),
                        TextEntry::make('toLocation.name')->label('To'),
                        TextEntry::make('quantity')->label('Quantity'),
                        TextEntry::make('movement_date')->label('Date')->dateTime(),
                        TextEntry::make('user.name')->label('By'),
                    ])
                    ->columns(2),
            ]);
    }
}
