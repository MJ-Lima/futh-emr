<?php

namespace App\Filament\Resources\AuditFollowUps\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class AuditFollowUpInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('AuditFollowUp Information')
                    ->schema([
                        TextEntry::make('follow_up_number')->label('Follow-up #'),
                        TextEntry::make('audit_type')->label('Audit Type'),
                        TextEntry::make('follow_up_date')->label('Date')->date(),
                        TextEntry::make('progress_status')->label('Progress'),
                        TextEntry::make('completion_percentage')->label('% Complete'),
                        TextEntry::make('follower.name')->label('By'),
                        TextEntry::make('next_follow_up_date')->label('Next Follow-up')->date(),
                    ])
                    ->columns(2),
            ]);
    }
}
