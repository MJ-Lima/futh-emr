<?php

namespace App\Filament\Resources\AuditCategories;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\AuditCategories\Pages\CreateAuditCategory;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\AuditCategories\Pages\EditAuditCategory;
use App\Filament\Resources\AuditCategories\Pages\ListAuditCategories;
use App\Filament\Resources\AuditCategories\Pages\ViewAuditCategory;
use App\Filament\Resources\AuditCategories\Schemas\AuditCategoryForm;
use App\Filament\Resources\AuditCategories\Schemas\AuditCategoryInfolist;
use App\Filament\Resources\AuditCategories\Tables\AuditCategoriesTable;
use App\Models\AuditCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AuditCategoryResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedSwatch;
    }

    public static function getNavigationGroup(): string
    {
        return 'Audit';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = AuditCategory::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return AuditCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AuditCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAuditCategories::route('/'),
            'create' => CreateAuditCategory::route('/create'),
            'view'   => ViewAuditCategory::route('/{record}'),
            'edit'   => EditAuditCategory::route('/{record}/edit'),
        ];
    }
}
