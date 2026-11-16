<?php

namespace App\Filament\Resources\DischargeSummaries\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class DischargeSummariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('admission.admission_number')->label('Admission')->sortable(),
                    TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                    TextColumn::make('dischargingDoctor.name')->label('Discharging Dr')->sortable(),
                    TextColumn::make('discharge_date')->label('Discharge Date')->dateTime()->searchable(),
                    TextColumn::make('discharge_type')->label('Type')->badge()->color('success')->searchable(),
                    TextColumn::make('follow_up_date')->label('Follow-up')->date()->searchable(),
                    IconColumn::make('is_signed')->label('Signed')->boolean(),
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
