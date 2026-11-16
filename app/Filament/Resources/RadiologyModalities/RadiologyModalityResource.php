<?php

namespace App\Filament\Resources\RadiologyModalities;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\RadiologyModalities\Pages\CreateRadiologyModality;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\RadiologyModalities\Pages\EditRadiologyModality;
use App\Filament\Resources\RadiologyModalities\Pages\ListRadiologyModalities;
use App\Filament\Resources\RadiologyModalities\Pages\ViewRadiologyModality;
use App\Filament\Resources\RadiologyModalities\Schemas\RadiologyModalityForm;
use App\Filament\Resources\RadiologyModalities\Schemas\RadiologyModalityInfolist;
use App\Filament\Resources\RadiologyModalities\Tables\RadiologyModalitiesTable;
use App\Models\RadiologyModality;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RadiologyModalityResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedCamera;
    }

    public static function getNavigationGroup(): string
    {
        return 'Radiology';
    }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = RadiologyModality::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return RadiologyModalityForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RadiologyModalityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RadiologyModalitiesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRadiologyModalities::route('/'),
            'create' => CreateRadiologyModality::route('/create'),
            'view'   => ViewRadiologyModality::route('/{record}'),
            'edit'   => EditRadiologyModality::route('/{record}/edit'),
        ];
    }
}
