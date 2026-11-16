<?php

namespace App\Filament\Resources\LabTransfers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class LabTransfersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('labOrder.order_number')->label('Order #')->searchable()->weight('semibold'),
                TextColumn::make('fromLab.name')->label('From')->placeholder('—'),
                TextColumn::make('toLab.name')->label('To'),
                TextColumn::make('reason')->label('Reason')->searchable()->limit(40),
                TextColumn::make('status')->label('Status')->badge()->color('warning'),
                TextColumn::make('transferred_at')->label('Transferred')->dateTime('d M, H:i'),
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
            ])
            ->defaultSort('created_at', 'desc');
    }
}
