<?php

namespace App\Filament\Resources\ConsultationRooms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ConsultationRoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Code')->badge()->color('info')->searchable(),
                TextColumn::make('name')->label('Room')->searchable()->weight('semibold'),
                TextColumn::make('clinic.name')->label('Clinic')->sortable(),
                TextColumn::make('assignedDoctor.name')->label('Default Doctor')->placeholder('—'),
                TextColumn::make('capacity')->label('Cap.')->badge(),
                TextColumn::make('current_load')->label('Current Load')
                    ->state(fn ($r) => "{$r->current_load}/{$r->capacity}")
                    ->badge()
                    ->color(fn ($r) => $r->is_available ? 'success' : 'warning'),
                TextColumn::make('is_active')->label('Active')->badge()
                    ->color(fn ($s) => $s ? 'success' : 'danger'),
            ])
            ->filters([
                SelectFilter::make('clinic_id')->label('Clinic')->relationship('clinic', 'name'),
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
