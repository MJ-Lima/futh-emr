<?php

namespace App\Filament\Resources\StoreCategories;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\StoreCategories\Pages\CreateStoreCategory;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\StoreCategories\Pages\EditStoreCategory;
use App\Filament\Resources\StoreCategories\Pages\ListStoreCategories;
use App\Filament\Resources\StoreCategories\Pages\ViewStoreCategory;
use App\Filament\Resources\StoreCategories\Schemas\StoreCategoryForm;
use App\Filament\Resources\StoreCategories\Schemas\StoreCategoryInfolist;
use App\Filament\Resources\StoreCategories\Tables\StoreCategoriesTable;
use App\Models\StoreCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StoreCategoryResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedTag;
    }

    public static function getNavigationGroup(): string
    {
        return 'Store & Inventory';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = StoreCategory::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return StoreCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StoreCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoreCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListStoreCategories::route('/'),
            'create' => CreateStoreCategory::route('/create'),
            'view'   => ViewStoreCategory::route('/{record}'),
            'edit'   => EditStoreCategory::route('/{record}/edit'),
        ];
    }
}
