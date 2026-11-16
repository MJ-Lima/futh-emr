<?php

namespace App\Filament\Resources\MedicalAuditFindings\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class MedicalAuditFindingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('MedicalAuditFinding Information')
                    ->schema([
                        TextEntry::make('audit.audit_number')->label('Audit'),
                        TextEntry::make('finding_number')->label('Finding #'),
                        TextEntry::make('category')->label('Category'),
                        TextEntry::make('title')->label('Title'),
                        TextEntry::make('severity')->label('Severity'),
                        TextEntry::make('risk_level')->label('Risk'),
                        TextEntry::make('status')->label('Status'),
                        TextEntry::make('target_completion_date')->label('Target Date')->date(),
                    ])
                    ->columns(2),
            ]);
    }
}
