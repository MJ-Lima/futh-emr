<?php

namespace App\Filament\Resources\DutyPostings\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class DutyPostingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('user.name')->label('Staff')->sortable(),
                    TextColumn::make('hospital.name')->label('Hospital')->sortable(),
                    TextColumn::make('department.name')->label('Department')->sortable(),
                    TextColumn::make('clinic.name')->label('Unit / Clinic')->sortable(),
                    TextColumn::make('start_date')->label('Start')->date()->searchable(),
                    TextColumn::make('end_date')->label('End')->date()->searchable(),
                    TextColumn::make('shift')->label('Shift')->badge()->color('success')->searchable(),
                    TextColumn::make('role_in_unit')->label('Role')->badge()->color('success')->searchable(),
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
