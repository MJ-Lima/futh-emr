<?php

namespace App\Filament\Resources\BloodTransfusions;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\BloodTransfusions\Pages\CreateBloodTransfusion;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\BloodTransfusions\Pages\EditBloodTransfusion;
use App\Filament\Resources\BloodTransfusions\Pages\ListBloodTransfusions;
use App\Filament\Resources\BloodTransfusions\Pages\ViewBloodTransfusion;
use App\Filament\Resources\BloodTransfusions\Schemas\BloodTransfusionForm;
use App\Filament\Resources\BloodTransfusions\Schemas\BloodTransfusionInfolist;
use App\Filament\Resources\BloodTransfusions\Tables\BloodTransfusionsTable;
use App\Models\BloodTransfusion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BloodTransfusionResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedHeart;
    }

    public static function getNavigationGroup(): string
    {
        return 'Blood Bank';
    }
    public static function getNavigationSort(): ?int
    {
        return 6;
    }
    protected static ?string $model = BloodTransfusion::class;

    protected static ?string $recordTitleAttribute = 'transfusion_number';

    public static function form(Schema $schema): Schema
    {
        return BloodTransfusionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BloodTransfusionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BloodTransfusionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBloodTransfusions::route('/'),
            'create' => CreateBloodTransfusion::route('/create'),
            'view'   => ViewBloodTransfusion::route('/{record}'),
            'edit'   => EditBloodTransfusion::route('/{record}/edit'),
        ];
    }
}
