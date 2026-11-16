<?php

namespace App\Filament\Resources\DispensingPoints\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class DispensingPointForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('DispensingPoint Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Point Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            TextInput::make('location')->label('Location'),
                            Select::make('in_charge_id')->label('In-Charge')->relationship('inCharge', 'name')->searchable()->preload(),
                            Toggle::make('status')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
