<?php

namespace App\Filament\Resources\PatientReceipts\Pages;

use App\Filament\Resources\PatientReceipts\PatientReceiptResource;
use Filament\Resources\Pages\ListRecords;

class ListPatientReceipts extends ListRecords
{
    protected static string $resource = PatientReceiptResource::class;
}
