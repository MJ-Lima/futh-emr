<?php

namespace App\Filament\Resources\MedicalAuditFindings\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class MedicalAuditFindingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('MedicalAuditFinding Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('medical_audit_id')->label('Medical Audit')->required()->relationship('medical_audit', 'audit_number')->searchable()->preload(),
                            TextInput::make('finding_number')->label('Finding #'),
                            Select::make('category')->label('Category')->required()->options(['Clinical' => 'Clinical', 'Documentation' => 'Documentation', 'Medication' => 'Medication', 'Procedure' => 'Procedure', 'Diagnostic' => 'Diagnostic', 'Communication' => 'Communication', 'Follow-up' => 'Follow-up', 'Other' => 'Other'])->searchable(),
                            TextInput::make('title')->label('Title')->required(),
                            Textarea::make('description')->label('Description')->required(),
                            Textarea::make('criteria')->label('Criteria'),
                            Textarea::make('condition_observed')->label('Condition Observed'),
                            Textarea::make('cause')->label('Cause'),
                            Textarea::make('effect')->label('Effect'),
                            Select::make('severity')->label('Severity')->required()->options(['Minor' => 'Minor', 'Moderate' => 'Moderate', 'Major' => 'Major', 'Critical' => 'Critical'])->searchable(),
                            Select::make('risk_level')->label('Risk Level')->required()->options(['Low' => 'Low', 'Moderate' => 'Moderate', 'High' => 'High', 'Critical' => 'Critical'])->searchable(),
                            Textarea::make('recommendation')->label('Recommendation'),
                            Textarea::make('corrective_action')->label('Corrective Action'),
                            Select::make('responsible_user_id')->label('Responsible User')->relationship('responsible_user', 'name')->searchable()->preload(),
                            DatePicker::make('target_completion_date')->label('Target Completion Date'),
                            Select::make('status')->label('Status')->required()->options(['Open' => 'Open', 'In Progress' => 'In Progress', 'Implemented' => 'Implemented', 'Verified' => 'Verified', 'Closed' => 'Closed'])->searchable(),
                            Textarea::make('closure_notes')->label('Closure Notes'),
                        ]),
                    ]),
            ]);
    }
}
