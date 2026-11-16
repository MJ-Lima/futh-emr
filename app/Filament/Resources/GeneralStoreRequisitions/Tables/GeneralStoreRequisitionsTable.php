<?php
namespace App\Filament\Resources\GeneralStoreRequisitions\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
class GeneralStoreRequisitionsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
                    TextColumn::make('requisition_number')->label('Req #')->searchable(),
                    TextColumn::make('requestingDepartment.name')->label('Dept')->sortable(),
                    TextColumn::make('sourceLocation.name')->label('From Store')->sortable(),
                    TextColumn::make('required_date')->label('Required')->date()->searchable(),
                    TextColumn::make('priority')->label('Priority')->badge()->color('success')->searchable(),
                    TextColumn::make('status')->label('Status')->badge()->color('success')->searchable(),
                    TextColumn::make('requester.name')->label('By')->sortable(),
        ])->filters([])->recordActions([ViewAction::make(), EditAction::make()], position: RecordActionsPosition::BeforeColumns)->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
