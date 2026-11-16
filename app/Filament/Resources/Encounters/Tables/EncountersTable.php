<?php

namespace App\Filament\Resources\Encounters\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class EncountersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hospital_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('patient_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('clinic_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('encounter_number')
                    ->searchable(),
                TextColumn::make('visit_type')
                    ->badge(),
                TextColumn::make('encounter_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('encounter_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('referred_by')
                    ->searchable(),
                TextColumn::make('appointment_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
