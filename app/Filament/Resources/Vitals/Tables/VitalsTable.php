<?php

namespace App\Filament\Resources\Vitals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class VitalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('recorded_at')->label('Recorded At')->dateTime()->searchable(),
                    TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                    TextColumn::make('encounter.encounter_number')->label('Encounter')->sortable(),
                    TextColumn::make('bp_label')->label('BP')->searchable(),
                    TextColumn::make('heart_rate')->label('HR')->searchable(),
                    TextColumn::make('temperature_c')->label('Temp')->searchable(),
                    TextColumn::make('spo2')->label('SpO2')->searchable(),
                    TextColumn::make('bmi')->label('BMI')->searchable(),
                    TextColumn::make('recorder.name')->label('Recorded By')->sortable(),
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
