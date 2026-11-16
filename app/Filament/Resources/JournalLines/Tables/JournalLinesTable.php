<?php

namespace App\Filament\Resources\JournalLines\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class JournalLinesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('journalEntry.entry_number')->label('Journal Entry')->sortable(),
                    TextColumn::make('chartOfAccount.account_code')->label('Account')->sortable(),
                    TextColumn::make('chartOfAccount.name')->label('Account Name')->sortable(),
                    TextColumn::make('description')->label('Description')->searchable(),
                    TextColumn::make('debit')->label('Debit')->searchable(),
                    TextColumn::make('credit')->label('Credit')->searchable(),
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
