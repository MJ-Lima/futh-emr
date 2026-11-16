<?php

namespace App\Filament\Resources\StoreRequisitions;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\StoreRequisitions\Pages\CreateStoreRequisition;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\StoreRequisitions\Pages\EditStoreRequisition;
use App\Filament\Resources\StoreRequisitions\Pages\ListStoreRequisitions;
use App\Filament\Resources\StoreRequisitions\Pages\ViewStoreRequisition;
use App\Filament\Resources\StoreRequisitions\Schemas\StoreRequisitionForm;
use App\Filament\Resources\StoreRequisitions\Schemas\StoreRequisitionInfolist;
use App\Filament\Resources\StoreRequisitions\Tables\StoreRequisitionsTable;
use App\Models\StoreRequisition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StoreRequisitionResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedClipboardDocumentList;
    }

    public static function getNavigationGroup(): string
    {
        return 'Store & Inventory';
    }
    public static function getNavigationSort(): ?int
    {
        return 6;
    }
    protected static ?string $model = StoreRequisition::class;

    protected static ?string $recordTitleAttribute = 'requisition_number';

    public static function form(Schema $schema): Schema
    {
        return StoreRequisitionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StoreRequisitionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoreRequisitionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListStoreRequisitions::route('/'),
            'create' => CreateStoreRequisition::route('/create'),
            'view'   => ViewStoreRequisition::route('/{record}'),
            'edit'   => EditStoreRequisition::route('/{record}/edit'),
        ];
    }
}
