<?php

namespace App\Filament\Resources\StockTakes\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class StockTakeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('StockTake Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('location_id')->label('Location')->relationship('location', 'name')->searchable()->preload(),
                            Select::make('scope')->label('Scope')->required()->options(['Full Hospital' => 'Full Hospital', 'Single Location' => 'Single Location', 'Pharmacy' => 'Pharmacy', 'Specific Items' => 'Specific Items'])->searchable(),
                            DatePicker::make('stock_take_date')->label('Stock Take Date')->required(),
                            Select::make('status')->label('Status')->required()->options(['Planned' => 'Planned', 'In Progress' => 'In Progress', 'Counted' => 'Counted', 'Verified' => 'Verified', 'Closed' => 'Closed', 'Cancelled' => 'Cancelled'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
