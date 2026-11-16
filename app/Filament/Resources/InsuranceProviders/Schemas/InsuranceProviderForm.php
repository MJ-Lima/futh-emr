<?php

namespace App\Filament\Resources\InsuranceProviders\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class InsuranceProviderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('InsuranceProvider Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Provider Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            TextInput::make('short_name')->label('Short Name'),
                            Select::make('type')->label('Type')->required()->options(['NHIA' => 'NHIA', 'HMO' => 'HMO', 'Private Insurance' => 'Private Insurance', 'Corporate' => 'Corporate', 'Community-Based' => 'Community-Based', 'Government' => 'Government'])->searchable(),
                            TextInput::make('nhia_code')->label('NHIA Code'),
                            TextInput::make('contact_person')->label('Contact Person'),
                            TextInput::make('phone')->label('Phone')->tel(),
                            TextInput::make('email')->label('Email')->email(),
                            Textarea::make('address')->label('Address'),
                            TextInput::make('bank_name')->label('Bank Name'),
                            TextInput::make('bank_account_number')->label('Bank Account #'),
                            TextInput::make('capitation_rate')->label('Capitation Rate')->numeric(),
                            TextInput::make('discount_percentage')->label('Discount (%)')->numeric(),
                            TextInput::make('claim_settlement_days')->label('Settlement (days)')->numeric(),
                            Select::make('status')->label('Status')->required()->options(['Active' => 'Active', 'Suspended' => 'Suspended', 'Inactive' => 'Inactive'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
