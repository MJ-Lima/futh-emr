<?php

namespace App\Filament\Resources\JournalEntries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class JournalEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('entry_number')->label('Entry #')->searchable(),
                    TextColumn::make('entry_date')->label('Date')->date()->searchable(),
                    TextColumn::make('type')->label('Type')->badge()->color('success')->searchable(),
                    TextColumn::make('reference_number')->label('Reference')->searchable(),
                    TextColumn::make('total_debit')->label('Debit')->searchable(),
                    TextColumn::make('total_credit')->label('Credit')->searchable(),
                    IconColumn::make('is_posted')->label('Posted')->boolean(),
                    IconColumn::make('is_reversed')->label('Reversed')->boolean(),
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
