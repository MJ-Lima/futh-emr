<?php

namespace App\Filament\Resources\InsurancePlans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class InsurancePlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('code')->label('Code')->searchable(),
                    TextColumn::make('name')->label('Plan Name')->searchable(),
                    TextColumn::make('provider.name')->label('Provider')->sortable(),
                    TextColumn::make('tier')->label('Tier')->badge()->color('success')->searchable(),
                    TextColumn::make('coverage_type')->label('Coverage')->searchable(),
                    TextColumn::make('annual_premium')->label('Annual Premium')->searchable(),
                    TextColumn::make('annual_benefit_limit')->label('Benefit Limit')->searchable(),
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
