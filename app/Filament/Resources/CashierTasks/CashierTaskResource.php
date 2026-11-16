<?php

namespace App\Filament\Resources\CashierTasks;

use App\Filament\Resources\CashierTasks\Pages\ListCashierTasks;
use App\Filament\Resources\CashierTasks\Pages\ViewCashierTask;
use App\Filament\Resources\CashierTasks\Schemas\CashierTaskInfolist;
use App\Filament\Resources\CashierTasks\Tables\CashierTasksTable;
use App\Models\CashierTask;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CashierTaskResource extends Resource
{
    protected static ?string $model = CashierTask::class;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedBanknotes;
    }

    public static function getNavigationLabel(): string
    {
        return 'Cashier Queue';
    }

    public static function getNavigationGroup(): string
    {
        return 'Accounts';
    }

    public static function getNavigationBadge(): ?string
    {
        $count = CashierTask::pending()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function infolist(Schema $schema): Schema
    {
        return CashierTaskInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CashierTasksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCashierTasks::route('/'),
            'view'  => ViewCashierTask::route('/{record}'),
        ];
    }
}
