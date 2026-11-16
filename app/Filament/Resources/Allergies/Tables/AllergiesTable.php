<?php

namespace App\Filament\Resources\Allergies\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class AllergiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                    TextColumn::make('substance')->label('Substance')->searchable(),
                    TextColumn::make('category')->label('Category')->badge()->color('success')->searchable(),
                    TextColumn::make('severity')->label('Severity')->badge()->color('success')->searchable(),
                    TextColumn::make('reaction')->label('Reaction')->searchable(),
                    TextColumn::make('status')->label('Status')->badge()->color('success')->searchable(),
                    TextColumn::make('onset_date')->label('Onset')->date()->searchable(),
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
