<?php

namespace App\Filament\Resources\Wards\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class WardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('code')->label('Code')->searchable(),
                    TextColumn::make('name')->label('Ward Name')->searchable(),
                    TextColumn::make('hospital.name')->label('Hospital')->sortable(),
                    TextColumn::make('department.name')->label('Department')->sortable(),
                    TextColumn::make('type')->label('Type')->badge()->color('success')->searchable(),
                    TextColumn::make('gender_restriction')->label('Gender')->searchable(),
                    TextColumn::make('total_beds')->label('Beds')->searchable(),
                    TextColumn::make('occupiedBedsCount')->label('Occupied')->searchable(),
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
