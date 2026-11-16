<?php

namespace App\Filament\Resources\ClinicSchedules\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ClinicSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('clinic.name')->label('Clinic')->sortable(),
                    TextColumn::make('appointmentType.name')->label('Appt Type')->sortable(),
                    TextColumn::make('weekday')->label('Day')->badge()->color('success')->searchable(),
                    TextColumn::make('start_time')->label('Start')->searchable(),
                    TextColumn::make('end_time')->label('End')->searchable(),
                    TextColumn::make('slot_duration_minutes')->label('Slot (min)')->searchable(),
                    TextColumn::make('max_patients_per_slot')->label('Max/Slot')->searchable(),
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
