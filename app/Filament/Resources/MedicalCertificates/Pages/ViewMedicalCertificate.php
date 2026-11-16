<?php

namespace App\Filament\Resources\MedicalCertificates\Pages;

use App\Filament\Resources\MedicalCertificates\MedicalCertificateResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMedicalCertificate extends ViewRecord
{
    protected static string $resource = MedicalCertificateResource::class;

    public function getTitle(): string
    {
        return 'Certificate · ' . $this->getRecord()->certificate_number;
    }
}
