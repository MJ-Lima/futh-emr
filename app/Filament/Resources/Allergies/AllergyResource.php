<?php

namespace App\Filament\Resources\Allergies;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\Allergies\Pages\CreateAllergy;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\Allergies\Pages\EditAllergy;
use App\Filament\Resources\Allergies\Pages\ListAllergies;
use App\Filament\Resources\Allergies\Pages\ViewAllergy;
use App\Filament\Resources\Allergies\Schemas\AllergyForm;
use App\Filament\Resources\Allergies\Schemas\AllergyInfolist;
use App\Filament\Resources\Allergies\Tables\AllergiesTable;
use App\Models\Allergy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AllergyResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedExclamationTriangle;
    }

    public static function getNavigationGroup(): string
    {
        return 'Clinical';
    }
    public static function getNavigationSort(): ?int
    {
        return 7;
    }
    protected static ?string $model = Allergy::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AllergyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AllergyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AllergiesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAllergies::route('/'),
            'create' => CreateAllergy::route('/create'),
            'view'   => ViewAllergy::route('/{record}'),
            'edit'   => EditAllergy::route('/{record}/edit'),
        ];
    }
}
