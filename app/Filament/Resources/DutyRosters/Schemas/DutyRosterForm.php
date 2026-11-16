<?php

namespace App\Filament\Resources\DutyRosters\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class DutyRosterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('DutyRoster Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Roster Name')->required(),
                            TextInput::make('code')->label('Roster Code'),
                            DatePicker::make('start_date')->label('Start Date')->required(),
                            DatePicker::make('end_date')->label('End Date')->required(),
                            Select::make('cycle')->label('Cycle')->required()->options(['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Bi-Weekly' => 'Bi-Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly'])->searchable(),
                            Textarea::make('description')->label('Description'),
                            Select::make('status')->label('Status')->required()->options(['Draft' => 'Draft', 'Active' => 'Active', 'Closed' => 'Closed'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
