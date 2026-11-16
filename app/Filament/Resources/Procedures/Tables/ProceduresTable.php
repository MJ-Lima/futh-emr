<?php

namespace App\Filament\Resources\Procedures\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn, TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ProceduresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('code')->label('Code')->searchable(),
                    TextColumn::make('name')->label('Procedure')->searchable(),
                    TextColumn::make('category.name')->label('Category')->sortable(),
                    TextColumn::make('type')->label('Type')->badge()->color('success')->searchable(),
                    TextColumn::make('site')->label('Site')->badge()->color('success')->searchable(),
                    TextColumn::make('anaesthesia_type')->label('Anaesthesia')->badge()->color('success')->searchable(),
                    TextColumn::make('estimated_duration_minutes')->label('Est. (min)')->searchable(),
                    TextColumn::make('standard_fee')->label('Fee')->searchable(),
                    IconColumn::make('is_active')->label('Active')->boolean(),
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
