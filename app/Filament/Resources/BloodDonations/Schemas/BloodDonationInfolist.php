<?php

namespace App\Filament\Resources\BloodDonations\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class BloodDonationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BloodDonation Information')
                    ->schema([
                        TextEntry::make('donation_number')->label('Donation #'),
                        TextEntry::make('donor.full_name')->label('Donor'),
                        TextEntry::make('donor.blood_group')->label('Group'),
                        TextEntry::make('donation_date')->label('Date')->date(),
                        TextEntry::make('donation_type')->label('Type'),
                        TextEntry::make('volume_collected_ml')->label('Volume (ml)'),
                        TextEntry::make('collector.name')->label('Collected By'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
