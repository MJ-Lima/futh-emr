<?php

namespace App\Filament\Resources\InsuranceClaims\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class InsuranceClaimInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('InsuranceClaim Information')
                    ->schema([
                        TextEntry::make('claim_number')->label('Claim #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('provider.name')->label('Provider'),
                        TextEntry::make('claim_type')->label('Type'),
                        TextEntry::make('service_date_start')->label('Service From')->date(),
                        TextEntry::make('total_claimed')->label('Claimed'),
                        TextEntry::make('total_approved')->label('Approved'),
                        TextEntry::make('total_paid')->label('Paid'),
                    ])
                    ->columns(2),
            ]);
    }
}
