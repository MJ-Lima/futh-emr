<?php

namespace App\Filament\Resources\ConsultationRooms;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ConsultationRooms\Pages\CreateConsultationRoom;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ConsultationRooms\Pages\EditConsultationRoom;
use App\Filament\Resources\ConsultationRooms\Pages\ListConsultationRooms;
use App\Filament\Resources\ConsultationRooms\Pages\ViewConsultationRoom;
use App\Filament\Resources\ConsultationRooms\Schemas\ConsultationRoomForm;
use App\Filament\Resources\ConsultationRooms\Schemas\ConsultationRoomInfolist;
use App\Filament\Resources\ConsultationRooms\Tables\ConsultationRoomsTable;
use App\Models\ConsultationRoom;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConsultationRoomResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedHomeModern;
    }

    public static function getNavigationGroup(): string
    {
        return 'Front Desk';
    }
    public static function getNavigationLabel(): string
    {
        return 'Consultation Rooms';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }
    protected static ?string $model = ConsultationRoom::class;

    public static function form(Schema $schema): Schema
    {
        return ConsultationRoomForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ConsultationRoomInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConsultationRoomsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListConsultationRooms::route('/'),
            'create' => CreateConsultationRoom::route('/create'),
            'view'   => ViewConsultationRoom::route('/{record}'),
            'edit'   => EditConsultationRoom::route('/{record}/edit'),
        ];
    }
}
