<?php

namespace App\Filament\Resources\StockMovements\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class StockMovementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('StockMovement Information')
                    ->schema([
                        TextEntry::make('drug.name')->label('Drug'),
                        TextEntry::make('type')->label('Type'),
                        TextEntry::make('quantity')->label('Quantity'),
                        TextEntry::make('balance_after')->label('Balance'),
                        TextEntry::make('movement_date')->label('Date')->dateTime(),
                        TextEntry::make('reference_number')->label('Ref #'),
                        TextEntry::make('user.name')->label('By'),
                    ])
                    ->columns(2),
            ]);
    }
}
