<?php

namespace App\Filament\Resources\ExpenseClaims\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class ExpenseClaimInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ExpenseClaim Information')
                    ->schema([
                        TextEntry::make('claim_number')->label('Claim #'),
                        TextEntry::make('claimant.name')->label('Claimant'),
                        TextEntry::make('department.name')->label('Department'),
                        TextEntry::make('title')->label('Title'),
                        TextEntry::make('type')->label('Type'),
                        TextEntry::make('expense_date')->label('Date')->date(),
                        TextEntry::make('total_amount')->label('Total'),
                        TextEntry::make('approved_amount')->label('Approved'),
                    ])
                    ->columns(2),
            ]);
    }
}
