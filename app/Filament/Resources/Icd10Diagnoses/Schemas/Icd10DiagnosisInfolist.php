<?php

namespace App\Filament\Resources\Icd10Diagnoses\Schemas;

use Filament\Schemas\Schema;

class Icd10DiagnosisInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // TODO: add infolist entries
        ]);
    }
}
