<?php

namespace App\Filament\Resources\Suppliers\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Supplier Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Supplier Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            TextInput::make('contact_person')->label('Contact Person'),
                            TextInput::make('phone')->label('Phone')->tel(),
                            TextInput::make('email')->label('Email')->email(),
                            Textarea::make('address')->label('Address'),
                            TextInput::make('tax_id')->label('Tax ID'),
                            TextInput::make('registration_number')->label('Registration #'),
                            Toggle::make('status')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
