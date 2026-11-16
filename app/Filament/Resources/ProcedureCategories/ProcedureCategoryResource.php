<?php

namespace App\Filament\Resources\ProcedureCategories;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ProcedureCategories\Pages\CreateProcedureCategory;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ProcedureCategories\Pages\EditProcedureCategory;
use App\Filament\Resources\ProcedureCategories\Pages\ListProcedureCategories;
use App\Filament\Resources\ProcedureCategories\Pages\ViewProcedureCategory;
use App\Filament\Resources\ProcedureCategories\Schemas\ProcedureCategoryForm;
use App\Filament\Resources\ProcedureCategories\Schemas\ProcedureCategoryInfolist;
use App\Filament\Resources\ProcedureCategories\Tables\ProcedureCategoriesTable;
use App\Models\ProcedureCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProcedureCategoryResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedSwatch;
    }

    public static function getNavigationGroup(): string
    {
        return 'Procedures';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = ProcedureCategory::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return ProcedureCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProcedureCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProcedureCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListProcedureCategories::route('/'),
            'create' => CreateProcedureCategory::route('/create'),
            'view'   => ViewProcedureCategory::route('/{record}'),
            'edit'   => EditProcedureCategory::route('/{record}/edit'),
        ];
    }
}
