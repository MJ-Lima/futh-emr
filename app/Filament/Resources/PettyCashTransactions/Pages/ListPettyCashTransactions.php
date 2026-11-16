<?php

namespace App\Filament\Resources\PettyCashTransactions\Pages;

use App\Filament\Resources\PettyCashTransactions\PettyCashTransactionResource;
use Filament\Resources\Pages\ListRecords;

class ListPettyCashTransactions extends ListRecords
{
    protected static string $resource = PettyCashTransactionResource::class;
}
