<?php

namespace App\Filament\Resources\Procedures\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ProcedureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Procedure Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('procedure_category_id')->label('Category')->relationship('procedure_category', 'name')->searchable()->preload(),
                            Select::make('service_id')->label('Linked Service')->relationship('service', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Procedure Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            TextInput::make('icd10_pcs_code')->label('ICD-10-PCS Code'),
                            TextInput::make('cpt_code')->label('CPT Code'),
                            Select::make('type')->label('Type')->required()->options(['Minor' => 'Minor', 'Major' => 'Major', 'Intermediate' => 'Intermediate', 'Complex' => 'Complex', 'Day Case' => 'Day Case'])->searchable(),
                            Select::make('site')->label('Site')->required()->options(['Theatre' => 'Theatre', 'Procedure Room' => 'Procedure Room', 'Ward Bedside' => 'Ward Bedside', 'Consulting Room' => 'Consulting Room', 'Emergency Room' => 'Emergency Room', 'ICU' => 'ICU', 'Other' => 'Other'])->searchable(),
                            Select::make('anaesthesia_type')->label('Anaesthesia Type')->required()->options(['Local' => 'Local', 'Regional' => 'Regional', 'Spinal' => 'Spinal', 'Epidural' => 'Epidural', 'General' => 'General', 'Sedation' => 'Sedation', 'None' => 'None'])->searchable(),
                            TextInput::make('estimated_duration_minutes')->label('Estimated Duration (min)')->numeric(),
                            TextInput::make('default_pre_op_days')->label('Default Pre-Op Days')->numeric(),
                            TextInput::make('default_post_op_days')->label('Default Post-Op Days')->numeric(),
                            Textarea::make('description')->label('Description'),
                            Textarea::make('indication')->label('Indication'),
                            Textarea::make('contraindication')->label('Contraindication'),
                            Textarea::make('pre_op_instructions')->label('Pre-Op Instructions'),
                            Textarea::make('post_op_instructions')->label('Post-Op Instructions'),
                            Textarea::make('complications')->label('Complications'),
                            TextInput::make('standard_fee')->label('Standard Fee')->numeric(),
                            TextInput::make('nhia_rate')->label('NHIA Rate')->numeric(),
                            TextInput::make('private_rate')->label('Private Rate')->numeric(),
                            TextInput::make('surgeon_fee')->label('Surgeon Fee')->numeric(),
                            TextInput::make('anaesthetist_fee')->label('Anaesthetist Fee')->numeric(),
                            TextInput::make('theatre_fee')->label('Theatre Fee')->numeric(),
                            TextInput::make('consumables_fee')->label('Consumables Fee')->numeric(),
                            Toggle::make('requires_pre_authorization')->label('Requires Pre-Auth'),
                            Toggle::make('requires_blood_reservation')->label('Requires Blood Reservation'),
                            Toggle::make('is_active')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
