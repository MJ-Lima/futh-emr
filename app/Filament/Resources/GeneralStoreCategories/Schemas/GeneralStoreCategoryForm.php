<?php

namespace App\Filament\Resources\GeneralStoreCategories\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class GeneralStoreCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('GeneralStoreCategory Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Category Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            Select::make('type')->label('Type')->required()->options(['Medical Supplies' => 'Medical Supplies', 'Office Supplies' => 'Office Supplies', 'Cleaning' => 'Cleaning', 'Furniture' => 'Furniture', 'Equipment' => 'Equipment', 'IT' => 'IT', 'Kitchen' => 'Kitchen', 'Linens' => 'Linens', 'Fuel & Lubricants' => 'Fuel & Lubricants', 'Stationery' => 'Stationery', 'PPE' => 'PPE', 'Surgical Consumables' => 'Surgical Consumables', 'Lab Consumables' => 'Lab Consumables', 'Other' => 'Other'])->searchable(),
                            Textarea::make('description')->label('Description'),
                            Toggle::make('is_active')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
