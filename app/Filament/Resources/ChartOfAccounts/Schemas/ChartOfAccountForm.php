<?php

namespace App\Filament\Resources\ChartOfAccounts\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ChartOfAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ChartOfAccount Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            TextInput::make('account_code')->label('Account Code')->required(),
                            TextInput::make('name')->label('Account Name')->required(),
                            Select::make('type')->label('Account Type')->required()->options(['Asset' => 'Asset', 'Liability' => 'Liability', 'Equity' => 'Equity', 'Revenue' => 'Revenue', 'Expense' => 'Expense', 'Contra' => 'Contra'])->searchable(),
                            Select::make('sub_type')->label('Sub-Type')->required()->options(['Current Asset' => 'Current Asset', 'Fixed Asset' => 'Fixed Asset', 'Other Asset' => 'Other Asset', 'Current Liability' => 'Current Liability', 'Long-term Liability' => 'Long-term Liability', 'Other Liability' => 'Other Liability', 'Capital' => 'Capital', 'Retained Earnings' => 'Retained Earnings', 'Operating Revenue' => 'Operating Revenue', 'Other Revenue' => 'Other Revenue', 'Operating Expense' => 'Operating Expense', 'Administrative Expense' => 'Administrative Expense', 'Cost of Goods Sold' => 'Cost of Goods Sold', 'Other Expense' => 'Other Expense', 'Contra Asset' => 'Contra Asset', 'Contra Revenue' => 'Contra Revenue'])->searchable(),
                            TextInput::make('currency')->label('Currency'),
                            Select::make('parent_account_id')->label('Parent Account')->relationship('parent_account', 'name')->searchable()->preload(),
                            TextInput::make('opening_balance')->label('Opening Balance')->numeric(),
                            Toggle::make('is_active')->label('Active'),
                            Toggle::make('is_bank_account')->label('Is Bank Account'),
                            TextInput::make('bank_name')->label('Bank Name'),
                            TextInput::make('bank_account_number')->label('Bank Account #'),
                            Textarea::make('description')->label('Description'),
                        ]),
                    ]),
            ]);
    }
}
