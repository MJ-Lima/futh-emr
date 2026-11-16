<?php

namespace App\Filament\Resources\DrugStockLevels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class DrugStockLevelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('drug.name')->label('Drug')->sortable(),
                    TextColumn::make('dispensingPoint.name')->label('Dispensing Point')->sortable(),
                    TextColumn::make('quantity_on_hand')->label('On Hand')->searchable(),
                    TextColumn::make('quantity_reserved')->label('Reserved')->searchable(),
                    TextColumn::make('quantity_available')->label('Available')->searchable(),
                    TextColumn::make('reorder_level')->label('Reorder Level')->searchable(),
                    TextColumn::make('stock_status')->label('Status')->badge()->color('success')->searchable(),
                    TextColumn::make('last_received_date')->label('Last Received')->date()->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
