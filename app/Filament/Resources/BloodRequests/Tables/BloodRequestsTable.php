<?php

namespace App\Filament\Resources\BloodRequests\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class BloodRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('request_number')->label('Request #')->searchable(),
                    TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                    TextColumn::make('requested_blood_group')->label('Group')->badge()->color('success')->searchable(),
                    TextColumn::make('component_requested')->label('Component')->badge()->color('success')->searchable(),
                    TextColumn::make('units_requested')->label('Units')->searchable(),
                    TextColumn::make('urgency')->label('Urgency')->badge()->color('success')->searchable(),
                    TextColumn::make('required_by')->label('Required By')->dateTime()->searchable(),
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
