<?php

namespace App\Filament\Resources\GeneralStoreItems;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\GeneralStoreItems\Pages\CreateGeneralStoreItem;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\GeneralStoreItems\Pages\EditGeneralStoreItem;
use App\Filament\Resources\GeneralStoreItems\Pages\ListGeneralStoreItems;
use App\Filament\Resources\GeneralStoreItems\Pages\ViewGeneralStoreItem;
use App\Filament\Resources\GeneralStoreItems\Schemas\GeneralStoreItemForm;
use App\Filament\Resources\GeneralStoreItems\Schemas\GeneralStoreItemInfolist;
use App\Filament\Resources\GeneralStoreItems\Tables\GeneralStoreItemsTable;
use App\Models\GeneralStoreItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GeneralStoreItemResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedCube;
    }

    public static function getNavigationGroup(): string
    {
        return 'General Store';
    }
    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    protected static ?string $model = GeneralStoreItem::class;

    protected static ?string $recordTitleAttribute = 'code';
    public static function form(Schema $schema): Schema { return GeneralStoreItemForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return GeneralStoreItemInfolist::configure($schema); }
    public static function table(Table $table): Table { return GeneralStoreItemsTable::configure($table); }
    public static function getPages(): array { return ['index' => ListGeneralStoreItems::route('/'), 'create' => CreateGeneralStoreItem::route('/create'), 'view' => ViewGeneralStoreItem::route('/{record}'), 'edit' => EditGeneralStoreItem::route('/{record}/edit')]; }
}
