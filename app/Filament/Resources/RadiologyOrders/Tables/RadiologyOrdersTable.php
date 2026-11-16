<?php

namespace App\Filament\Resources\RadiologyOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class RadiologyOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('order_number')->label('Order #')->searchable(),
                    TextColumn::make('patient.full_name')->label('Patient')->sortable(),
                    TextColumn::make('modality.name')->label('Modality')->sortable(),
                    TextColumn::make('examination')->label('Examination')->searchable(),
                    TextColumn::make('body_part')->label('Body Part')->searchable(),
                    TextColumn::make('ordered_at')->label('Ordered')->dateTime()->searchable(),
                    TextColumn::make('priority')->label('Priority')->badge()->color('success')->searchable(),
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
