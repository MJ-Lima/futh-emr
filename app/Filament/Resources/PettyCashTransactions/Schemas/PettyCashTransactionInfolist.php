<?php

namespace App\Filament\Resources\PettyCashTransactions\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class PettyCashTransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('PettyCashTransaction Information')
                    ->schema([
                        TextEntry::make('voucher_number')->label('Voucher #'),
                        TextEntry::make('user.name')->label('By'),
                        TextEntry::make('type')->label('Type'),
                        TextEntry::make('payee')->label('Payee'),
                        TextEntry::make('category')->label('Category'),
                        TextEntry::make('amount')->label('Amount'),
                        TextEntry::make('balance_after')->label('Balance After'),
                        TextEntry::make('transaction_date')->label('Date')->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }
}
