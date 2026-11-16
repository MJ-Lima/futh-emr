<?php

namespace App\Filament\Resources\Laboratories;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\Laboratories\Pages\CreateLaboratories;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\Laboratories\Pages\EditLaboratories;
use App\Filament\Resources\Laboratories\Pages\ListLaboratories;
use App\Filament\Resources\Laboratories\Pages\ViewLaboratories;
use App\Filament\Resources\Laboratories\Schemas\LaboratoriesForm;
use App\Filament\Resources\Laboratories\Schemas\LaboratoriesInfolist;
use App\Filament\Resources\Laboratories\Tables\LaboratoriesTable;
use App\Models\Laboratory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaboratoriesResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedBuildingLibrary;
    }

    public static function getNavigationGroup(): string
    {
        return 'Laboratory';
    }
    public static function getNavigationLabel(): string
    {
        return 'Laboratories';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    protected static ?string $model = Laboratory::class;

    public static function form(Schema $schema): Schema
    {
        return LaboratoriesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LaboratoriesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaboratoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLaboratories::route('/'),
            'create' => CreateLaboratories::route('/create'),
            'view'   => ViewLaboratories::route('/{record}'),
            'edit'   => EditLaboratories::route('/{record}/edit'),
        ];
    }
}
