<?php

namespace App\Filament\Resources\DutyPostings\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class DutyPostingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('DutyPosting Information')
                    ->schema([
                        TextEntry::make('user.name')->label('Staff'),
                        TextEntry::make('hospital.name')->label('Hospital'),
                        TextEntry::make('department.name')->label('Department'),
                        TextEntry::make('clinic.name')->label('Unit / Clinic'),
                        TextEntry::make('start_date')->label('Start')->date(),
                        TextEntry::make('end_date')->label('End')->date(),
                        TextEntry::make('shift')->label('Shift'),
                        TextEntry::make('role_in_unit')->label('Role'),
                    ])
                    ->columns(2),
            ]);
    }
}
