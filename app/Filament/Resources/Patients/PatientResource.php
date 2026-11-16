<?php

namespace App\Filament\Resources\Patients;

use App\Filament\Traits\HasRoleBasedAccess;
use App\Filament\Resources\Patients\Pages\{CreatePatient, EditPatient, ListPatients, ViewPatient};
use App\Filament\Traits\HasUnitScoping;
use App\Models\Patient;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\Patients\Tables\PatientsTable;
use App\Filament\Resources\Patients\Schemas\PatientForm;
use App\Filament\Resources\Patients\Schemas\PatientInfolist;
use BackedEnum;

class PatientResource extends Resource
{
    use HasRoleBasedAccess;
    use HasUnitScoping;

    protected static ?string $model = Patient::class;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedUserGroup;
    }

    public static function form(Schema $schema): Schema
    {
        return PatientForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PatientInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatientsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatients::route('/'),
            'create' => CreatePatient::route('/create'),
            'view' => ViewPatient::route('/{record}'),
            'edit' => EditPatient::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole([
            'super_admin', 'medical_administrator',
            'head_of_department', 'head_of_unit', 'head_of_records',
            'medical_records_officer',
            'doctor', 'nurse', 'pharmacist',
            'laboratory_scientist', 'radiographer',
            'accountant', 'biller', 'cashier',
        ]) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole([
            'super_admin',
            'medical_administrator',
            'head_of_records',
            'medical_records_officer',
            'head_of_department',
        ]) ?? false;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole([
            'super_admin', 'medical_administrator',
            'head_of_department', 'head_of_unit', 'head_of_records',
            'medical_records_officer',
            'doctor', 'nurse', 'pharmacist',
            'laboratory_scientist', 'radiographer',
            'accountant', 'biller', 'cashier',
        ]) ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasRole([
            'super_admin', 'medical_administrator',
            'head_of_records', 'medical_records_officer',
            'head_of_department',
        ]) ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasRole([
            'super_admin', 'medical_administrator',
        ]) ?? false;
    }
}