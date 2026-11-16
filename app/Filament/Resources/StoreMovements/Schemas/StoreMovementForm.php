<?php

namespace App\Filament\Resources\StoreMovements\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class StoreMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('StoreMovement Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('store_item_id')->label('Item')->required()->relationship('store_item', 'name')->searchable()->preload(),
                            Select::make('store_stock_id')->label('Stock Batch')->relationship('store_stock', 'batch_number')->searchable()->preload(),
                            Select::make('from_location_id')->label('From Location')->relationship('from_location', 'name')->searchable()->preload(),
                            Select::make('to_location_id')->label('To Location')->relationship('to_location', 'name')->searchable()->preload(),
                            Select::make('type')->label('Movement Type')->required()->options(['Receipt' => 'Receipt', 'Transfer In' => 'Transfer In', 'Transfer Out' => 'Transfer Out', 'Issue' => 'Issue', 'Return' => 'Return', 'Adjustment In' => 'Adjustment In', 'Adjustment Out' => 'Adjustment Out', 'Disposal' => 'Disposal', 'Stock Take' => 'Stock Take'])->searchable(),
                            TextInput::make('reference_number')->label('Reference #'),
                            DateTimePicker::make('movement_date')->label('Movement Date')->required(),
                            TextInput::make('quantity')->label('Quantity')->numeric(),
                            TextInput::make('balance_after')->label('Balance After')->numeric(),
                            Textarea::make('reason')->label('Reason'),
                        ]),
                    ]),
            ]);
    }
}
