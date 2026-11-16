<?php

namespace App\Filament\Resources\AppointmentSlots\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class AppointmentSlotsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('slot_date')->label('Date')->date()->searchable(),
                    TextColumn::make('clinic.name')->label('Clinic')->sortable(),
                    TextColumn::make('provider.name')->label('Provider')->sortable(),
                    TextColumn::make('appointmentType.name')->label('Type')->sortable(),
                    TextColumn::make('start_time')->label('Start')->searchable(),
                    TextColumn::make('end_time')->label('End')->searchable(),
                    TextColumn::make('duration_minutes')->label('Min')->searchable(),
                    TextColumn::make('booked_count')->label('Booked')->searchable(),
                    TextColumn::make('capacity')->label('Capacity')->searchable(),
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
