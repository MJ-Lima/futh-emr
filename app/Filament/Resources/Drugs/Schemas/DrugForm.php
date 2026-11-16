<?php

namespace App\Filament\Resources\Drugs\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class DrugForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Drug Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            Select::make('drug_category_id')->label('Category')->relationship('category', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Brand Name')->required(),
                            TextInput::make('generic_name')->label('Generic Name'),
                            TextInput::make('code')->label('Code')->required(),
                            TextInput::make('atc_code')->label('ATC Code'),
                            TextInput::make('dosage_form')->label('Dosage Form'),
                            TextInput::make('strength')->label('Strength'),
                            TextInput::make('unit_of_measure')->label('UoM'),
                            TextInput::make('manufacturer')->label('Manufacturer'),
                            TextInput::make('supplier')->label('Supplier'),
                            TextInput::make('cost_price')->label('Cost Price')->numeric(),
                            TextInput::make('selling_price')->label('Selling Price')->numeric(),
                            TextInput::make('reorder_level')->label('Reorder Level')->numeric(),
                            TextInput::make('reorder_quantity')->label('Reorder Qty')->numeric(),
                            Toggle::make('is_controlled')->label('Controlled'),
                            Toggle::make('is_antenatal_safe')->label('Antenatal Safe'),
                            Toggle::make('is_pediatric_safe')->label('Pediatric Safe'),
                            Toggle::make('status')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
