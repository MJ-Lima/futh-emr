<?php

namespace App\Filament\Resources\BloodUnits\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class BloodUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('unit_number')->label('Unit #')->searchable(),
                    TextColumn::make('donor.blood_group')->label('Group')->sortable(),
                    TextColumn::make('component_type')->label('Component')->badge()->color('success')->searchable(),
                    TextColumn::make('volume_ml')->label('Vol (ml)')->searchable(),
                    TextColumn::make('collection_date')->label('Collected')->date()->searchable(),
                    TextColumn::make('expiry_date')->label('Expires')->date()->searchable(),
                    IconColumn::make('is_screened')->label('Screened')->boolean(),
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
