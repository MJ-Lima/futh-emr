<?php

namespace App\Filament\Resources\StoreStocks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn, TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class StoreStocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('item.name')->label('Item')->sortable(),
                    TextColumn::make('location.name')->label('Location')->sortable(),
                    TextColumn::make('batch_number')->label('Batch')->searchable(),
                    TextColumn::make('expiry_date')->label('Expiry')->date()->searchable(),
                    TextColumn::make('quantity_remaining')->label('Remaining')->searchable(),
                    TextColumn::make('unit_cost')->label('Unit Cost')->searchable(),
                    IconColumn::make('is_quarantined')->label('Quarantined')->boolean(),
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
