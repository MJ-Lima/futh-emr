<?php

namespace App\Filament\Resources\ClinicTemplates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ClinicTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->weight('semibold'),
                TextColumn::make('clinic_type')->badge()->color('info'),
                TextColumn::make('section_type')->badge()
                    ->formatStateUsing(fn ($s) => \App\Models\ClinicTemplate::SECTION_TYPES[$s] ?? $s),
                TextColumn::make('description')->limit(50)->placeholder('—'),
                TextColumn::make('is_active')->badge()->color(fn ($s) => $s ? 'success' : 'danger'),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('clinic_type')->options(\App\Models\ClinicTemplate::CLINIC_TYPES),
                \Filament\Tables\Filters\SelectFilter::make('section_type')->options(\App\Models\ClinicTemplate::SECTION_TYPES),
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
