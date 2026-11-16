<?php

namespace App\Filament\Resources\SavedReports\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class SavedReportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('SavedReport Information')
                    ->schema([
                        TextEntry::make('report_number')->label('Report #'),
                        TextEntry::make('title')->label('Title'),
                        TextEntry::make('category')->label('Category'),
                        TextEntry::make('period_from')->label('From')->date(),
                        TextEntry::make('period_to')->label('To')->date(),
                        TextEntry::make('record_count')->label('Records'),
                        TextEntry::make('format')->label('Format'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
