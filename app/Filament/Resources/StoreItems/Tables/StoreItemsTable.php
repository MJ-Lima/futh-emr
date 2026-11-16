<?php

namespace App\Filament\Resources\StoreItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn, TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class StoreItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('code')->label('Code')->searchable(),
                    TextColumn::make('name')->label('Item Name')->searchable(),
                    TextColumn::make('category.name')->label('Category')->sortable(),
                    TextColumn::make('unit_of_measure')->label('UoM')->searchable(),
                    TextColumn::make('cost_price')->label('Cost')->searchable(),
                    TextColumn::make('selling_price')->label('Selling')->searchable(),
                    IconColumn::make('is_consumable')->label('Consumable')->boolean(),
                    IconColumn::make('is_active')->label('Active')->boolean(),
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
