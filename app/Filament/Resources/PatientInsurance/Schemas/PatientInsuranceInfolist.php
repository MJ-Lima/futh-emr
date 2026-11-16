<?php

namespace App\Filament\Resources\PatientInsurance\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class PatientInsuranceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('PatientInsurance Information')
                    ->schema([
                        TextEntry::make('policy_number')->label('Policy #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('provider.name')->label('Provider'),
                        TextEntry::make('plan.name')->label('Plan'),
                        TextEntry::make('principal_relationship')->label('Relationship'),
                        TextEntry::make('effective_date')->label('Effective')->date(),
                        TextEntry::make('expiry_date')->label('Expiry')->date(),
                        TextEntry::make('verification_status')->label('Verification'),
                    ])
                    ->columns(2),
            ]);
    }
}
