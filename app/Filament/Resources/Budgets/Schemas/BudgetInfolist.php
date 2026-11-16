<?php

namespace App\Filament\Resources\Budgets\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class BudgetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Budget Information')
                    ->schema([
                        TextEntry::make('code')->label('Code'),
                        TextEntry::make('name')->label('Budget Name'),
                        TextEntry::make('hospital.name')->label('Hospital'),
                        TextEntry::make('department.name')->label('Department'),
                        TextEntry::make('period')->label('Period'),
                        TextEntry::make('fiscal_year')->label('FY'),
                        TextEntry::make('planned_amount')->label('Planned'),
                        TextEntry::make('actual_amount')->label('Actual'),
                    ])
                    ->columns(2),
            ]);
    }
}
