<?php

namespace App\Filament\Resources\Icd10Diagnoses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class Icd10DiagnosesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->badge()->color('info')->searchable(),
                TextColumn::make('description')->searchable()->limit(60)->weight('semibold'),
                TextColumn::make('category')->badge(),
                TextColumn::make('is_active')->badge()->color(fn ($s) => $s ? 'success' : 'danger'),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('category')->relationship('category', 'category'),
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
