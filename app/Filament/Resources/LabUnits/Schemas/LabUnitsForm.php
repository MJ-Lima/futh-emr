<?php

namespace App\Filament\Resources\LabUnits\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class LabUnitsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Lab Units Details')->schema([
                Grid::make(2)->schema([
                    Select::make('laboratory_id')->label('Laboratory')->required()
                        ->relationship('laboratory', 'name')->searchable()->preload()->native(false),
                    TextInput::make('name')->label('Unit Name')->required(),
                    TextInput::make('code')->label('Code')->required(),
                    TextInput::make('description')->label('Description'),
                    Toggle::make('is_active')->label('Active')->default(true),
                ]),
            ]),
        ]);
    }
}
