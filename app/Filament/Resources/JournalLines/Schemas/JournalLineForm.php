<?php

namespace App\Filament\Resources\JournalLines\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class JournalLineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('JournalLine Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('journal_entry_id')->label('Journal Entry')->required()->relationship('journal_entry', 'entry_number')->searchable()->preload(),
                            Select::make('chart_of_account_id')->label('Account')->required()->relationship('chart_of_account', 'account_code')->searchable()->preload(),
                            TextInput::make('description')->label('Description'),
                            TextInput::make('debit')->label('Debit')->numeric(),
                            TextInput::make('credit')->label('Credit')->numeric(),
                            TextInput::make('cost_center')->label('Cost Center'),
                        ]),
                    ]),
            ]);
    }
}
