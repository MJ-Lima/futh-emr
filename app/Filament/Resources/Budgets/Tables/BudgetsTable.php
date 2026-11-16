<?php

namespace App\Filament\Resources\Budgets\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class BudgetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('code')->label('Code')->searchable(),
                    TextColumn::make('name')->label('Budget Name')->searchable(),
                    TextColumn::make('hospital.name')->label('Hospital')->sortable(),
                    TextColumn::make('department.name')->label('Department')->sortable(),
                    TextColumn::make('period')->label('Period')->badge()->color('success')->searchable(),
                    TextColumn::make('fiscal_year')->label('FY')->searchable(),
                    TextColumn::make('planned_amount')->label('Planned')->searchable(),
                    TextColumn::make('actual_amount')->label('Actual')->searchable(),
                    TextColumn::make('variance')->label('Variance')->searchable(),
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
