<?php

namespace App\Filament\Resources\ExpenseClaims\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ExpenseClaimsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('claim_number')->label('Claim #')->searchable(),
                    TextColumn::make('claimant.name')->label('Claimant')->sortable(),
                    TextColumn::make('department.name')->label('Department')->sortable(),
                    TextColumn::make('title')->label('Title')->searchable(),
                    TextColumn::make('type')->label('Type')->badge()->color('success')->searchable(),
                    TextColumn::make('expense_date')->label('Date')->date()->searchable(),
                    TextColumn::make('total_amount')->label('Total')->searchable(),
                    TextColumn::make('approved_amount')->label('Approved')->searchable(),
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
