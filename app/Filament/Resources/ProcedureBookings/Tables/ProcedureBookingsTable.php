<?php

namespace App\Filament\Resources\ProcedureBookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ProcedureBookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('booking_number')->label('Booking #')->searchable(),
                    TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                    TextColumn::make('procedure.name')->label('Procedure')->sortable(),
                    TextColumn::make('surgeon.name')->label('Surgeon')->sortable(),
                    TextColumn::make('scheduled_start')->label('Scheduled')->dateTime()->searchable(),
                    TextColumn::make('priority')->label('Priority')->badge()->color('success')->searchable(),
                    IconColumn::make('consent_signed')->label('Consent')->boolean(),
                    TextColumn::make('booking_status')->label('Status')->badge()->color('success')->searchable(),
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
