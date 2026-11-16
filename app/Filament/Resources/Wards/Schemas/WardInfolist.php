<?php

namespace App\Filament\Resources\Wards\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class WardInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ward Information')
                    ->schema([
                        TextEntry::make('code')->label('Code'),
                        TextEntry::make('name')->label('Ward Name'),
                        TextEntry::make('hospital.name')->label('Hospital'),
                        TextEntry::make('department.name')->label('Department'),
                        TextEntry::make('type')->label('Type'),
                        TextEntry::make('gender_restriction')->label('Gender'),
                        TextEntry::make('total_beds')->label('Beds'),
                        TextEntry::make('occupiedBedsCount')->label('Occupied'),
                    ])
                    ->columns(2),
            ]);
    }
}
