<?php

namespace App\Filament\Resources\ReportTemplates\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ReportTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ReportTemplate Details')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')->label('Template Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            Select::make('category')->label('Category')->required()->options(['Patient' => 'Patient', 'Clinical' => 'Clinical', 'Financial' => 'Financial', 'Pharmacy' => 'Pharmacy', 'Laboratory' => 'Laboratory', 'Radiology' => 'Radiology', 'Insurance' => 'Insurance', 'HR' => 'HR', 'Inventory' => 'Inventory', 'Audit' => 'Audit', 'Mortality' => 'Mortality', 'Morbidity' => 'Morbidity', 'Infection' => 'Infection', 'Quality' => 'Quality', 'Operational' => 'Operational', 'Other' => 'Other'])->searchable(),
                            Select::make('frequency')->label('Frequency')->required()->options(['On Demand' => 'On Demand', 'Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Annual' => 'Annual'])->searchable(),
                            Textarea::make('description')->label('Description'),
                            TextInput::make('data_source')->label('Data Source'),
                            Textarea::make('sql_query')->label('SQL Query'),
                            Toggle::make('is_scheduled')->label('Is Scheduled'),
                            Toggle::make('is_active')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
