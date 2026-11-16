<?php

namespace App\Filament\Resources\Beds\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class BedForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bed Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('ward_id')->label('Ward')->required()->relationship('ward', 'name')->searchable()->preload(),
                            TextInput::make('bed_number')->label('Bed Number')->required(),
                            TextInput::make('code')->label('Code'),
                            Select::make('type')->label('Bed Type')->required()->options(['Regular' => 'Regular', 'Electric' => 'Electric', 'ICU' => 'ICU', 'HDU' => 'HDU', 'Baby Cot' => 'Baby Cot', 'Stretchers' => 'Stretchers', 'Recovery' => 'Recovery', 'Other' => 'Other'])->searchable(),
                            Select::make('class')->label('Bed Class')->required()->options(['General' => 'General', 'Private' => 'Private', 'Semi-Private' => 'Semi-Private', 'VIP' => 'VIP', 'Suite' => 'Suite'])->searchable(),
                            TextInput::make('daily_rate')->label('Daily Rate')->numeric(),
                            Select::make('status')->label('Status')->required()->options(['Available' => 'Available', 'Occupied' => 'Occupied', 'Reserved' => 'Reserved', 'Out of Service' => 'Out of Service', 'Cleaning' => 'Cleaning', 'Maintenance' => 'Maintenance'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
