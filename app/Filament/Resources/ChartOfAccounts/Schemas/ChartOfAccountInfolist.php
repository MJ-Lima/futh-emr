<?php

namespace App\Filament\Resources\ChartOfAccounts\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class ChartOfAccountInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ChartOfAccount Information')
                    ->schema([
                        TextEntry::make('account_code')->label('Code'),
                        TextEntry::make('name')->label('Account Name'),
                        TextEntry::make('type')->label('Type'),
                        TextEntry::make('sub_type')->label('Sub-type'),
                        TextEntry::make('current_balance')->label('Balance'),
                        TextEntry::make('is_bank_account')->label('Bank Acct'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
