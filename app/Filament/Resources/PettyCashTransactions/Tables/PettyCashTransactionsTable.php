<?php

namespace App\Filament\Resources\PettyCashTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class PettyCashTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('voucher_number')->label('Voucher #')->searchable(),
                    TextColumn::make('user.name')->label('By')->sortable(),
                    TextColumn::make('type')->label('Type')->badge()->color('success')->searchable(),
                    TextColumn::make('payee')->label('Payee')->searchable(),
                    TextColumn::make('category')->label('Category')->searchable(),
                    TextColumn::make('amount')->label('Amount')->searchable(),
                    TextColumn::make('balance_after')->label('Balance After')->searchable(),
                    TextColumn::make('transaction_date')->label('Date')->dateTime()->searchable(),
                    TextColumn::make('status')->label('Status')->badge()->color('success')->searchable(),
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
