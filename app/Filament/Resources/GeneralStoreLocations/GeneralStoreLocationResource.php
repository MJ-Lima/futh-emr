<?php

namespace App\Filament\Resources\GeneralStoreLocations;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\GeneralStoreLocations\Pages\CreateGeneralStoreLocation;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\GeneralStoreLocations\Pages\EditGeneralStoreLocation;
use App\Filament\Resources\GeneralStoreLocations\Pages\ListGeneralStoreLocations;
use App\Filament\Resources\GeneralStoreLocations\Pages\ViewGeneralStoreLocation;
use App\Filament\Resources\GeneralStoreLocations\Schemas\GeneralStoreLocationForm;
use App\Filament\Resources\GeneralStoreLocations\Schemas\GeneralStoreLocationInfolist;
use App\Filament\Resources\GeneralStoreLocations\Tables\GeneralStoreLocationsTable;
use App\Models\GeneralStoreLocation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GeneralStoreLocationResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedMapPin;
    }

    public static function getNavigationGroup(): string
    {
        return 'General Store';
    }
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    protected static ?string $model = GeneralStoreLocation::class;

    protected static ?string $recordTitleAttribute = 'code';
    public static function form(Schema $schema): Schema { return GeneralStoreLocationForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return GeneralStoreLocationInfolist::configure($schema); }
    public static function table(Table $table): Table { return GeneralStoreLocationsTable::configure($table); }
    public static function getPages(): array { return ['index' => ListGeneralStoreLocations::route('/'), 'create' => CreateGeneralStoreLocation::route('/create'), 'view' => ViewGeneralStoreLocation::route('/{record}'), 'edit' => EditGeneralStoreLocation::route('/{record}/edit')]; }
}
