<?php

namespace App\Filament\Resources\BloodUnits\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class BloodUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BloodUnit Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('donation_id')->label('Donation')->required()->relationship('donation', 'donation_number')->searchable()->preload(),
                            Select::make('donor_id')->label('Donor')->required()->relationship('donor', 'donor_number')->searchable()->preload(),
                            Select::make('blood_group')->label('Blood Group')->required()->options(['A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'AB+' => 'AB+', 'AB-' => 'AB-', 'O+' => 'O+', 'O-' => 'O-'])->searchable(),
                            Select::make('component_type')->label('Component Type')->required()->options(['Whole Blood' => 'Whole Blood', 'Packed Red Cells' => 'Packed Red Cells', 'Plasma' => 'Plasma', 'Platelets' => 'Platelets', 'Cryoprecipitate' => 'Cryoprecipitate', 'Fresh Frozen Plasma' => 'Fresh Frozen Plasma'])->searchable(),
                            TextInput::make('volume_ml')->label('Volume (ml)')->numeric(),
                            DatePicker::make('collection_date')->label('Collection Date')->required(),
                            DatePicker::make('expiry_date')->label('Expiry Date')->required(),
                            TextInput::make('storage_location')->label('Storage Location'),
                            TextInput::make('storage_temperature_c')->label('Storage Temp (°C)')->numeric(),
                            Select::make('hiv_status')->label('HIV Status')->required()->options(['Pending' => 'Pending', 'Negative' => 'Negative', 'Positive' => 'Positive', 'Not Tested' => 'Not Tested'])->searchable(),
                            Select::make('hepatitis_b_status')->label('Hepatitis B Status')->required()->options(['Pending' => 'Pending', 'Negative' => 'Negative', 'Positive' => 'Positive', 'Not Tested' => 'Not Tested'])->searchable(),
                            Select::make('hepatitis_c_status')->label('Hepatitis C Status')->required()->options(['Pending' => 'Pending', 'Negative' => 'Negative', 'Positive' => 'Positive', 'Not Tested' => 'Not Tested'])->searchable(),
                            Select::make('syphilis_status')->label('Syphilis Status')->required()->options(['Pending' => 'Pending', 'Negative' => 'Negative', 'Positive' => 'Positive', 'Not Tested' => 'Not Tested'])->searchable(),
                            Select::make('malaria_status')->label('Malaria Status')->required()->options(['Pending' => 'Pending', 'Negative' => 'Negative', 'Positive' => 'Positive', 'Not Tested' => 'Not Tested'])->searchable(),
                            Textarea::make('screening_notes')->label('Screening Notes'),
                            Toggle::make('is_quarantined')->label('Quarantined'),
                            Select::make('status')->label('Status')->required()->options(['Collected' => 'Collected', 'Screening' => 'Screening', 'Available' => 'Available', 'Reserved' => 'Reserved', 'Transfused' => 'Transfused', 'Discarded' => 'Discarded', 'Expired' => 'Expired'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
