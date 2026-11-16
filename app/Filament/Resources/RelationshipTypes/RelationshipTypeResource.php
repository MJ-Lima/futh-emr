<?php

namespace App\Filament\Resources\RelationshipTypes;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\RelationshipTypes\Pages\CreateRelationshipType;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\RelationshipTypes\Pages\EditRelationshipType;
use App\Filament\Resources\RelationshipTypes\Pages\ListRelationshipTypes;
use App\Filament\Resources\RelationshipTypes\Pages\ViewRelationshipType;
use App\Filament\Resources\RelationshipTypes\Schemas\RelationshipTypeForm;
use App\Filament\Resources\RelationshipTypes\Schemas\RelationshipTypeInfolist;
use App\Filament\Resources\RelationshipTypes\Tables\RelationshipTypesTable;
use App\Models\RelationshipType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RelationshipTypeResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedSquares2x2;
    }

    public static function getNavigationGroup(): string
    {
        return 'Patient Management';
    }
    public static function getNavigationSort(): ?int
    {
        return 6;
    }
    protected static ?string $model = RelationshipType::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return RelationshipTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RelationshipTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RelationshipTypesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRelationshipTypes::route('/'),
            'create' => CreateRelationshipType::route('/create'),
            'view'   => ViewRelationshipType::route('/{record}'),
            'edit'   => EditRelationshipType::route('/{record}/edit'),
        ];
    }
}
