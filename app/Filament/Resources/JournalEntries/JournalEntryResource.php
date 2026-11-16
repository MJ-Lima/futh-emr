<?php

namespace App\Filament\Resources\JournalEntries;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\JournalEntries\Pages\CreateJournalEntry;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\JournalEntries\Pages\EditJournalEntry;
use App\Filament\Resources\JournalEntries\Pages\ListJournalEntries;
use App\Filament\Resources\JournalEntries\Pages\ViewJournalEntry;
use App\Filament\Resources\JournalEntries\Schemas\JournalEntryForm;
use App\Filament\Resources\JournalEntries\Schemas\JournalEntryInfolist;
use App\Filament\Resources\JournalEntries\Tables\JournalEntriesTable;
use App\Models\JournalEntry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JournalEntryResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedBookOpen;
    }

    public static function getNavigationGroup(): string
    {
        return 'Accounting';
    }
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    protected static ?string $model = JournalEntry::class;

    protected static ?string $recordTitleAttribute = 'entry_number';

    public static function form(Schema $schema): Schema
    {
        return JournalEntryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JournalEntryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JournalEntriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListJournalEntries::route('/'),
            'create' => CreateJournalEntry::route('/create'),
            'view'   => ViewJournalEntry::route('/{record}'),
            'edit'   => EditJournalEntry::route('/{record}/edit'),
        ];
    }
}
