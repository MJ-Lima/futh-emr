<?php

namespace App\Filament\Resources\ProcedureSessions;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ProcedureSessions\Pages\CreateProcedureSession;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\ProcedureSessions\Pages\EditProcedureSession;
use App\Filament\Resources\ProcedureSessions\Pages\ListProcedureSessions;
use App\Filament\Resources\ProcedureSessions\Pages\ViewProcedureSession;
use App\Filament\Resources\ProcedureSessions\Schemas\ProcedureSessionForm;
use App\Filament\Resources\ProcedureSessions\Schemas\ProcedureSessionInfolist;
use App\Filament\Resources\ProcedureSessions\Tables\ProcedureSessionsTable;
use App\Models\ProcedureSession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProcedureSessionResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedPlayCircle;
    }

    public static function getNavigationGroup(): string
    {
        return 'Procedures';
    }
    public static function getNavigationSort(): ?int
    {
        return 4;
    }
    protected static ?string $model = ProcedureSession::class;

    protected static ?string $recordTitleAttribute = 'session_number';

    public static function form(Schema $schema): Schema
    {
        return ProcedureSessionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProcedureSessionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProcedureSessionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListProcedureSessions::route('/'),
            'create' => CreateProcedureSession::route('/create'),
            'view'   => ViewProcedureSession::route('/{record}'),
            'edit'   => EditProcedureSession::route('/{record}/edit'),
        ];
    }
}
