<?php

namespace App\Filament\Resources\Staff\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn; 
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class StaffTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('staff_number')->label('Staff #')->searchable(),
                    TextColumn::make('full_name')->label('Name')->searchable(),
                    TextColumn::make('hospital.name')->label('Hospital')->sortable(),
                    TextColumn::make('department.name')->label('Department')->sortable(),
                    TextColumn::make('clinic.name')->label('Clinic')->sortable(),
                    TextColumn::make('designation')->label('Designation')->searchable(),
                    TextColumn::make('staff_type')->label('Type')->badge()->color('success')->searchable(),
                    IconColumn::make('status')->label('Active')->boolean(),
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
