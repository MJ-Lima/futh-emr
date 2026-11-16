<?php

namespace App\Filament\Resources\ClinicalNotes\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class ClinicalNoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ClinicalNote Information')
                    ->schema([
                        TextEntry::make('note_number')->label('Note #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('note_type')->label('Type'),
                        TextEntry::make('title')->label('Title'),
                        TextEntry::make('is_signed')->label('Signed'),
                        TextEntry::make('author.name')->label('Author'),
                        TextEntry::make('signed_at')->label('Signed At')->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }
}
