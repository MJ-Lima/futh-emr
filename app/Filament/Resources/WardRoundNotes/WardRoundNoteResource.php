<?php

namespace App\Filament\Resources\WardRoundNotes;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\WardRoundNotes\Pages\CreateWardRoundNote;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\WardRoundNotes\Pages\EditWardRoundNote;
use App\Filament\Resources\WardRoundNotes\Pages\ListWardRoundNotes;
use App\Filament\Resources\WardRoundNotes\Pages\ViewWardRoundNote;
use App\Filament\Resources\WardRoundNotes\Schemas\WardRoundNoteForm;
use App\Filament\Resources\WardRoundNotes\Schemas\WardRoundNoteInfolist;
use App\Filament\Resources\WardRoundNotes\Tables\WardRoundNotesTable;
use App\Models\WardRoundNote;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WardRoundNoteResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedClipboardDocumentList;
    }

    public static function getNavigationGroup(): string
    {
        return 'Inpatient';
    }
    public static function getNavigationLabel(): string
    {
        return 'Ward Round Notes';
    }

    public static function getNavigationSort(): ?int
    {
        return 7;
    }
    protected static ?string $model = WardRoundNote::class;

    protected static ?string $recordTitleAttribute = 'note_number';

    public static function form(Schema $schema): Schema
    {
        return WardRoundNoteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WardRoundNoteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WardRoundNotesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListWardRoundNotes::route('/'),
            'create' => CreateWardRoundNote::route('/create'),
            'view'   => ViewWardRoundNote::route('/{record}'),
            'edit'   => EditWardRoundNote::route('/{record}/edit'),
        ];
    }
}
