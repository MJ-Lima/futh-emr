<?php

namespace App\Filament\Resources\InsurancePlans\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class InsurancePlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('InsurancePlan Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('insurance_provider_id')->label('Provider')->required()->relationship('provider', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Plan Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            Select::make('tier')->label('Tier')->required()->options(['Formal' => 'Formal', 'Informal' => 'Informal', 'Equity' => 'Equity', 'Vulnerable' => 'Vulnerable', 'Premium' => 'Premium', 'Standard' => 'Standard', 'VIP' => 'VIP'])->searchable(),
                            Select::make('coverage_type')->label('Coverage Type')->required()->options(['Individual' => 'Individual', 'Family' => 'Family', 'Corporate' => 'Corporate', 'Group' => 'Group'])->searchable(),
                            TextInput::make('annual_premium')->label('Annual Premium')->numeric(),
                            TextInput::make('annual_benefit_limit')->label('Annual Benefit Limit')->numeric(),
                            TextInput::make('per_encounter_limit')->label('Per-Encounter Limit')->numeric(),
                            TextInput::make('outpatient_deduction')->label('Outpatient Deduction')->numeric(),
                            TextInput::make('inpatient_deduction')->label('Inpatient Deduction')->numeric(),
                            TextInput::make('copay_percentage')->label('Copay (%)')->numeric(),
                            Toggle::make('covers_pharmacy')->label('Covers Pharmacy'),
                            Toggle::make('covers_lab')->label('Covers Lab'),
                            Toggle::make('covers_radiology')->label('Covers Radiology'),
                            Toggle::make('covers_inpatient')->label('Covers Inpatient'),
                            Toggle::make('covers_maternity')->label('Covers Maternity'),
                            Toggle::make('covers_dental')->label('Covers Dental'),
                            Toggle::make('covers_optical')->label('Covers Optical'),
                            Textarea::make('description')->label('Description'),
                            Select::make('status')->label('Status')->required()->options(['Active' => 'Active', 'Inactive' => 'Inactive'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
