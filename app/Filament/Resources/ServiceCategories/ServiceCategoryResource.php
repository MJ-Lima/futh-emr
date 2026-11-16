<?php

namespace App\Filament\Resources\ServiceCategories;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ServiceCategories\Pages\CreateServiceCategory;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ServiceCategories\Pages\EditServiceCategory;
use App\Filament\Resources\ServiceCategories\Pages\ListServiceCategories;
use App\Filament\Resources\ServiceCategories\Pages\ViewServiceCategory;
use App\Filament\Resources\ServiceCategories\Schemas\ServiceCategoryForm;
use App\Filament\Resources\ServiceCategories\Schemas\ServiceCategoryInfolist;
use App\Filament\Resources\ServiceCategories\Tables\ServiceCategoriesTable;
use App\Models\ServiceCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceCategoryResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedSwatch;
    }

    public static function getNavigationGroup(): string
    {
        return 'Billing & Finance';
    }
    public static function getNavigationSort(): ?int
    {
        return 0;
    }
    protected static ?string $model = ServiceCategory::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return ServiceCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListServiceCategories::route('/'),
            'create' => CreateServiceCategory::route('/create'),
            'view'   => ViewServiceCategory::route('/{record}'),
            'edit'   => EditServiceCategory::route('/{record}/edit'),
        ];
    }
}
