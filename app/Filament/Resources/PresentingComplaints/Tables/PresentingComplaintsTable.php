<?php

namespace App\Filament\Resources\PresentingComplaints\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class PresentingComplaintsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->weight('semibold'),
                TextColumn::make('code')->badge()->color('info'),
                TextColumn::make('category')->badge(),
                TextColumn::make('description')->limit(40)->placeholder('—'),
                TextColumn::make('is_active')->badge()->color(fn ($s) => $s ? 'success' : 'danger'),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('category')->options(\App\Models\PresentingComplaint::CATEGORIES),
                \Filament\Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
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
