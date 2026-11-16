<?php

namespace App\Filament\Resources\InternalAuditFindings;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\InternalAuditFindings\Pages\CreateInternalAuditFinding;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\InternalAuditFindings\Pages\EditInternalAuditFinding;
use App\Filament\Resources\InternalAuditFindings\Pages\ListInternalAuditFindings;
use App\Filament\Resources\InternalAuditFindings\Pages\ViewInternalAuditFinding;
use App\Filament\Resources\InternalAuditFindings\Schemas\InternalAuditFindingForm;
use App\Filament\Resources\InternalAuditFindings\Schemas\InternalAuditFindingInfolist;
use App\Filament\Resources\InternalAuditFindings\Tables\InternalAuditFindingsTable;
use App\Models\InternalAuditFinding;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InternalAuditFindingResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedExclamationCircle;
    }

    public static function getNavigationGroup(): string
    {
        return 'Audit';
    }
    public static function getNavigationSort(): ?int
    {
        return 5;
    }
    protected static ?string $model = InternalAuditFinding::class;

    protected static ?string $recordTitleAttribute = 'finding_number';

    public static function form(Schema $schema): Schema
    {
        return InternalAuditFindingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InternalAuditFindingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InternalAuditFindingsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListInternalAuditFindings::route('/'),
            'create' => CreateInternalAuditFinding::route('/create'),
            'view'   => ViewInternalAuditFinding::route('/{record}'),
            'edit'   => EditInternalAuditFinding::route('/{record}/edit'),
        ];
    }
}
