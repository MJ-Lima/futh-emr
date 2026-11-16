<?php

namespace App\Filament\Resources\ProcedureSessions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ProcedureSessionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('session_number')->label('Session #')->searchable(),
                    TextColumn::make('booking.booking_number')->label('Booking')->sortable(),
                    TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                    TextColumn::make('procedure.name')->label('Procedure')->sortable(),
                    TextColumn::make('surgeon.name')->label('Surgeon')->sortable(),
                    TextColumn::make('actual_start')->label('Started')->dateTime()->searchable(),
                    TextColumn::make('actual_end')->label('Ended')->dateTime()->searchable(),
                    TextColumn::make('duration_minutes')->label('Duration (min)')->searchable(),
                    TextColumn::make('outcome')->label('Outcome')->badge()->color('success')->searchable(),
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
