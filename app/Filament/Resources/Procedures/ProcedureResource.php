<?php

namespace App\Filament\Resources\Procedures;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\Procedures\Pages\CreateProcedure;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\Procedures\Pages\EditProcedure;
use App\Filament\Resources\Procedures\Pages\ListProcedures;
use App\Filament\Resources\Procedures\Pages\ViewProcedure;
use App\Filament\Resources\Procedures\Schemas\ProcedureForm;
use App\Filament\Resources\Procedures\Schemas\ProcedureInfolist;
use App\Filament\Resources\Procedures\Tables\ProceduresTable;
use App\Models\Procedure;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProcedureResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedCog6Tooth;
    }

    public static function getNavigationGroup(): string
    {
        return 'Procedures';
    }
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    protected static ?string $model = Procedure::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return ProcedureForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProcedureInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProceduresTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListProcedures::route('/'),
            'create' => CreateProcedure::route('/create'),
            'view'   => ViewProcedure::route('/{record}'),
            'edit'   => EditProcedure::route('/{record}/edit'),
        ];
    }
}
