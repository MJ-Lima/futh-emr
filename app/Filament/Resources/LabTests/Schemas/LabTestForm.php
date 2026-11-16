<?php

namespace App\Filament\Resources\LabTests\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class LabTestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('LabTest Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            Select::make('lab_panel_id')->label('Panel')->relationship('panel', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Test Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            TextInput::make('loinc_code')->label('LOINC Code'),
                            TextInput::make('specimen_type')->label('Specimen Type'),
                            TextInput::make('container')->label('Container'),
                            TextInput::make('unit')->label('Unit'),
                            TextInput::make('reference_range_low')->label('Ref Low')->numeric(),
                            TextInput::make('reference_range_high')->label('Ref High')->numeric(),
                            TextInput::make('reference_notes')->label('Ref Notes'),
                            Textarea::make('description')->label('Description'),
                            TextInput::make('price')->label('Price')->numeric(),
                            TextInput::make('turnaround_hours')->label('Turnaround (hrs)')->numeric(),
                            Toggle::make('status')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
