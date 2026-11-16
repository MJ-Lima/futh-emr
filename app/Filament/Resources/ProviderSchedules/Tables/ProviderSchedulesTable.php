<?php

namespace App\Filament\Resources\ProviderSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn, TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ProviderSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('user.name')->label('Provider')->sortable(),
                    TextColumn::make('clinic.name')->label('Clinic')->sortable(),
                    TextColumn::make('weekday')->label('Day')->badge()->color('success')->searchable(),
                    TextColumn::make('start_time')->label('Start')->searchable(),
                    TextColumn::make('end_time')->label('End')->searchable(),
                    TextColumn::make('slot_duration_minutes')->label('Slot (min)')->searchable(),
                    TextColumn::make('effective_from')->label('From')->date()->searchable(),
                    TextColumn::make('effective_until')->label('Until')->date()->searchable(),
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
