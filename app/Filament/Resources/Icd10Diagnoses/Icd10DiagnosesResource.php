<?php

namespace App\Filament\Resources\Icd10Diagnoses;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\Icd10Diagnoses\Pages\CreateIcd10Diagnosis;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\Icd10Diagnoses\Pages\EditIcd10Diagnosis;
use App\Filament\Resources\Icd10Diagnoses\Pages\ListIcd10Diagnoses;
use App\Filament\Resources\Icd10Diagnoses\Pages\ViewIcd10Diagnosis;
use App\Filament\Resources\Icd10Diagnoses\Schemas\Icd10DiagnosisForm;
use App\Filament\Resources\Icd10Diagnoses\Schemas\Icd10DiagnosisInfolist;
use App\Filament\Resources\Icd10Diagnoses\Tables\Icd10DiagnosesTable;
use App\Models\Icd10Diagnosis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class Icd10DiagnosesResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedBookOpen;
    }

    public static function getNavigationGroup(): string
    {
        return 'Administration';
    }
    public static function getNavigationLabel(): string
    {
        return 'ICD-10 Diagnoses';
    }

    public static function getNavigationSort(): ?int
    {
        return 21;
    }
    protected static ?string $model = Icd10Diagnosis::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return Icd10DiagnosisForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return Icd10DiagnosisInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return Icd10DiagnosesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListIcd10Diagnoses::route('/'),
            'create' => CreateIcd10Diagnosis::route('/create'),
            'view'   => ViewIcd10Diagnosis::route('/{record}'),
            'edit'   => EditIcd10Diagnosis::route('/{record}/edit'),
        ];
    }
}
