<?php

namespace App\Filament\Resources\BloodCrossmatches;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\BloodCrossmatches\Pages\CreateBloodCrossmatch;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\BloodCrossmatches\Pages\EditBloodCrossmatch;
use App\Filament\Resources\BloodCrossmatches\Pages\ListBloodCrossmatches;
use App\Filament\Resources\BloodCrossmatches\Pages\ViewBloodCrossmatch;
use App\Filament\Resources\BloodCrossmatches\Schemas\BloodCrossmatchForm;
use App\Filament\Resources\BloodCrossmatches\Schemas\BloodCrossmatchInfolist;
use App\Filament\Resources\BloodCrossmatches\Tables\BloodCrossmatchesTable;
use App\Models\BloodCrossmatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BloodCrossmatchResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedArrowPathRoundedSquare;
    }

    public static function getNavigationGroup(): string
    {
        return 'Blood Bank';
    }
    public static function getNavigationSort(): ?int
    {
        return 5;
    }
    protected static ?string $model = BloodCrossmatch::class;

    protected static ?string $recordTitleAttribute = 'crossmatch_number';

    public static function form(Schema $schema): Schema
    {
        return BloodCrossmatchForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BloodCrossmatchInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BloodCrossmatchesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBloodCrossmatches::route('/'),
            'create' => CreateBloodCrossmatch::route('/create'),
            'view'   => ViewBloodCrossmatch::route('/{record}'),
            'edit'   => EditBloodCrossmatch::route('/{record}/edit'),
        ];
    }
}
