<?php
namespace App\Filament\Resources\GeneralStoreMovements\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
class GeneralStoreMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
                    TextColumn::make('movement_number')->label('Movement #')->searchable(),
                    TextColumn::make('item.name')->label('Item')->sortable(),
                    TextColumn::make('type')->label('Type')->badge()->color('success')->searchable(),
                    TextColumn::make('fromLocation.name')->label('From')->sortable(),
                    TextColumn::make('toLocation.name')->label('To')->sortable(),
                    TextColumn::make('quantity')->label('Quantity')->searchable(),
                    TextColumn::make('movement_date')->label('Date')->dateTime()->searchable(),
                    TextColumn::make('user.name')->label('By')->sortable(),
        ])->filters([])->recordActions([ViewAction::make(), EditAction::make()], position: RecordActionsPosition::BeforeColumns)->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
