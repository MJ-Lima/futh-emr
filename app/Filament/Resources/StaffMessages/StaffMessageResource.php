<?php

namespace App\Filament\Resources\StaffMessages;

use App\Filament\Resources\StaffMessages\Pages\ListStaffMessages;
use App\Filament\Resources\StaffMessages\Pages\ViewStaffMessage;
use App\Filament\Resources\StaffMessages\Schemas\StaffMessageInfolist;
use App\Filament\Resources\StaffMessages\Tables\StaffMessagesTable;
use App\Models\StaffMessage;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StaffMessageResource extends Resource
{
    protected static ?string $model = StaffMessage::class;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedEnvelope;
    }

    public static function getNavigationLabel(): string
    {
        return 'Staff Messages';
    }

    public static function getNavigationGroup(): string
    {
        return 'Administration';
    }

    public static function getNavigationBadge(): ?string
    {
        $user = auth()->user();
        if (! $user) return null;
        $count = StaffMessage::where('recipient_id', $user->id)->whereNull('read_at')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function infolist(Schema $schema): Schema
    {
        return StaffMessageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffMessagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStaffMessages::route('/'),
            'view'  => ViewStaffMessage::route('/{record}'),
        ];
    }
}
