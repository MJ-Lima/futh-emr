<?php

namespace App\Filament\Resources\DrugStockLevels\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class DrugStockLevelInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('DrugStockLevel Information')
                    ->schema([
                        TextEntry::make('drug.name')->label('Drug'),
                        TextEntry::make('dispensingPoint.name')->label('Dispensing Point'),
                        TextEntry::make('quantity_on_hand')->label('On Hand'),
                        TextEntry::make('quantity_reserved')->label('Reserved'),
                        TextEntry::make('quantity_available')->label('Available'),
                        TextEntry::make('reorder_level')->label('Reorder Level'),
                        TextEntry::make('stock_status')->label('Status'),
                        TextEntry::make('last_received_date')->label('Last Received')->date(),
                    ])
                    ->columns(2),
            ]);
    }
}
