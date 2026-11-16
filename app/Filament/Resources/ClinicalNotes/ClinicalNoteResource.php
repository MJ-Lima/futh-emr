<?php

namespace App\Filament\Resources\ClinicalNotes;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ClinicalNotes\Pages\CreateClinicalNote;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ClinicalNotes\Pages\EditClinicalNote;
use App\Filament\Resources\ClinicalNotes\Pages\ListClinicalNotes;
use App\Filament\Resources\ClinicalNotes\Pages\ViewClinicalNote;
use App\Filament\Resources\ClinicalNotes\Schemas\ClinicalNoteForm;
use App\Filament\Resources\ClinicalNotes\Schemas\ClinicalNoteInfolist;
use App\Filament\Resources\ClinicalNotes\Tables\ClinicalNotesTable;
use App\Models\ClinicalNote;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClinicalNoteResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedDocumentText;
    }

    public static function getNavigationGroup(): string
    {
        return 'Clinical';
    }
    public static function getNavigationSort(): ?int
    {
        return 6;
    }
    protected static ?string $model = ClinicalNote::class;

    protected static ?string $recordTitleAttribute = 'note_number';

    public static function form(Schema $schema): Schema
    {
        return ClinicalNoteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClinicalNoteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClinicalNotesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListClinicalNotes::route('/'),
            'create' => CreateClinicalNote::route('/create'),
            'view'   => ViewClinicalNote::route('/{record}'),
            'edit'   => EditClinicalNote::route('/{record}/edit'),
        ];
    }
}
