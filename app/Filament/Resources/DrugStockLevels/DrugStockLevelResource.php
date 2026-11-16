<?php

namespace App\Filament\Resources\DrugStockLevels;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\DrugStockLevels\Pages\CreateDrugStockLevel;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\DrugStockLevels\Pages\EditDrugStockLevel;
use App\Filament\Resources\DrugStockLevels\Pages\ListDrugStockLevels;
use App\Filament\Resources\DrugStockLevels\Pages\ViewDrugStockLevel;
use App\Filament\Resources\DrugStockLevels\Schemas\DrugStockLevelForm;
use App\Filament\Resources\DrugStockLevels\Schemas\DrugStockLevelInfolist;
use App\Filament\Resources\DrugStockLevels\Tables\DrugStockLevelsTable;
use App\Models\DrugStockLevel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DrugStockLevelResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedClipboardDocumentCheck;
    }

    public static function getNavigationGroup(): string
    {
        return 'Pharmacy';
    }
    public static function getNavigationSort(): ?int
    {
        return 10;
    }
    protected static ?string $model = DrugStockLevel::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DrugStockLevelForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DrugStockLevelInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DrugStockLevelsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDrugStockLevels::route('/'),
            'create' => CreateDrugStockLevel::route('/create'),
            'view'   => ViewDrugStockLevel::route('/{record}'),
            'edit'   => EditDrugStockLevel::route('/{record}/edit'),
        ];
    }
}
