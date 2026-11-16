<?php

namespace App\Filament\Resources\Budgets\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class BudgetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Budget Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            Select::make('department_id')->label('Department')->relationship('department', 'name')->searchable()->preload(),
                            Select::make('chart_of_account_id')->label('Account')->relationship('chart_of_account', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Budget Name')->required(),
                            TextInput::make('code')->label('Code'),
                            Select::make('period')->label('Period')->required()->options(['Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Annual' => 'Annual'])->searchable(),
                            TextInput::make('fiscal_year')->label('Fiscal Year')->numeric(),
                            TextInput::make('quarter')->label('Quarter')->numeric(),
                            TextInput::make('month')->label('Month')->numeric(),
                            TextInput::make('planned_amount')->label('Planned Amount')->numeric(),
                            TextInput::make('actual_amount')->label('Actual Amount')->numeric(),
                            Select::make('status')->label('Status')->required()->options(['Draft' => 'Draft', 'Approved' => 'Approved', 'Active' => 'Active', 'Closed' => 'Closed', 'Revised' => 'Revised'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
