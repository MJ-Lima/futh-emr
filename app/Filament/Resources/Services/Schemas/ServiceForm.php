<?php

namespace App\Filament\Resources\Services\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Service Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            Select::make('category')->label('Category')->required()->options(['Consultation' => 'Consultation', 'Procedure' => 'Procedure', 'Laboratory' => 'Laboratory', 'Radiology' => 'Radiology', 'Pharmacy' => 'Pharmacy', 'Accommodation' => 'Accommodation', 'Consumable' => 'Consumable', 'Equipment' => 'Equipment', 'Emergency' => 'Emergency', 'Other' => 'Other'])->searchable(),
                            Select::make('sub_category')->label('Sub-Category')->required()->options(['General' => 'General', 'Specialist' => 'Specialist', 'Surgery' => 'Surgery', 'Dental' => 'Dental', 'Optical' => 'Optical', 'Mental Health' => 'Mental Health', 'Maternity' => 'Maternity', 'Physiotherapy' => 'Physiotherapy'])->searchable(),
                            TextInput::make('nhia_code')->label('NHIA Code'),
                            TextInput::make('standard_price')->label('Standard Price')->numeric(),
                            TextInput::make('nhia_rate')->label('NHIA Rate')->numeric(),
                            TextInput::make('private_rate')->label('Private Rate')->numeric(),
                            TextInput::make('cost_price')->label('Cost Price')->numeric(),
                            TextInput::make('unit_of_measure')->label('Unit'),
                            Toggle::make('is_taxable')->label('Taxable'),
                            TextInput::make('tax_percentage')->label('Tax (%)')->numeric(),
                            Toggle::make('requires_pre_authorization')->label('Requires Pre-Auth'),
                            Textarea::make('description')->label('Description'),
                            Select::make('status')->label('Status')->required()->options(['Active' => 'Active', 'Inactive' => 'Inactive'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
