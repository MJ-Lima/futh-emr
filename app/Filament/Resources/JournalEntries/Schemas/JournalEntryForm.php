<?php

namespace App\Filament\Resources\JournalEntries\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class JournalEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('JournalEntry Details')
                    ->schema([
                        Grid::make(2)->schema([
                            DatePicker::make('entry_date')->label('Entry Date')->required(),
                            Select::make('type')->label('Entry Type')->required()->options(['Sales' => 'Sales', 'Purchase' => 'Purchase', 'Payment' => 'Payment', 'Receipt' => 'Receipt', 'Journal' => 'Journal', 'Payroll' => 'Payroll', 'Adjustment' => 'Adjustment', 'Closing' => 'Closing', 'Reversal' => 'Reversal'])->searchable(),
                            TextInput::make('reference_number')->label('Reference #'),
                            TextInput::make('reference_type')->label('Reference Type'),
                            TextInput::make('reference_id')->label('Reference ID')->numeric(),
                            Textarea::make('description')->label('Description')->required(),
                            Select::make('is_posted')->label('Posted'),
                            Select::make('is_reversed')->label('Reversed'),
                        ]),
                    ]),
            ]);
    }
}
