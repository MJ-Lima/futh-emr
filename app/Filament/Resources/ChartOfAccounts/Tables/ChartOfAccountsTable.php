<?php

namespace App\Filament\Resources\ChartOfAccounts\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ChartOfAccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('account_code')->label('Code')->searchable(),
                    TextColumn::make('name')->label('Account Name')->searchable(),
                    TextColumn::make('type')->label('Type')->badge()->color('success')->searchable(),
                    TextColumn::make('sub_type')->label('Sub-type')->searchable(),
                    TextColumn::make('current_balance')->label('Balance')->searchable(),
                    IconColumn::make('is_bank_account')->label('Bank Acct')->boolean(),
                    TextColumn::make('status')->label('Status')->searchable(),
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
