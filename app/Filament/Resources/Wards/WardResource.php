<?php

namespace App\Filament\Resources\Wards;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\Wards\Pages\CreateWard;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\Wards\Pages\EditWard;
use App\Filament\Resources\Wards\Pages\ListWards;
use App\Filament\Resources\Wards\Pages\ViewWard;
use App\Filament\Resources\Wards\Schemas\WardForm;
use App\Filament\Resources\Wards\Schemas\WardInfolist;
use App\Filament\Resources\Wards\Tables\WardsTable;
use App\Models\Ward;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WardResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedBuildingOffice2;
    }

    public static function getNavigationGroup(): string
    {
        return 'Ward & Bed Management';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = Ward::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return WardForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WardInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WardsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListWards::route('/'),
            'create' => CreateWard::route('/create'),
            'view'   => ViewWard::route('/{record}'),
            'edit'   => EditWard::route('/{record}/edit'),
        ];
    }
}
