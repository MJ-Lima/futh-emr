<?php

namespace App\Filament\Resources\BloodUnits;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\BloodUnits\Pages\CreateBloodUnit;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\BloodUnits\Pages\EditBloodUnit;
use App\Filament\Resources\BloodUnits\Pages\ListBloodUnits;
use App\Filament\Resources\BloodUnits\Pages\ViewBloodUnit;
use App\Filament\Resources\BloodUnits\Schemas\BloodUnitForm;
use App\Filament\Resources\BloodUnits\Schemas\BloodUnitInfolist;
use App\Filament\Resources\BloodUnits\Tables\BloodUnitsTable;
use App\Models\BloodUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BloodUnitResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedArchiveBox;
    }

    public static function getNavigationGroup(): string
    {
        return 'Blood Bank';
    }
    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    protected static ?string $model = BloodUnit::class;

    protected static ?string $recordTitleAttribute = 'unit_number';

    public static function form(Schema $schema): Schema
    {
        return BloodUnitForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BloodUnitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BloodUnitsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBloodUnits::route('/'),
            'create' => CreateBloodUnit::route('/create'),
            'view'   => ViewBloodUnit::route('/{record}'),
            'edit'   => EditBloodUnit::route('/{record}/edit'),
        ];
    }
}
