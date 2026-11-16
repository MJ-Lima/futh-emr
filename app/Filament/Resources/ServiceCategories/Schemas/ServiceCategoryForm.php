<?php

namespace App\Filament\Resources\ServiceCategories\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ServiceCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ServiceCategory Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            Select::make('parent_id')->label('Parent Category')->relationship('parent', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Category Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            Select::make('type')->label('Type')->required()->options(['Consultation' => 'Consultation', 'Procedure' => 'Procedure', 'Laboratory' => 'Laboratory', 'Radiology' => 'Radiology', 'Pharmacy' => 'Pharmacy', 'Accommodation' => 'Accommodation', 'Consumable' => 'Consumable', 'Equipment' => 'Equipment', 'Emergency' => 'Emergency', 'Other' => 'Other'])->searchable(),
                            TextInput::make('color')->label('Color (hex)'),
                            Textarea::make('description')->label('Description'),
                            Toggle::make('is_active')->label('Active'),
                            TextInput::make('sort_order')->label('Sort Order')->numeric(),
                        ]),
                    ]),
            ]);
    }
}
