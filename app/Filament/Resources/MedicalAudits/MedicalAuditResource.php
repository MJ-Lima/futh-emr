<?php

namespace App\Filament\Resources\MedicalAudits;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\MedicalAudits\Pages\CreateMedicalAudit;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\MedicalAudits\Pages\EditMedicalAudit;
use App\Filament\Resources\MedicalAudits\Pages\ListMedicalAudits;
use App\Filament\Resources\MedicalAudits\Pages\ViewMedicalAudit;
use App\Filament\Resources\MedicalAudits\Schemas\MedicalAuditForm;
use App\Filament\Resources\MedicalAudits\Schemas\MedicalAuditInfolist;
use App\Filament\Resources\MedicalAudits\Tables\MedicalAuditsTable;
use App\Models\MedicalAudit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MedicalAuditResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedClipboardDocumentCheck;
    }

    public static function getNavigationGroup(): string
    {
        return 'Audit';
    }
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    protected static ?string $model = MedicalAudit::class;

    protected static ?string $recordTitleAttribute = 'audit_number';

    public static function form(Schema $schema): Schema
    {
        return MedicalAuditForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MedicalAuditInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MedicalAuditsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListMedicalAudits::route('/'),
            'create' => CreateMedicalAudit::route('/create'),
            'view'   => ViewMedicalAudit::route('/{record}'),
            'edit'   => EditMedicalAudit::route('/{record}/edit'),
        ];
    }
}
