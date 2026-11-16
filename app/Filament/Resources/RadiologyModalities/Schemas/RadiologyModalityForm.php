<?php

namespace App\Filament\Resources\RadiologyModalities\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class RadiologyModalityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('RadiologyModality Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Modality Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            Textarea::make('description')->label('Description'),
                            TextInput::make('price')->label('Price')->numeric(),
                            TextInput::make('turnaround_hours')->label('Turnaround (hrs)')->numeric(),
                            Toggle::make('status')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
