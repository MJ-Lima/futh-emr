<?php

namespace App\Filament\Resources\InsuranceClaims\Pages;

use App\Filament\Resources\InsuranceClaims\InsuranceClaimResource;
use Filament\Resources\Pages\ListRecords;

class ListInsuranceClaims extends ListRecords
{
    protected static string $resource = InsuranceClaimResource::class;
}
