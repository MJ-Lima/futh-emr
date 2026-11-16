<?php

namespace App\Filament\Resources\BedTransfers\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class BedTransfersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('transfer_number')->label('Transfer #')->searchable(),
                    TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                    TextColumn::make('fromWard.name')->label('From Ward')->sortable(),
                    TextColumn::make('fromBed.bed_number')->label('From Bed')->sortable(),
                    TextColumn::make('toWard.name')->label('To Ward')->sortable(),
                    TextColumn::make('toBed.bed_number')->label('To Bed')->sortable(),
                    TextColumn::make('transfer_date')->label('Date')->dateTime()->searchable(),
                    TextColumn::make('authorized_by.name')->label('Authorized By')->sortable(),
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
