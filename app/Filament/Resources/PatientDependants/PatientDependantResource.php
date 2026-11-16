<?php

namespace App\Filament\Resources\PatientDependants;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\PatientDependants\Pages\CreatePatientDependant;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\PatientDependants\Pages\EditPatientDependant;
use App\Filament\Resources\PatientDependants\Pages\ListPatientDependants;
use App\Filament\Resources\PatientDependants\Pages\ViewPatientDependant;
use App\Filament\Resources\PatientDependants\Schemas\PatientDependantForm;
use App\Filament\Resources\PatientDependants\Schemas\PatientDependantInfolist;
use App\Filament\Resources\PatientDependants\Tables\PatientDependantsTable;
use App\Models\PatientDependant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatientDependantResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedUserGroup;
    }

    public static function getNavigationGroup(): string
    {
        return 'Patient Management';
    }
    public static function getNavigationSort(): ?int
    {
        return 5;
    }
    protected static ?string $model = PatientDependant::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PatientDependantForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PatientDependantInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatientDependantsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPatientDependants::route('/'),
            'create' => CreatePatientDependant::route('/create'),
            'view'   => ViewPatientDependant::route('/{record}'),
            'edit'   => EditPatientDependant::route('/{record}/edit'),
        ];
    }
}
