<?php

namespace App\Filament\Resources\InternalAudits;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\InternalAudits\Pages\CreateInternalAudit;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\InternalAudits\Pages\EditInternalAudit;
use App\Filament\Resources\InternalAudits\Pages\ListInternalAudits;
use App\Filament\Resources\InternalAudits\Pages\ViewInternalAudit;
use App\Filament\Resources\InternalAudits\Schemas\InternalAuditForm;
use App\Filament\Resources\InternalAudits\Schemas\InternalAuditInfolist;
use App\Filament\Resources\InternalAudits\Tables\InternalAuditsTable;
use App\Models\InternalAudit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InternalAuditResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedShieldCheck;
    }

    public static function getNavigationGroup(): string
    {
        return 'Audit';
    }
    public static function getNavigationSort(): ?int
    {
        return 4;
    }
    protected static ?string $model = InternalAudit::class;

    protected static ?string $recordTitleAttribute = 'audit_number';

    public static function form(Schema $schema): Schema
    {
        return InternalAuditForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InternalAuditInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InternalAuditsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListInternalAudits::route('/'),
            'create' => CreateInternalAudit::route('/create'),
            'view'   => ViewInternalAudit::route('/{record}'),
            'edit'   => EditInternalAudit::route('/{record}/edit'),
        ];
    }
}
