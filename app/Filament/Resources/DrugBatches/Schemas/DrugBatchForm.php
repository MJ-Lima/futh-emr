<?php

namespace App\Filament\Resources\DrugBatches\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class DrugBatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('DrugBatch Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('drug_id')->label('Drug')->required()->relationship('drug', 'name')->searchable()->preload(),
                            TextInput::make('batch_number')->label('Batch Number')->required(),
                            DatePicker::make('expiry_date')->label('Expiry Date')->required(),
                            DatePicker::make('manufactured_date')->label('Manufactured Date'),
                            TextInput::make('quantity_received')->label('Qty Received')->numeric(),
                            TextInput::make('quantity_remaining')->label('Qty Remaining')->numeric(),
                            TextInput::make('cost_price')->label('Cost Price')->numeric(),
                            TextInput::make('supplier')->label('Supplier'),
                            DatePicker::make('received_date')->label('Received Date'),
                            Toggle::make('is_quarantined')->label('Quarantined'),
                        ]),
                    ]),
            ]);
    }
}
