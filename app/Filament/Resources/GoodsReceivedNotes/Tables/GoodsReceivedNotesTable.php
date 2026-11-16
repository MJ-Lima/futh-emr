<?php

namespace App\Filament\Resources\GoodsReceivedNotes\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class GoodsReceivedNotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('grn_number')->label('GRN #')->searchable(),
                    TextColumn::make('supplier.name')->label('Supplier')->sortable(),
                    TextColumn::make('received_at')->label('Received')->dateTime()->searchable(),
                    TextColumn::make('invoice_number')->label('Invoice #')->searchable(),
                    TextColumn::make('total_value')->label('Total Value')->searchable(),
                    TextColumn::make('total_rejected')->label('Rejected')->searchable(),
                    TextColumn::make('status')->label('Status')->badge()->color('success')->searchable(),
                    TextColumn::make('receiver.name')->label('Received By')->sortable(),
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
