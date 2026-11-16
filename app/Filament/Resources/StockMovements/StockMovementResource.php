<?php

namespace App\Filament\Resources\StockMovements;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\StockMovements\Pages\CreateStockMovement;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\StockMovements\Pages\EditStockMovement;
use App\Filament\Resources\StockMovements\Pages\ListStockMovements;
use App\Filament\Resources\StockMovements\Pages\ViewStockMovement;
use App\Filament\Resources\StockMovements\Schemas\StockMovementForm;
use App\Filament\Resources\StockMovements\Schemas\StockMovementInfolist;
use App\Filament\Resources\StockMovements\Tables\StockMovementsTable;
use App\Models\StockMovement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockMovementResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedArrowsRightLeft;
    }

    public static function getNavigationGroup(): string
    {
        return 'Pharmacy';
    }
    public static function getNavigationSort(): ?int
    {
        return 7;
    }
    protected static ?string $model = StockMovement::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return StockMovementForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StockMovementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockMovementsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListStockMovements::route('/'),
            'create' => CreateStockMovement::route('/create'),
            'view'   => ViewStockMovement::route('/{record}'),
            'edit'   => EditStockMovement::route('/{record}/edit'),
        ];
    }
}
