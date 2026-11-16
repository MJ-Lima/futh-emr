<?php

namespace App\Filament\Resources\BloodDonors\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class BloodDonorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BloodDonor Information')
                    ->schema([
                        TextEntry::make('donor_number')->label('Donor #'),
                        TextEntry::make('full_name')->label('Name'),
                        TextEntry::make('gender')->label('Gender'),
                        TextEntry::make('blood_group')->label('Blood Group'),
                        TextEntry::make('rh_factor')->label('Rh'),
                        TextEntry::make('total_donations')->label('Donations'),
                        TextEntry::make('last_donation_date')->label('Last Donation')->date(),
                        TextEntry::make('is_eligible')->label('Eligible'),
                    ])
                    ->columns(2),
            ]);
    }
}
