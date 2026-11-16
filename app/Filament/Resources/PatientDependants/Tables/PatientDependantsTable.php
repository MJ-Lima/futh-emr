<?php

namespace App\Filament\Resources\PatientDependants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class PatientDependantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('principalPatient.full_name')->label('Principal')->sortable(),
                    TextColumn::make('full_name')->label('Dependant')->searchable(),
                    TextColumn::make('relationship')->label('Relationship')->badge()->color('success')->searchable(),
                    TextColumn::make('date_of_birth')->label('DOB')->date()->searchable(),
                    TextColumn::make('gender')->label('Gender')->badge()->color('success')->searchable(),
                    TextColumn::make('verification_status')->label('Verification')->badge()->color('success')->searchable(),
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
