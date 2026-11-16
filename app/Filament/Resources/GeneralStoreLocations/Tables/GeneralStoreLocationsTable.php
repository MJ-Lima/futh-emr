<?php
namespace App\Filament\Resources\GeneralStoreLocations\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
class GeneralStoreLocationsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
                    TextColumn::make('code')->label('Code')->searchable(),
                    TextColumn::make('name')->label('Location')->searchable(),
                    TextColumn::make('type')->label('Type')->badge()->color('success')->searchable(),
                    TextColumn::make('department.name')->label('Department')->sortable(),
                    TextColumn::make('building')->label('Building')->searchable(),
                    TextColumn::make('floor')->label('Floor')->searchable(),
                    TextColumn::make('inCharge.name')->label('In-Charge')->sortable(),
                    IconColumn::make('is_active')->label('Active')->boolean(),
        ])->filters([])->recordActions([ViewAction::make(), EditAction::make()], position: RecordActionsPosition::BeforeColumns)->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
