<?php

namespace App\Filament\Resources\Laboratories\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class LaboratoriesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Laboratories Details')->schema([
                Grid::make(2)->schema([
                    Select::make('hospital_id')->label('Hospital')->required()
                        ->relationship('hospital', 'name')->searchable()->preload()->native(false),
                    TextInput::make('name')->label('Lab Name')->required(),
                    TextInput::make('code')->label('Code')->required()->unique(ignoreRecord: true),
                    Select::make('lab_type')->label('Type')->required()->options([
                        'dna' => 'DNA Lab', 'main' => 'Main Lab', 'subsidiary' => 'Subsidiary Lab',
                    ])->native(false),
                    TextInput::make('address')->label('Address'),
                    TextInput::make('phone')->label('Phone'),
                    Toggle::make('is_active')->label('Active')->default(true),
                ]),
            ]),
        ]);
    }
}
