<?php

namespace App\Filament\Resources\RelationshipTypes\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class RelationshipTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('RelationshipType Details')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')->label('Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            Select::make('category')->label('Category')->required()->options(['Family' => 'Family', 'Medical' => 'Medical', 'Financial' => 'Financial', 'Legal' => 'Legal', 'Insurance' => 'Insurance', 'Referral' => 'Referral', 'Emergency' => 'Emergency', 'Other' => 'Other'])->searchable(),
                            Textarea::make('description')->label('Description'),
                            Toggle::make('is_symmetric')->label('Symmetric'),
                            Toggle::make('is_active')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
