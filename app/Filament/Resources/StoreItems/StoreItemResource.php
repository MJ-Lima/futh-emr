<?php

namespace App\Filament\Resources\StoreItems;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\StoreItems\Pages\CreateStoreItem;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\StoreItems\Pages\EditStoreItem;
use App\Filament\Resources\StoreItems\Pages\ListStoreItems;
use App\Filament\Resources\StoreItems\Pages\ViewStoreItem;
use App\Filament\Resources\StoreItems\Schemas\StoreItemForm;
use App\Filament\Resources\StoreItems\Schemas\StoreItemInfolist;
use App\Filament\Resources\StoreItems\Tables\StoreItemsTable;
use App\Models\StoreItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StoreItemResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedCube;
    }

    public static function getNavigationGroup(): string
    {
        return 'Store & Inventory';
    }
    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    protected static ?string $model = StoreItem::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return StoreItemForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StoreItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoreItemsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListStoreItems::route('/'),
            'create' => CreateStoreItem::route('/create'),
            'view'   => ViewStoreItem::route('/{record}'),
            'edit'   => EditStoreItem::route('/{record}/edit'),
        ];
    }
}
