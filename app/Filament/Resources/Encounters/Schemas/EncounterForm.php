<?php

namespace App\Filament\Resources\Encounters\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;

class EncounterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
			Select::make('patient_id')
					->relationship(name: 'patient', titleAttribute: 'hospital_no')
					->searchable() 
					->preload(),
            
			Select::make('clinic_id')
						->relationship(name: 'clinic', titleAttribute: 'name', modifyQueryUsing: fn ($query) => $query->where('hospital_id', Auth::user()->hospital_id)->orderBy('name'))
						->searchable() 
						->preload(),
               
				Section::make('Encounter information')
				->relationship('hospital')
				->schema([
					Select::make('visit_type')
					->label('Patient type')
                    ->options([
						'Outpatient' => 'Outpatient',
						'Emergency' => 'Emergency',
						'Inpatient' => 'Inpatient',
						'Referal' => 'Referal',
						'Telemedicine' => 'Telemedicine',
					])->required(),
					DatePicker::make('encounter_date')
						->required(),
					TimePicker::make('encounter_time')
						->required(),
					TextInput::make('referred_by'),
				]),
				Section::make('Appointment Information')
					->relationship('appointment') 
					->schema([
						DatePicker::make('appointment_date'),
						TimePicker::make('appointment_time'),
						Select::make('visit_type')
							->options([
								'New' => 'New',
								'Follow-up' => 'Follow-up',
								'Review' => 'Review',
								'Telemedicine' => 'Telemedicine',
						]),
						 Select::make('status')
                    ->options([
						'Waiting' => 'Waiting',
						'In Progress' => 'In progress',
						'Completed' => 'Completed',
						'Cancelled' => 'Cancelled',
					])
                    ->default('Waiting')
                    ->required(),
				]) ,
                
               
               
               
                
               
          
				 Textarea::make('chief_complaint'),
                Textarea::make('notes'),
				 
            ]);
    }
}
