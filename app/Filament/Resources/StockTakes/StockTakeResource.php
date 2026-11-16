<?php

namespace App\Filament\Resources\StockTakes;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\StockTakes\Pages\CreateStockTake;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\StockTakes\Pages\EditStockTake;
use App\Filament\Resources\StockTakes\Pages\ListStockTakes;
use App\Filament\Resources\StockTakes\Pages\ViewStockTake;
use App\Filament\Resources\StockTakes\Schemas\StockTakeForm;
use App\Filament\Resources\StockTakes\Schemas\StockTakeInfolist;
use App\Filament\Resources\StockTakes\Tables\StockTakesTable;
use App\Models\StockTake;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockTakeResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedClipboardDocumentCheck;
    }

    public static function getNavigationGroup(): string
    {
        return 'Store & Inventory';
    }
    public static function getNavigationSort(): ?int
    {
        return 7;
    }
    protected static ?string $model = StockTake::class;

    protected static ?string $recordTitleAttribute = 'stock_take_number';

    public static function form(Schema $schema): Schema
    {
        return StockTakeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StockTakeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockTakesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListStockTakes::route('/'),
            'create' => CreateStockTake::route('/create'),
            'view'   => ViewStockTake::route('/{record}'),
            'edit'   => EditStockTake::route('/{record}/edit'),
        ];
    }
}
