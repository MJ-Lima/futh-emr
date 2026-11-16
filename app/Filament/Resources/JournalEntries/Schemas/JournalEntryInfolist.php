<?php

namespace App\Filament\Resources\JournalEntries\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class JournalEntryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('JournalEntry Information')
                    ->schema([
                        TextEntry::make('entry_number')->label('Entry #'),
                        TextEntry::make('entry_date')->label('Date')->date(),
                        TextEntry::make('type')->label('Type'),
                        TextEntry::make('reference_number')->label('Reference'),
                        TextEntry::make('total_debit')->label('Debit'),
                        TextEntry::make('total_credit')->label('Credit'),
                        TextEntry::make('is_posted')->label('Posted'),
                        TextEntry::make('is_reversed')->label('Reversed'),
                    ])
                    ->columns(2),
            ]);
    }
}
