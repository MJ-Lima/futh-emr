<?php

namespace App\Filament\Resources\BloodUnits\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class BloodUnitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BloodUnit Information')
                    ->schema([
                        TextEntry::make('unit_number')->label('Unit #'),
                        TextEntry::make('donor.blood_group')->label('Group'),
                        TextEntry::make('component_type')->label('Component'),
                        TextEntry::make('volume_ml')->label('Vol (ml)'),
                        TextEntry::make('collection_date')->label('Collected')->date(),
                        TextEntry::make('expiry_date')->label('Expires')->date(),
                        TextEntry::make('is_screened')->label('Screened'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
