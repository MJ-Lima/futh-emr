<?php

namespace App\Filament\Resources\PatientInsurance;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\PatientInsurance\Pages\CreatePatientInsurance;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\PatientInsurance\Pages\EditPatientInsurance;
use App\Filament\Resources\PatientInsurance\Pages\ListPatientInsurance;
use App\Filament\Resources\PatientInsurance\Pages\ViewPatientInsurance;
use App\Filament\Resources\PatientInsurance\Schemas\PatientInsuranceForm;
use App\Filament\Resources\PatientInsurance\Schemas\PatientInsuranceInfolist;
use App\Filament\Resources\PatientInsurance\Tables\PatientInsuranceTable;
use App\Models\PatientInsurance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatientInsuranceResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedIdentification;
    }

    public static function getNavigationGroup(): string
    {
        return 'Insurance';
    }
    public static function getNavigationLabel(): string
    {
        return 'Patient Policies';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    protected static ?string $model = PatientInsurance::class;

    protected static ?string $recordTitleAttribute = 'policy_number';

    public static function form(Schema $schema): Schema
    {
        return PatientInsuranceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PatientInsuranceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatientInsuranceTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPatientInsurance::route('/'),
            'create' => CreatePatientInsurance::route('/create'),
            'view'   => ViewPatientInsurance::route('/{record}'),
            'edit'   => EditPatientInsurance::route('/{record}/edit'),
        ];
    }
}
