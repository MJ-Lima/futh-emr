<?php

namespace App\Filament\Resources\ReportSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn, TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ReportSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('name')->label('Schedule')->searchable(),
                    TextColumn::make('template.name')->label('Template')->sortable(),
                    TextColumn::make('frequency')->label('Frequency')->badge()->color('success')->searchable(),
                    TextColumn::make('run_at')->label('Run At')->searchable(),
                    TextColumn::make('last_run_at')->label('Last Run')->dateTime()->searchable(),
                    TextColumn::make('next_run_at')->label('Next Run')->dateTime()->searchable(),
                    IconColumn::make('is_active')->label('Active')->boolean(),
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
