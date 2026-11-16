<?php

namespace App\Filament\Resources\Drugs\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class DrugInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Drug Information')
                    ->schema([
                        TextEntry::make('code')->label('Code'),
                        TextEntry::make('name')->label('Drug Name'),
                        TextEntry::make('generic_name')->label('Generic'),
                        TextEntry::make('category.name')->label('Category'),
                        TextEntry::make('dosage_form')->label('Form'),
                        TextEntry::make('strength')->label('Strength'),
                        TextEntry::make('selling_price')->label('Price'),
                        TextEntry::make('status')->label('Active'),
                    ])
                    ->columns(2),
            ]);
    }
}
