<?php

namespace App\Filament\Resources\Beds;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\Beds\Pages\CreateBed;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\Beds\Pages\EditBed;
use App\Filament\Resources\Beds\Pages\ListBeds;
use App\Filament\Resources\Beds\Pages\ViewBed;
use App\Filament\Resources\Beds\Schemas\BedForm;
use App\Filament\Resources\Beds\Schemas\BedInfolist;
use App\Filament\Resources\Beds\Tables\BedsTable;
use App\Models\Bed;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BedResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedRectangleStack;
    }

    public static function getNavigationGroup(): string
    {
        return 'Ward & Bed Management';
    }
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    protected static ?string $model = Bed::class;

    protected static ?string $recordTitleAttribute = 'bed_number';

    public static function form(Schema $schema): Schema
    {
        return BedForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BedInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BedsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBeds::route('/'),
            'create' => CreateBed::route('/create'),
            'view'   => ViewBed::route('/{record}'),
            'edit'   => EditBed::route('/{record}/edit'),
        ];
    }
}
