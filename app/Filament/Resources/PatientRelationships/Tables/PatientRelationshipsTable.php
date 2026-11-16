<?php

namespace App\Filament\Resources\PatientRelationships\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class PatientRelationshipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                    TextColumn::make('relatedPatient.full_name')->label('Related To')->sortable(),
                    TextColumn::make('relationshipType.name')->label('Relationship')->sortable(),
                    IconColumn::make('is_primary_contact')->label('Primary')->boolean(),
                    IconColumn::make('is_emergency_contact')->label('Emergency')->boolean(),
                    TextColumn::make('status')->label('Status')->badge()->color('success')->searchable(),
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
