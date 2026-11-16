<?php

namespace App\Filament\Resources\ReportTemplates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn, TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ReportTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('code')->label('Code')->searchable(),
                    TextColumn::make('name')->label('Template')->searchable(),
                    TextColumn::make('category')->label('Category')->badge()->color('success')->searchable(),
                    TextColumn::make('frequency')->label('Frequency')->badge()->color('success')->searchable(),
                    IconColumn::make('is_scheduled')->label('Scheduled')->boolean(),
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
