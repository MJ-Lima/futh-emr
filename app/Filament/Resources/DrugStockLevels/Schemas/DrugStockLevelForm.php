<?php

namespace App\Filament\Resources\DrugStockLevels\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class DrugStockLevelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('DrugStockLevel Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('drug_id')->label('Drug')->required()->relationship('drug', 'name')->searchable()->preload(),
                            Select::make('dispensing_point_id')->label('Dispensing Point')->relationship('dispensing_point', 'name')->searchable()->preload(),
                            TextInput::make('quantity_on_hand')->label('Quantity On Hand')->numeric(),
                            TextInput::make('quantity_reserved')->label('Quantity Reserved')->numeric(),
                            TextInput::make('quantity_in_transit')->label('Quantity In Transit')->numeric(),
                            TextInput::make('reorder_level')->label('Reorder Level')->numeric(),
                            TextInput::make('reorder_quantity')->label('Reorder Quantity')->numeric(),
                            DatePicker::make('last_received_date')->label('Last Received Date'),
                            DatePicker::make('last_dispensed_date')->label('Last Dispensed Date'),
                            DatePicker::make('last_counted_date')->label('Last Counted Date'),
                            Select::make('stock_status')->label('Stock Status')->required()->options(['In Stock' => 'In Stock', 'Low Stock' => 'Low Stock', 'Out of Stock' => 'Out of Stock', 'Over Stock' => 'Over Stock', 'Discontinued' => 'Discontinued'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
