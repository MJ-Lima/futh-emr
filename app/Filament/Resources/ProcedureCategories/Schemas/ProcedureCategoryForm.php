<?php

namespace App\Filament\Resources\ProcedureCategories\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ProcedureCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ProcedureCategory Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Category Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            Select::make('specialty')->label('Specialty')->required()->options(['General Surgery' => 'General Surgery', 'Orthopaedics' => 'Orthopaedics', 'OBGYN' => 'OBGYN', 'ENT' => 'ENT', 'Ophthalmology' => 'Ophthalmology', 'Dental' => 'Dental', 'Cardiology' => 'Cardiology', 'Urology' => 'Urology', 'Neurosurgery' => 'Neurosurgery', 'Plastic Surgery' => 'Plastic Surgery', 'Paediatric Surgery' => 'Paediatric Surgery', 'Endoscopy' => 'Endoscopy', 'Other' => 'Other'])->searchable(),
                            Textarea::make('description')->label('Description'),
                            Toggle::make('requires_consent')->label('Requires Consent'),
                            Toggle::make('requires_anaesthesia')->label('Requires Anaesthesia'),
                            Toggle::make('requires_pre_op_assessment')->label('Requires Pre-Op Assessment'),
                            Toggle::make('is_active')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
