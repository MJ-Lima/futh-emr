<?php

namespace App\Filament\Resources\InternalAudits\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class InternalAuditInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('InternalAudit Information')
                    ->schema([
                        TextEntry::make('audit_number')->label('Audit #'),
                        TextEntry::make('audit_type')->label('Type'),
                        TextEntry::make('department.name')->label('Department'),
                        TextEntry::make('auditor.name')->label('Auditor'),
                        TextEntry::make('audit_date')->label('Date')->date(),
                        TextEntry::make('financial_impact')->label('Fin. Impact'),
                        TextEntry::make('risk_level')->label('Risk'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
