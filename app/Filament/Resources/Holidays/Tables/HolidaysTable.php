<?php

namespace App\Filament\Resources\Holidays\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class HolidaysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('name')->label('Holiday Name')->searchable(),
                    TextColumn::make('holiday_date')->label('Start')->date()->searchable(),
                    TextColumn::make('end_date')->label('End')->date()->searchable(),
                    TextColumn::make('type')->label('Type')->badge()->color('success')->searchable(),
                    IconColumn::make('is_working_day')->label('Working Day')->boolean(),
                    IconColumn::make('affects_clinics')->label('Affects Clinics')->boolean(),
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
