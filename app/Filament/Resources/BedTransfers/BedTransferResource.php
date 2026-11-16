<?php

namespace App\Filament\Resources\BedTransfers;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\BedTransfers\Pages\CreateBedTransfer;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\BedTransfers\Pages\EditBedTransfer;
use App\Filament\Resources\BedTransfers\Pages\ListBedTransfers;
use App\Filament\Resources\BedTransfers\Pages\ViewBedTransfer;
use App\Filament\Resources\BedTransfers\Schemas\BedTransferForm;
use App\Filament\Resources\BedTransfers\Schemas\BedTransferInfolist;
use App\Filament\Resources\BedTransfers\Tables\BedTransfersTable;
use App\Models\BedTransfer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BedTransferResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedArrowsRightLeft;
    }

    public static function getNavigationGroup(): string
    {
        return 'Ward & Bed Management';
    }
    public static function getNavigationSort(): ?int
    {
        return 4;
    }
    protected static ?string $model = BedTransfer::class;

    protected static ?string $recordTitleAttribute = 'transfer_number';

    public static function form(Schema $schema): Schema
    {
        return BedTransferForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BedTransferInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BedTransfersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBedTransfers::route('/'),
            'create' => CreateBedTransfer::route('/create'),
            'view'   => ViewBedTransfer::route('/{record}'),
            'edit'   => EditBedTransfer::route('/{record}/edit'),
        ];
    }
}
