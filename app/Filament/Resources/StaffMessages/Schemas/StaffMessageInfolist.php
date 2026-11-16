<?php

namespace App\Filament\Resources\StaffMessages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StaffMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Message')
                ->schema([
                    TextEntry::make('sender.name')->label('From'),
                    TextEntry::make('recipient.name')->label('To')->placeholder('—'),
                    TextEntry::make('subject')->placeholder('(no subject)'),
                    TextEntry::make('priority')->badge()->color(fn ($state) => match ($state) {
                        'Urgent'     => 'danger',
                        'Important'  => 'warning',
                        default       => 'gray',
                    }),
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('body')->markdown()->columnSpanFull(),
                ])->columns(3),
        ]);
    }
}
