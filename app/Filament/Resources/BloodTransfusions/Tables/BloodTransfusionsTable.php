<?php

namespace App\Filament\Resources\BloodTransfusions\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class BloodTransfusionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('transfusion_number')->label('Transfusion #')->searchable(),
                    TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                    TextColumn::make('bloodRequest.request_number')->label('Request')->sortable(),
                    TextColumn::make('bloodUnit.unit_number')->label('Unit')->sortable(),
                    TextColumn::make('transfused_by.name')->label('By')->sortable(),
                    TextColumn::make('transfusion_started_at')->label('Started')->dateTime()->searchable(),
                    TextColumn::make('transfusion_ended_at')->label('Ended')->dateTime()->searchable(),
                    TextColumn::make('reaction_severity')->label('Reactions')->badge()->color('success')->searchable(),
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
