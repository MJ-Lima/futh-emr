<?php

namespace App\Filament\Resources\DispensingPoints;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\DispensingPoints\Pages\CreateDispensingPoint;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\DispensingPoints\Pages\EditDispensingPoint;
use App\Filament\Resources\DispensingPoints\Pages\ListDispensingPoints;
use App\Filament\Resources\DispensingPoints\Pages\ViewDispensingPoint;
use App\Filament\Resources\DispensingPoints\Schemas\DispensingPointForm;
use App\Filament\Resources\DispensingPoints\Schemas\DispensingPointInfolist;
use App\Filament\Resources\DispensingPoints\Tables\DispensingPointsTable;
use App\Models\DispensingPoint;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DispensingPointResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedBuildingStorefront;
    }

    public static function getNavigationGroup(): string
    {
        return 'Pharmacy';
    }
    public static function getNavigationSort(): ?int
    {
        return 6;
    }
    protected static ?string $model = DispensingPoint::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return DispensingPointForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DispensingPointInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DispensingPointsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDispensingPoints::route('/'),
            'create' => CreateDispensingPoint::route('/create'),
            'view'   => ViewDispensingPoint::route('/{record}'),
            'edit'   => EditDispensingPoint::route('/{record}/edit'),
        ];
    }
}
