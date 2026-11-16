<?php

namespace App\Filament\Resources\LabPanels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class LabPanelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('code')->label('Code')->searchable(),
                    TextColumn::make('name')->label('Panel')->searchable(),
                    TextColumn::make('loinc_code')->label('LOINC')->searchable(),
                    TextColumn::make('specimen_type')->label('Specimen')->searchable(),
                    TextColumn::make('price')->label('Price')->searchable(),
                    TextColumn::make('turnaround_hours')->label('TAT (hrs)')->searchable(),
                    IconColumn::make('status')->label('Active')->boolean(),
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
