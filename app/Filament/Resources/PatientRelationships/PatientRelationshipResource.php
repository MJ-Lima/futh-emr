<?php

namespace App\Filament\Resources\PatientRelationships;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\PatientRelationships\Pages\CreatePatientRelationship;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\PatientRelationships\Pages\EditPatientRelationship;
use App\Filament\Resources\PatientRelationships\Pages\ListPatientRelationships;
use App\Filament\Resources\PatientRelationships\Pages\ViewPatientRelationship;
use App\Filament\Resources\PatientRelationships\Schemas\PatientRelationshipForm;
use App\Filament\Resources\PatientRelationships\Schemas\PatientRelationshipInfolist;
use App\Filament\Resources\PatientRelationships\Tables\PatientRelationshipsTable;
use App\Models\PatientRelationship;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatientRelationshipResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedUsers;
    }

    public static function getNavigationGroup(): string
    {
        return 'Patient Management';
    }
    public static function getNavigationSort(): ?int
    {
        return 7;
    }
    protected static ?string $model = PatientRelationship::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PatientRelationshipForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PatientRelationshipInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatientRelationshipsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPatientRelationships::route('/'),
            'create' => CreatePatientRelationship::route('/create'),
            'view'   => ViewPatientRelationship::route('/{record}'),
            'edit'   => EditPatientRelationship::route('/{record}/edit'),
        ];
    }
}
