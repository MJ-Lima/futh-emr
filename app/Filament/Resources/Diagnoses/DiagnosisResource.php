<?php

namespace App\Filament\Resources\Diagnoses;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\Diagnoses\Pages\CreateDiagnosis;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\Diagnoses\Pages\EditDiagnosis;
use App\Filament\Resources\Diagnoses\Pages\ListDiagnoses;
use App\Filament\Resources\Diagnoses\Pages\ViewDiagnosis;
use App\Filament\Resources\Diagnoses\Schemas\DiagnosisForm;
use App\Filament\Resources\Diagnoses\Schemas\DiagnosisInfolist;
use App\Filament\Resources\Diagnoses\Tables\DiagnosesTable;
use App\Models\Diagnosis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DiagnosisResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedClipboardDocumentList;
    }

    public static function getNavigationGroup(): string
    {
        return 'Clinical';
    }
    public static function getNavigationSort(): ?int
    {
        return 8;
    }
    protected static ?string $model = Diagnosis::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DiagnosisForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DiagnosisInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiagnosesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDiagnoses::route('/'),
            'create' => CreateDiagnosis::route('/create'),
            'view'   => ViewDiagnosis::route('/{record}'),
            'edit'   => EditDiagnosis::route('/{record}/edit'),
        ];
    }
}
