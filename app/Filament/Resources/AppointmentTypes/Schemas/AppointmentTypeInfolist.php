<?php

namespace App\Filament\Resources\AppointmentTypes\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class AppointmentTypeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('AppointmentType Information')
                    ->schema([
                        TextEntry::make('code')->label('Code'),
                        TextEntry::make('name')->label('Type'),
                        TextEntry::make('category')->label('Category'),
                        TextEntry::make('default_duration_minutes')->label('Duration (min)'),
                        TextEntry::make('default_fee')->label('Fee'),
                        TextEntry::make('requires_pre_auth')->label('Pre-Auth'),
                        TextEntry::make('is_active')->label('Active'),
                    ])
                    ->columns(2),
            ]);
    }
}
