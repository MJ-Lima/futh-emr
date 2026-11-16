<?php

namespace App\Filament\Resources\StoreStocks;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\StoreStocks\Pages\CreateStoreStock;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\StoreStocks\Pages\EditStoreStock;
use App\Filament\Resources\StoreStocks\Pages\ListStoreStocks;
use App\Filament\Resources\StoreStocks\Pages\ViewStoreStock;
use App\Filament\Resources\StoreStocks\Schemas\StoreStockForm;
use App\Filament\Resources\StoreStocks\Schemas\StoreStockInfolist;
use App\Filament\Resources\StoreStocks\Tables\StoreStocksTable;
use App\Models\StoreStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StoreStockResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedRectangleStack;
    }

    public static function getNavigationGroup(): string
    {
        return 'Store & Inventory';
    }
    public static function getNavigationSort(): ?int
    {
        return 4;
    }
    protected static ?string $model = StoreStock::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return StoreStockForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StoreStockInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoreStocksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListStoreStocks::route('/'),
            'create' => CreateStoreStock::route('/create'),
            'view'   => ViewStoreStock::route('/{record}'),
            'edit'   => EditStoreStock::route('/{record}/edit'),
        ];
    }
}
