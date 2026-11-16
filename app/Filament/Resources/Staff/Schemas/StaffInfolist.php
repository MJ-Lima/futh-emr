<?php

namespace App\Filament\Resources\Staff\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class StaffInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Staff Information')
                    ->schema([
                        TextEntry::make('staff_number')->label('Staff #'),
                        TextEntry::make('full_name')->label('Name'),
                        TextEntry::make('hospital.name')->label('Hospital'),
                        TextEntry::make('department.name')->label('Department'),
                        TextEntry::make('clinic.name')->label('Clinic'),
                        TextEntry::make('designation')->label('Designation'),
                        TextEntry::make('staff_type')->label('Type'),
                        TextEntry::make('status')->label('Active'),
                    ])
                    ->columns(2),
            ]);
    }
}
