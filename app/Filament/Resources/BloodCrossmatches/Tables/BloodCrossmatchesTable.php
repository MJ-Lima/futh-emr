<?php

namespace App\Filament\Resources\BloodCrossmatches\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class BloodCrossmatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('crossmatch_number')->label('XM #')->searchable(),
                    TextColumn::make('bloodRequest.request_number')->label('Request')->sortable(),
                    TextColumn::make('bloodUnit.unit_number')->label('Unit')->sortable(),
                    TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                    TextColumn::make('method')->label('Method')->badge()->color('success')->searchable(),
                    TextColumn::make('major_crossmatch')->label('Major XM')->badge()->color('success')->searchable(),
                    TextColumn::make('minor_crossmatch')->label('Minor XM')->badge()->color('success')->searchable(),
                    TextColumn::make('performed_at')->label('Performed')->dateTime()->searchable(),
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
