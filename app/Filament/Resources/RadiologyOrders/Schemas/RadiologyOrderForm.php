<?php

namespace App\Filament\Resources\RadiologyOrders\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class RadiologyOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('RadiologyOrder Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()->relationship('patient', 'hospital_no')->getOptionLabelUsing(fn ($record) => $record?->full_name ?? 'Unknown')->searchable()->preload(),
                            Select::make('encounter_id')->label('Encounter')->relationship('encounter', 'encounter_number')->searchable()->preload(),
                            Select::make('modality_id')->label('Modality')->required()->relationship('modality', 'name')->searchable()->preload(),
                            TextInput::make('examination')->label('Examination')->required(),
                            TextInput::make('body_part')->label('Body Part'),
                            Textarea::make('clinical_indication')->label('Clinical Indication'),
                            Textarea::make('request_notes')->label('Request Notes'),
                            Select::make('priority')->label('Priority')->required()->options(['Routine' => 'Routine', 'Urgent' => 'Urgent', 'STAT' => 'STAT'])->searchable(),
                            Select::make('status')->label('Status')->required()->options(['Pending' => 'Pending', 'Scheduled' => 'Scheduled', 'In Progress' => 'In Progress', 'Completed' => 'Completed', 'Verified' => 'Verified', 'Cancelled' => 'Cancelled'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
