<?php

namespace App\Filament\Resources\PettyCashTransactions\Pages;

use App\Filament\Resources\PettyCashTransactions\PettyCashTransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePettyCashTransaction extends CreateRecord
{
    protected static string $resource = PettyCashTransactionResource::class;
}
