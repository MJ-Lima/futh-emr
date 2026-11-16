<?php

namespace App\Filament\Resources\GeneralStoreLocations\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class GeneralStoreLocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('GeneralStoreLocation Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            Select::make('department_id')->label('Department')->relationship('department', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Location Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            Select::make('type')->label('Location Type')->required()->options(['Main Store' => 'Main Store', 'Sub-Store' => 'Sub-Store', 'Ward Store' => 'Ward Store', 'Department Store' => 'Department Store', 'Theatre Store' => 'Theatre Store', 'Emergency Store' => 'Emergency Store', 'Other' => 'Other'])->searchable(),
                            TextInput::make('building')->label('Building'),
                            TextInput::make('floor')->label('Floor'),
                            TextInput::make('room')->label('Room'),
                            Select::make('in_charge_id')->label('In-Charge')->relationship('in_charge', 'name')->searchable()->preload(),
                            Toggle::make('is_active')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
