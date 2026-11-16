<?php

namespace App\Filament\Resources\JournalLines;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\JournalLines\Pages\CreateJournalLine;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\JournalLines\Pages\EditJournalLine;
use App\Filament\Resources\JournalLines\Pages\ListJournalLines;
use App\Filament\Resources\JournalLines\Pages\ViewJournalLine;
use App\Filament\Resources\JournalLines\Schemas\JournalLineForm;
use App\Filament\Resources\JournalLines\Schemas\JournalLineInfolist;
use App\Filament\Resources\JournalLines\Tables\JournalLinesTable;
use App\Models\JournalLine;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JournalLineResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedBars3;
    }

    public static function getNavigationGroup(): string
    {
        return 'Accounting';
    }
    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    protected static ?string $model = JournalLine::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return JournalLineForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JournalLineInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JournalLinesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListJournalLines::route('/'),
            'create' => CreateJournalLine::route('/create'),
            'view'   => ViewJournalLine::route('/{record}'),
            'edit'   => EditJournalLine::route('/{record}/edit'),
        ];
    }
}
