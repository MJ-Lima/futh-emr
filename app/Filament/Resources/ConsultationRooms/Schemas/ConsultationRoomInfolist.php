<?php

namespace App\Filament\Resources\ConsultationRooms\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class ConsultationRoomInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Room Details')
                ->icon('heroicon-m-home-modern')
                ->schema([
                    TextEntry::make('name')->label('Room Name'),
                    TextEntry::make('code')->label('Code')->badge()->color('info'),
                    TextEntry::make('clinic.name')->label('Clinic'),
                    TextEntry::make('hospital.name')->label('Hospital'),
                    TextEntry::make('capacity')->label('Capacity'),
                    TextEntry::make('assignedDoctor.name')->label('Default Doctor')->placeholder('—'),
                    TextEntry::make('is_active')->label('Active')->badge()
                        ->color(fn ($s) => $s ? 'success' : 'danger'),
                ])->columns(2),
        ]);
    }
}
