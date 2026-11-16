<?php

namespace App\Filament\Resources\MedicalAuditFindings;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\MedicalAuditFindings\Pages\CreateMedicalAuditFinding;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\MedicalAuditFindings\Pages\EditMedicalAuditFinding;
use App\Filament\Resources\MedicalAuditFindings\Pages\ListMedicalAuditFindings;
use App\Filament\Resources\MedicalAuditFindings\Pages\ViewMedicalAuditFinding;
use App\Filament\Resources\MedicalAuditFindings\Schemas\MedicalAuditFindingForm;
use App\Filament\Resources\MedicalAuditFindings\Schemas\MedicalAuditFindingInfolist;
use App\Filament\Resources\MedicalAuditFindings\Tables\MedicalAuditFindingsTable;
use App\Models\MedicalAuditFinding;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MedicalAuditFindingResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedExclamationTriangle;
    }

    public static function getNavigationGroup(): string
    {
        return 'Audit';
    }
    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    protected static ?string $model = MedicalAuditFinding::class;

    protected static ?string $recordTitleAttribute = 'finding_number';

    public static function form(Schema $schema): Schema
    {
        return MedicalAuditFindingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MedicalAuditFindingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MedicalAuditFindingsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListMedicalAuditFindings::route('/'),
            'create' => CreateMedicalAuditFinding::route('/create'),
            'view'   => ViewMedicalAuditFinding::route('/{record}'),
            'edit'   => EditMedicalAuditFinding::route('/{record}/edit'),
        ];
    }
}
