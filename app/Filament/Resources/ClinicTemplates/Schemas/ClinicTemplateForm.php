<?php

namespace App\Filament\Resources\ClinicTemplates\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
class ClinicTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Forms\Components\Select::make('clinic_id')->label('Specific Clinic (optional)')
                ->relationship('clinic', 'name')->searchable()->preload()->native(false)
                ->helperText('Leave blank to apply this template to ALL clinics of the type below.'),
            \Filament\Forms\Components\Select::make('clinic_type')->label('Clinic Type')->required()
                ->options(\App\Models\ClinicTemplate::CLINIC_TYPES)->searchable()->native(false),
            \Filament\Forms\Components\Select::make('section_type')->label('Section Type')->required()
                ->options(\App\Models\ClinicTemplate::SECTION_TYPES)->native(false),
            \Filament\Forms\Components\TextInput::make('name')->label('Template Name')->required(),
            \Filament\Forms\Components\Textarea::make('fields_json')->label('Fields (one per line, or JSON array)')
                ->rows(8)->placeholder('["BP", "Pulse", "Temperature"]'),
            \Filament\Forms\Components\Textarea::make('description')->rows(2),
            \Filament\Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            \Filament\Forms\Components\Toggle::make('is_active')->label('Active')->default(true),
        ]);
    }
}
