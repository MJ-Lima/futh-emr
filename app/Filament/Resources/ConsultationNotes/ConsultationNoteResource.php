<?php

namespace App\Filament\Resources\ConsultationNotes;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ConsultationNotes\Pages\CreateConsultationNote;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ConsultationNotes\Pages\EditConsultationNote;
use App\Filament\Resources\ConsultationNotes\Pages\ListConsultationNotes;
use App\Filament\Resources\ConsultationNotes\Pages\ViewConsultationNote;
use App\Filament\Resources\ConsultationNotes\Schemas\ConsultationNoteForm;
use App\Filament\Resources\ConsultationNotes\Schemas\ConsultationNoteInfolist;
use App\Filament\Resources\ConsultationNotes\Tables\ConsultationNotesTable;
use App\Models\ConsultationNote;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConsultationNoteResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedClipboardDocumentList;
    }

    public static function getNavigationGroup(): string
    {
        return 'Clinical';
    }
    public static function getNavigationLabel(): string
    {
        return 'Consultation Notes';
    }

    public static function getNavigationSort(): ?int
    {
        return 14;
    }
    protected static ?string $model = ConsultationNote::class;

    protected static ?string $recordTitleAttribute = 'note_number';

    public static function form(Schema $schema): Schema
    {
        return ConsultationNoteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ConsultationNoteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConsultationNotesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListConsultationNotes::route('/'),
            'create' => CreateConsultationNote::route('/create'),
            'view'   => ViewConsultationNote::route('/{record}'),
            'edit'   => EditConsultationNote::route('/{record}/edit'),
        ];
    }
}
