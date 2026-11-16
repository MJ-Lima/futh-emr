<?php

namespace App\Filament\Resources\GeneralStoreRequisitions;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\GeneralStoreRequisitions\Pages\CreateGeneralStoreRequisition;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\GeneralStoreRequisitions\Pages\EditGeneralStoreRequisition;
use App\Filament\Resources\GeneralStoreRequisitions\Pages\ListGeneralStoreRequisitions;
use App\Filament\Resources\GeneralStoreRequisitions\Pages\ViewGeneralStoreRequisition;
use App\Filament\Resources\GeneralStoreRequisitions\Schemas\GeneralStoreRequisitionForm;
use App\Filament\Resources\GeneralStoreRequisitions\Schemas\GeneralStoreRequisitionInfolist;
use App\Filament\Resources\GeneralStoreRequisitions\Tables\GeneralStoreRequisitionsTable;
use App\Models\GeneralStoreRequisition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GeneralStoreRequisitionResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedClipboardDocumentList;
    }

    public static function getNavigationGroup(): string
    {
        return 'General Store';
    }
    public static function getNavigationSort(): ?int
    {
        return 6;
    }
    protected static ?string $model = GeneralStoreRequisition::class;

    protected static ?string $recordTitleAttribute = 'requisition_number';
    public static function form(Schema $schema): Schema { return GeneralStoreRequisitionForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return GeneralStoreRequisitionInfolist::configure($schema); }
    public static function table(Table $table): Table { return GeneralStoreRequisitionsTable::configure($table); }
    public static function getPages(): array { return ['index' => ListGeneralStoreRequisitions::route('/'), 'create' => CreateGeneralStoreRequisition::route('/create'), 'view' => ViewGeneralStoreRequisition::route('/{record}'), 'edit' => EditGeneralStoreRequisition::route('/{record}/edit')]; }
}
