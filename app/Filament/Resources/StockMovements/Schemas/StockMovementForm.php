<?php

namespace App\Filament\Resources\StockMovements\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('StockMovement Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('drug_id')->label('Drug')->required()->relationship('drug', 'name')->searchable()->preload(),
                            Select::make('drug_batch_id')->label('Batch')->relationship('drugBatch', 'batch_number')->searchable()->preload(),
                            Select::make('dispensing_point_id')->label('Dispensing Point')->relationship('dispensingPoint', 'name')->searchable()->preload(),
                            Select::make('type')->label('Movement Type')->required()->options(['Receipt' => 'Receipt', 'Transfer In' => 'Transfer In', 'Transfer Out' => 'Transfer Out', 'Dispense' => 'Dispense', 'Adjustment In' => 'Adjustment In', 'Adjustment Out' => 'Adjustment Out', 'Return' => 'Return', 'Disposal' => 'Disposal', 'Stock Take' => 'Stock Take'])->searchable(),
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
