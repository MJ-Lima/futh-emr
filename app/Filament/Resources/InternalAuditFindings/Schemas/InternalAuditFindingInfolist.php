<?php

namespace App\Filament\Resources\InternalAuditFindings\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class InternalAuditFindingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('InternalAuditFinding Information')
                    ->schema([
                        TextEntry::make('audit.audit_number')->label('Audit'),
                        TextEntry::make('finding_number')->label('Finding #'),
                        TextEntry::make('category')->label('Category'),
                        TextEntry::make('title')->label('Title'),
                        TextEntry::make('severity')->label('Severity'),
                        TextEntry::make('financial_impact')->label('Fin. Impact'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
