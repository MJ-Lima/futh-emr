<?php

namespace App\Filament\Resources\AuditFollowUps\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class AuditFollowUpForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('AuditFollowUp Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('audit_type')->label('Audit Type')->required()->options(['Medical' => 'Medical', 'Internal' => 'Internal'])->searchable(),
                            Select::make('medical_audit_id')->label('Medical Audit')->relationship('medical_audit', 'audit_number')->searchable()->preload(),
                            Select::make('internal_audit_id')->label('Internal Audit')->relationship('internal_audit', 'audit_number')->searchable()->preload(),
                            DatePicker::make('follow_up_date')->label('Follow-up Date')->required(),
                            Textarea::make('progress_notes')->label('Progress Notes')->required(),
                            Textarea::make('evidence_provided')->label('Evidence Provided'),
                            Select::make('progress_status')->label('Progress Status')->required()->options(['Not Started' => 'Not Started', 'In Progress' => 'In Progress', 'Delayed' => 'Delayed', 'Completed' => 'Completed', 'Overdue' => 'Overdue'])->searchable(),
                            TextInput::make('completion_percentage')->label('Completion %')->numeric(),
                            DatePicker::make('next_follow_up_date')->label('Next Follow-up Date'),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
