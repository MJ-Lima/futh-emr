<?php

namespace App\Filament\Resources\LabUnits;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\LabUnits\Pages\CreateLabUnits;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\LabUnits\Pages\EditLabUnits;
use App\Filament\Resources\LabUnits\Pages\ListLabUnits;
use App\Filament\Resources\LabUnits\Pages\ViewLabUnits;
use App\Filament\Resources\LabUnits\Schemas\LabUnitsForm;
use App\Filament\Resources\LabUnits\Schemas\LabUnitsInfolist;
use App\Filament\Resources\LabUnits\Tables\LabUnitsTable;
use App\Models\LabUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LabUnitsResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedSquare3Stack3d;
    }

    public static function getNavigationGroup(): string
    {
        return 'Laboratory';
    }
    public static function getNavigationLabel(): string
    {
        return 'Lab Units';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    protected static ?string $model = LabUnit::class;

    public static function form(Schema $schema): Schema
    {
        return LabUnitsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LabUnitsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LabUnitsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLabUnits::route('/'),
            'create' => CreateLabUnits::route('/create'),
            'view'   => ViewLabUnits::route('/{record}'),
            'edit'   => EditLabUnits::route('/{record}/edit'),
        ];
    }
}
