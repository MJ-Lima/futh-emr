<?php

namespace App\Filament\Resources\AuditFollowUps;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\AuditFollowUps\Pages\CreateAuditFollowUp;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\AuditFollowUps\Pages\EditAuditFollowUp;
use App\Filament\Resources\AuditFollowUps\Pages\ListAuditFollowUps;
use App\Filament\Resources\AuditFollowUps\Pages\ViewAuditFollowUp;
use App\Filament\Resources\AuditFollowUps\Schemas\AuditFollowUpForm;
use App\Filament\Resources\AuditFollowUps\Schemas\AuditFollowUpInfolist;
use App\Filament\Resources\AuditFollowUps\Tables\AuditFollowUpsTable;
use App\Models\AuditFollowUp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AuditFollowUpResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedArrowPath;
    }

    public static function getNavigationGroup(): string
    {
        return 'Audit';
    }
    public static function getNavigationSort(): ?int
    {
        return 6;
    }
    protected static ?string $model = AuditFollowUp::class;

    protected static ?string $recordTitleAttribute = 'follow_up_number';

    public static function form(Schema $schema): Schema
    {
        return AuditFollowUpForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AuditFollowUpInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditFollowUpsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAuditFollowUps::route('/'),
            'create' => CreateAuditFollowUp::route('/create'),
            'view'   => ViewAuditFollowUp::route('/{record}'),
            'edit'   => EditAuditFollowUp::route('/{record}/edit'),
        ];
    }
}
