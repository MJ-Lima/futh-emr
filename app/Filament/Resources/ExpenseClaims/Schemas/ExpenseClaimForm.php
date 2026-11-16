<?php

namespace App\Filament\Resources\ExpenseClaims\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ExpenseClaimForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ExpenseClaim Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('claimant_id')->label('Claimant')->required()->relationship('claimant', 'name')->searchable()->preload(),
                            Select::make('department_id')->label('Department')->relationship('department', 'name')->searchable()->preload(),
                            TextInput::make('title')->label('Title')->required(),
                            Select::make('type')->label('Expense Type')->required()->options(['Travel' => 'Travel', 'Medical Supplies' => 'Medical Supplies', 'Office Supplies' => 'Office Supplies', 'Maintenance' => 'Maintenance', 'Utility' => 'Utility', 'Transport' => 'Transport', 'Meals' => 'Meals', 'Training' => 'Training', 'Other' => 'Other'])->searchable(),
                            DatePicker::make('expense_date')->label('Expense Date')->required(),
                            TextInput::make('total_amount')->label('Total Amount')->numeric(),
                            TextInput::make('reimbursable_amount')->label('Reimbursable Amount')->numeric(),
                            TextInput::make('approved_amount')->label('Approved Amount')->numeric(),
                            Textarea::make('description')->label('Description'),
                            Select::make('status')->label('Status')->required()->options(['Draft' => 'Draft', 'Submitted' => 'Submitted', 'Under Review' => 'Under Review', 'Approved' => 'Approved', 'Rejected' => 'Rejected', 'Paid' => 'Paid', 'Cancelled' => 'Cancelled'])->searchable(),
                            Textarea::make('review_notes')->label('Review Notes'),
                            Textarea::make('rejection_reason')->label('Rejection Reason'),
                        ]),
                    ]),
            ]);
    }
}
