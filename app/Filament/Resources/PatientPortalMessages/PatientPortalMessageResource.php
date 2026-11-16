<?php

namespace App\Filament\Resources\PatientPortalMessages;

use App\Filament\Resources\PatientPortalMessages\Pages\ListPatientPortalMessages;
use App\Filament\Resources\PatientPortalMessages\Pages\ViewPatientPortalMessage;
use App\Filament\Resources\PatientPortalMessages\Tables\PatientPortalMessagesTable;
use App\Models\PatientPortalMessage;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatientPortalMessageResource extends Resource
{
    protected static ?string $model = PatientPortalMessage::class;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedChatBubbleLeftRight;
    }

    public static function getNavigationLabel(): string
    {
        return 'Patient Messages';
    }

    public static function getNavigationGroup(): string
    {
        return 'Front Desk';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) PatientPortalMessage::where('direction', 'inbound')->whereNull('read_at')->count() ?: null;
    }

    public static function table(Table $table): Table
    {
        return PatientPortalMessagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatientPortalMessages::route('/'),
            'view'  => ViewPatientPortalMessage::route('/{record}'),
        ];
    }
}
