<?php

namespace App\Filament\Resources\GeneralStoreCategories;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\GeneralStoreCategories\Pages\CreateGeneralStoreCategory;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\GeneralStoreCategories\Pages\EditGeneralStoreCategory;
use App\Filament\Resources\GeneralStoreCategories\Pages\ListGeneralStoreCategories;
use App\Filament\Resources\GeneralStoreCategories\Pages\ViewGeneralStoreCategory;
use App\Filament\Resources\GeneralStoreCategories\Schemas\GeneralStoreCategoryForm;
use App\Filament\Resources\GeneralStoreCategories\Schemas\GeneralStoreCategoryInfolist;
use App\Filament\Resources\GeneralStoreCategories\Tables\GeneralStoreCategoriesTable;
use App\Models\GeneralStoreCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GeneralStoreCategoryResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedTag;
    }

    public static function getNavigationGroup(): string
    {
        return 'General Store';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = GeneralStoreCategory::class;

    protected static ?string $recordTitleAttribute = 'code';
    public static function form(Schema $schema): Schema { return GeneralStoreCategoryForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return GeneralStoreCategoryInfolist::configure($schema); }
    public static function table(Table $table): Table { return GeneralStoreCategoriesTable::configure($table); }
    public static function getPages(): array { return ['index' => ListGeneralStoreCategories::route('/'), 'create' => CreateGeneralStoreCategory::route('/create'), 'view' => ViewGeneralStoreCategory::route('/{record}'), 'edit' => EditGeneralStoreCategory::route('/{record}/edit')]; }
}
