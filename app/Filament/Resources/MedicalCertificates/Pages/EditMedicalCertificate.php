<?php

namespace App\Filament\Resources\MedicalCertificates\Pages;

use App\Filament\Resources\MedicalCertificates\MedicalCertificateResource;
use Filament\Resources\Pages\EditRecord;

class EditMedicalCertificate extends EditRecord
{
    protected static string $resource = MedicalCertificateResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('view', ['record' => $this->record]);
    }
}
