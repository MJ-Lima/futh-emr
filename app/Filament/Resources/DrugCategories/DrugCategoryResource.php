<?php

namespace App\Filament\Resources\DrugCategories;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\DrugCategories\Pages\CreateDrugCategory;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\DrugCategories\Pages\EditDrugCategory;
use App\Filament\Resources\DrugCategories\Pages\ListDrugCategories;
use App\Filament\Resources\DrugCategories\Pages\ViewDrugCategory;
use App\Filament\Resources\DrugCategories\Schemas\DrugCategoryForm;
use App\Filament\Resources\DrugCategories\Schemas\DrugCategoryInfolist;
use App\Filament\Resources\DrugCategories\Tables\DrugCategoriesTable;
use App\Models\DrugCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DrugCategoryResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedTag;
    }

    public static function getNavigationGroup(): string
    {
        return 'Pharmacy';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = DrugCategory::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return DrugCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DrugCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DrugCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDrugCategories::route('/'),
            'create' => CreateDrugCategory::route('/create'),
            'view'   => ViewDrugCategory::route('/{record}'),
            'edit'   => EditDrugCategory::route('/{record}/edit'),
        ];
    }
}
