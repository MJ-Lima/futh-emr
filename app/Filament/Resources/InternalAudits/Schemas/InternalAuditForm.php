<?php

namespace App\Filament\Resources\InternalAudits\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class InternalAuditForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('InternalAudit Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('audit_category_id')->label('Audit Category')->relationship('audit_category', 'name')->searchable()->preload(),
                            Select::make('department_id')->label('Department')->relationship('department', 'name')->searchable()->preload(),
                            Select::make('lead_auditor_id')->label('Lead Auditor')->relationship('lead_auditor', 'name')->searchable()->preload(),
                            Select::make('audit_type')->label('Audit Type')->required()->options(['Financial' => 'Financial', 'Operational' => 'Operational', 'Compliance' => 'Compliance', 'IT' => 'IT', 'HR' => 'HR', 'Procurement' => 'Procurement', 'Inventory' => 'Inventory', 'Revenue Cycle' => 'Revenue Cycle', 'Payroll' => 'Payroll', 'Asset Management' => 'Asset Management', 'Risk Management' => 'Risk Management', 'Other' => 'Other'])->searchable(),
                            DatePicker::make('audit_date')->label('Audit Date')->required(),
                            DatePicker::make('period_from')->label('Period From')->required(),
                            DatePicker::make('period_to')->label('Period To')->required(),
                            Textarea::make('audit_scope')->label('Audit Scope')->required(),
                            Textarea::make('objectives')->label('Objectives'),
                            Textarea::make('methodology')->label('Methodology'),
                            Textarea::make('documents_reviewed')->label('Documents Reviewed'),
                            Textarea::make('persons_interviewed')->label('Persons Interviewed'),
                            Textarea::make('summary')->label('Summary'),
                            Textarea::make('key_findings')->label('Key Findings'),
                            Textarea::make('recommendations')->label('Recommendations'),
                            Textarea::make('management_response')->label('Management Response'),
                            Textarea::make('action_plan')->label('Action Plan'),
                            TextInput::make('financial_impact')->label('Financial Impact')->numeric(),
                            Select::make('risk_level')->label('Risk Level')->required()->options(['Low' => 'Low', 'Moderate' => 'Moderate', 'High' => 'High', 'Critical' => 'Critical'])->searchable(),
                            Select::make('status')->label('Status')->required()->options(['Planned' => 'Planned', 'In Progress' => 'In Progress', 'Draft Report' => 'Draft Report', 'Final Report' => 'Final Report', 'Management Response' => 'Management Response', 'Action Plan' => 'Action Plan', 'Closed' => 'Closed', 'Cancelled' => 'Cancelled'])->searchable(),
                            Textarea::make('notes')->label('Notes'),
                        ]),
                    ]),
            ]);
    }
}
