<?php

namespace App\Filament\Resources\DrugBatches\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class DrugBatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('batch_number')->label('Batch #')->searchable(),
                    TextColumn::make('drug.name')->label('Drug')->sortable(),
                    TextColumn::make('expiry_date')->label('Expiry')->date()->searchable(),
                    TextColumn::make('quantity_received')->label('Received')->searchable(),
                    TextColumn::make('quantity_remaining')->label('Remaining')->searchable(),
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
