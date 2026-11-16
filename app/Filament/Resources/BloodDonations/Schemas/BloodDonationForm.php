<?php

namespace App\Filament\Resources\BloodDonations\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class BloodDonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BloodDonation Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('donor_id')->label('Donor')->required()->relationship('donor', 'donor_number')->searchable()->preload(),
                            DatePicker::make('donation_date')->label('Donation Date')->required(),
                            TimePicker::make('donation_time')->label('Donation Time')->required(),
                            Select::make('donation_type')->label('Donation Type')->required()->options(['Whole Blood' => 'Whole Blood', 'Plasma' => 'Plasma', 'Platelets' => 'Platelets', 'Double Red Cells' => 'Double Red Cells', 'Cryoprecipitate' => 'Cryoprecipitate'])->searchable(),
                            TextInput::make('volume_collected_ml')->label('Volume Collected (ml)')->numeric(),
                            Select::make('collected_by')->label('Collected By')->required()->relationship('collected_by', 'name')->searchable()->preload(),
                            TextInput::make('collection_site')->label('Collection Site'),
                            TextInput::make('vein_used')->label('Vein Used'),
                            Textarea::make('adverse_reactions')->label('Adverse Reactions'),
                            Textarea::make('notes')->label('Notes'),
                            Select::make('status')->label('Status')->required()->options(['Collected' => 'Collected', 'Screening' => 'Screening', 'Quarantined' => 'Quarantined', 'Available' => 'Available', 'Discarded' => 'Discarded', 'Expired' => 'Expired', 'Transfused' => 'Transfused'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
