<?php

namespace App\Filament\Resources\SavedReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class SavedReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('report_number')->label('Report #')->searchable(),
                    TextColumn::make('title')->label('Title')->searchable(),
                    TextColumn::make('category')->label('Category')->badge()->color('success')->searchable(),
                    TextColumn::make('period_from')->label('From')->date()->searchable(),
                    TextColumn::make('period_to')->label('To')->date()->searchable(),
                    TextColumn::make('record_count')->label('Records')->searchable(),
                    TextColumn::make('format')->label('Format')->badge()->color('success')->searchable(),
                    TextColumn::make('status')->label('Status')->badge()->color('success')->searchable(),
                    TextColumn::make('generated_at')->label('Generated')->dateTime()->searchable(),
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
