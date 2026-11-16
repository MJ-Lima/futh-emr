<?php

namespace App\Filament\Resources\LabTransfers;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\LabTransfers\Pages\CreateLabTransfers;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\LabTransfers\Pages\EditLabTransfers;
use App\Filament\Resources\LabTransfers\Pages\ListLabTransfers;
use App\Filament\Resources\LabTransfers\Pages\ViewLabTransfers;
use App\Filament\Resources\LabTransfers\Schemas\LabTransfersForm;
use App\Filament\Resources\LabTransfers\Schemas\LabTransfersInfolist;
use App\Filament\Resources\LabTransfers\Tables\LabTransfersTable;
use App\Models\LabTransfer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LabTransfersResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedArrowsRightLeft;
    }

    public static function getNavigationGroup(): string
    {
        return 'Laboratory';
    }
    public static function getNavigationLabel(): string
    {
        return 'Lab Transfers';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }
    protected static ?string $model = LabTransfer::class;

    public static function form(Schema $schema): Schema
    {
        return LabTransfersForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LabTransfersInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LabTransfersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLabTransfers::route('/'),
            'create' => CreateLabTransfers::route('/create'),
            'view'   => ViewLabTransfers::route('/{record}'),
            'edit'   => EditLabTransfers::route('/{record}/edit'),
        ];
    }
}
