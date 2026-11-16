<?php

namespace App\Filament\Resources\Procedures\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class ProcedureInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Procedure Information')
                    ->schema([
                        TextEntry::make('code')->label('Code'),
                        TextEntry::make('name')->label('Procedure'),
                        TextEntry::make('category.name')->label('Category'),
                        TextEntry::make('type')->label('Type'),
                        TextEntry::make('site')->label('Site'),
                        TextEntry::make('anaesthesia_type')->label('Anaesthesia'),
                        TextEntry::make('estimated_duration_minutes')->label('Est. (min)'),
                        TextEntry::make('standard_fee')->label('Fee'),
                    ])
                    ->columns(2),
            ]);
    }
}
