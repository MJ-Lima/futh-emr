<?php

namespace App\Filament\Resources\GoodsReceivedNotes;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\GoodsReceivedNotes\Pages\CreateGoodsReceivedNote;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\GoodsReceivedNotes\Pages\EditGoodsReceivedNote;
use App\Filament\Resources\GoodsReceivedNotes\Pages\ListGoodsReceivedNotes;
use App\Filament\Resources\GoodsReceivedNotes\Pages\ViewGoodsReceivedNote;
use App\Filament\Resources\GoodsReceivedNotes\Schemas\GoodsReceivedNoteForm;
use App\Filament\Resources\GoodsReceivedNotes\Schemas\GoodsReceivedNoteInfolist;
use App\Filament\Resources\GoodsReceivedNotes\Tables\GoodsReceivedNotesTable;
use App\Models\GoodsReceivedNote;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GoodsReceivedNoteResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedTruck;
    }

    public static function getNavigationGroup(): string
    {
        return 'Pharmacy';
    }
    public static function getNavigationSort(): ?int
    {
        return 9;
    }
    protected static ?string $model = GoodsReceivedNote::class;

    protected static ?string $recordTitleAttribute = 'grn_number';

    public static function form(Schema $schema): Schema
    {
        return GoodsReceivedNoteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GoodsReceivedNoteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoodsReceivedNotesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListGoodsReceivedNotes::route('/'),
            'create' => CreateGoodsReceivedNote::route('/create'),
            'view'   => ViewGoodsReceivedNote::route('/{record}'),
            'edit'   => EditGoodsReceivedNote::route('/{record}/edit'),
        ];
    }
}
