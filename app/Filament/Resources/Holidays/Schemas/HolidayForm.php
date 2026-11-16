<?php

namespace App\Filament\Resources\Holidays\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class HolidayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Holiday Details')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')->label('Holiday Name')->required(),
                            DatePicker::make('holiday_date')->label('Start Date')->required(),
                            DatePicker::make('end_date')->label('End Date'),
                            Select::make('type')->label('Type')->required()->options(['Public Holiday' => 'Public Holiday', 'Religious' => 'Religious', 'National' => 'National', 'Hospital-specific' => 'Hospital-specific', 'Local' => 'Local'])->searchable(),
                            Toggle::make('is_working_day')->label('Is Working Day'),
                            Toggle::make('affects_clinics')->label('Affects Clinics'),
                            Textarea::make('description')->label('Description'),
                        ]),
                    ]),
            ]);
    }
}
