<?php

namespace App\Filament\Resources\AppointmentTypes\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class AppointmentTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('AppointmentType Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Type Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            Select::make('category')->label('Category')->required()->options(['New' => 'New', 'Follow-up' => 'Follow-up', 'Review' => 'Review', 'Procedure' => 'Procedure', 'Telemedicine' => 'Telemedicine', 'Emergency' => 'Emergency', 'Specialist' => 'Specialist'])->searchable(),
                            TextInput::make('color')->label('Color (hex)'),
                            TextInput::make('default_duration_minutes')->label('Default Duration (min)')->numeric(),
                            TextInput::make('default_fee')->label('Default Fee')->numeric(),
                            Toggle::make('requires_pre_auth')->label('Requires Pre-Auth'),
                            Toggle::make('is_active')->label('Active'),
                            Textarea::make('description')->label('Description'),
                        ]),
                    ]),
            ]);
    }
}
