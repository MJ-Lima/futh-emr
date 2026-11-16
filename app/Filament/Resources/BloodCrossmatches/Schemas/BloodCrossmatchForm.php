<?php

namespace App\Filament\Resources\BloodCrossmatches\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class BloodCrossmatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('BloodCrossmatch Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('blood_request_id')->label('Blood Request')->required()->relationship('blood_request', 'request_number')->searchable()->preload(),
                            Select::make('blood_unit_id')->label('Blood Unit')->required()->relationship('blood_unit', 'unit_number')->searchable()->preload(),
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('performed_by')->label('Performed By')->required()->relationship('performed_by', 'name')->searchable()->preload(),
                            Select::make('method')->label('Method')->required()->options(['Saline' => 'Saline', 'Albumin' => 'Albumin', 'AHG' => 'AHG', 'Gel Card' => 'Gel Card', 'Electronic Crossmatch' => 'Electronic Crossmatch'])->searchable(),
                            Select::make('major_crossmatch')->label('Major Crossmatch')->required()->options(['Compatible' => 'Compatible', 'Incompatible' => 'Incompatible', 'Pending' => 'Pending'])->searchable(),
                            Select::make('minor_crossmatch')->label('Minor Crossmatch')->required()->options(['Compatible' => 'Compatible', 'Incompatible' => 'Incompatible', 'Not Done' => 'Not Done', 'Pending' => 'Pending'])->searchable(),
                            Textarea::make('results')->label('Results'),
                            Textarea::make('notes')->label('Notes'),
                            Select::make('status')->label('Status')->required()->options(['Pending' => 'Pending', 'Compatible' => 'Compatible', 'Incompatible' => 'Incompatible', 'Expired' => 'Expired', 'Transfused' => 'Transfused', 'Discarded' => 'Discarded'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
