<?php

namespace App\Filament\Resources\Wards\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class WardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ward Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            Select::make('department_id')->label('Department')->relationship('department', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Ward Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            Select::make('type')->label('Ward Type')->required()->options(['General' => 'General', 'Male Medical' => 'Male Medical', 'Female Medical' => 'Female Medical', 'Male Surgical' => 'Male Surgical', 'Female Surgical' => 'Female Surgical', 'Paediatrics' => 'Paediatrics', 'Maternity' => 'Maternity', 'Antenatal' => 'Antenatal', 'Postnatal' => 'Postnatal', 'ICU' => 'ICU', 'HDU' => 'HDU', 'NICU' => 'NICU', 'Emergency' => 'Emergency', 'Isolation' => 'Isolation', 'Private' => 'Private', 'Psychiatry' => 'Psychiatry', 'Other' => 'Other'])->searchable(),
                            Select::make('gender_restriction')->label('Gender Restriction')->required()->options(['Any' => 'Any', 'Male Only' => 'Male Only', 'Female Only' => 'Female Only', 'Paediatrics Only' => 'Paediatrics Only'])->searchable(),
                            TextInput::make('total_beds')->label('Total Beds')->numeric(),
                            TextInput::make('floor')->label('Floor'),
                            TextInput::make('wing')->label('Wing'),
                            TextInput::make('daily_room_rate')->label('Daily Room Rate')->numeric(),
                            Select::make('in_charge_id')->label('Ward In-Charge')->relationship('inCharge', 'name')->searchable()->preload(),
                            Textarea::make('description')->label('Description'),
                            Select::make('status')->label('Status')->required()->options(['Active' => 'Active', 'Closed' => 'Closed', 'Maintenance' => 'Maintenance'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
