<?php

namespace App\Filament\Resources\Admissions\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class AdmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('admission_number')->label('Admission #')->searchable(),
                TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                TextColumn::make('ward.name')->label('Ward')->sortable(),
                TextColumn::make('bed.bed_number')->label('Bed')->sortable(),
                TextColumn::make('admittingDoctor.name')->label('Admitting Dr')->sortable(),
                TextColumn::make('admission_type')->label('Type')->badge()->color('success')->searchable(),
                TextColumn::make('admission_date')->label('Admitted')->dateTime()->searchable(),
                TextColumn::make('lengthOfStayDays')->label('LOS (days)')->searchable(),
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