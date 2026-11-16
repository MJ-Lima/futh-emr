<?php

namespace App\Filament\Resources\GeneralStoreStocks\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class GeneralStoreStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('GeneralStoreStock Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('general_store_item_id')->label('Item')->required()->relationship('general_store_item', 'name')->searchable()->preload(),
                            Select::make('general_store_location_id')->label('Location')->required()->relationship('general_store_location', 'name')->searchable()->preload(),
                            TextInput::make('batch_number')->label('Batch Number'),
                            DatePicker::make('expiry_date')->label('Expiry Date'),
                            TextInput::make('quantity_received')->label('Quantity Received')->numeric(),
                            TextInput::make('quantity_remaining')->label('Quantity Remaining')->numeric(),
                            TextInput::make('unit_cost')->label('Unit Cost')->numeric(),
                            DatePicker::make('received_date')->label('Received Date'),
                            TextInput::make('reference_number')->label('Reference #'),
                            Toggle::make('is_quarantined')->label('Quarantined'),
                        ]),
                    ]),
            ]);
    }
}
