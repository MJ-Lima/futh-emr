<?php

namespace App\Filament\Resources\ClinicalNotes\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ClinicalNotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('note_number')->label('Note #')->searchable(),
                    TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                    TextColumn::make('note_type')->label('Type')->badge()->color('success')->searchable(),
                    TextColumn::make('title')->label('Title')->searchable(),
                    IconColumn::make('is_signed')->label('Signed')->boolean(),
                    TextColumn::make('author.name')->label('Author')->sortable(),
                    TextColumn::make('signed_at')->label('Signed At')->dateTime()->searchable(),
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
