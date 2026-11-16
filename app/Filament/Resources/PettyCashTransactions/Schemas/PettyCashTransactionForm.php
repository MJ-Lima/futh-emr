<?php

namespace App\Filament\Resources\PettyCashTransactions\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class PettyCashTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('PettyCashTransaction Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('dispensing_point_id')->label('Dispensing Point')->relationship('dispensingPoint', 'name')->searchable()->preload(),
                            Select::make('user_id')->label('User')->required()->relationship('user', 'name')->searchable()->preload(),
                            Select::make('type')->label('Type')->required()->options(['Top Up' => 'Top Up', 'Expense' => 'Expense', 'Reimbursement' => 'Reimbursement', 'Adjustment' => 'Adjustment'])->searchable(),
                            DateTimePicker::make('transaction_date')->label('Transaction Date')->required(),
                            TextInput::make('amount')->label('Amount')->numeric(),
                            TextInput::make('payee')->label('Payee'),
                            TextInput::make('category')->label('Category'),
                            Textarea::make('description')->label('Description'),
                            Select::make('status')->label('Status')->required()->options(['Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected', 'Posted' => 'Posted'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
