<?php

namespace App\Filament\Resources\PatientReceipts\Pages;

use App\Filament\Resources\PatientReceipts\PatientReceiptResource;
use Filament\Resources\Pages\EditRecord;

class EditPatientReceipt extends EditRecord
{
    protected static string $resource = PatientReceiptResource::class;
}
