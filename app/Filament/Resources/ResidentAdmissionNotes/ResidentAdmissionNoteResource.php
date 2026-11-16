<?php

namespace App\Filament\Resources\ResidentAdmissionNotes;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ResidentAdmissionNotes\Pages\CreateResidentAdmissionNote;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ResidentAdmissionNotes\Pages\EditResidentAdmissionNote;
use App\Filament\Resources\ResidentAdmissionNotes\Pages\ListResidentAdmissionNotes;
use App\Filament\Resources\ResidentAdmissionNotes\Pages\ViewResidentAdmissionNote;
use App\Filament\Resources\ResidentAdmissionNotes\Schemas\ResidentAdmissionNoteForm;
use App\Filament\Resources\ResidentAdmissionNotes\Schemas\ResidentAdmissionNoteInfolist;
use App\Filament\Resources\ResidentAdmissionNotes\Tables\ResidentAdmissionNotesTable;
use App\Models\ResidentAdmissionNote;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ResidentAdmissionNoteResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedClipboardDocumentCheck;
    }

    public static function getNavigationGroup(): string
    {
        return 'Inpatient';
    }
    public static function getNavigationLabel(): string
    {
        return 'Resident Admission Notes';
    }

    public static function getNavigationSort(): ?int
    {
        return 6;
    }
    protected static ?string $model = ResidentAdmissionNote::class;

    protected static ?string $recordTitleAttribute = 'note_number';

    public static function form(Schema $schema): Schema
    {
        return ResidentAdmissionNoteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ResidentAdmissionNoteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResidentAdmissionNotesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListResidentAdmissionNotes::route('/'),
            'create' => CreateResidentAdmissionNote::route('/create'),
            'view'   => ViewResidentAdmissionNote::route('/{record}'),
            'edit'   => EditResidentAdmissionNote::route('/{record}/edit'),
        ];
    }
}
