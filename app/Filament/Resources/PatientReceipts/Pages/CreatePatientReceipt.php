<?php

namespace App\Filament\Resources\PatientReceipts\Pages;

use App\Filament\Resources\PatientReceipts\PatientReceiptResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatientReceipt extends CreateRecord
{
    protected static string $resource = PatientReceiptResource::class;
}
