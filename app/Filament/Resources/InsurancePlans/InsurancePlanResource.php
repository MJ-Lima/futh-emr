<?php

namespace App\Filament\Resources\InsurancePlans;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\InsurancePlans\Pages\CreateInsurancePlan;
use App\Filament\Traits\HasUnitScoping;
use App\Filament\Resources\InsurancePlans\Pages\EditInsurancePlan;
use App\Filament\Resources\InsurancePlans\Pages\ListInsurancePlans;
use App\Filament\Resources\InsurancePlans\Pages\ViewInsurancePlan;
use App\Filament\Resources\InsurancePlans\Schemas\InsurancePlanForm;
use App\Filament\Resources\InsurancePlans\Schemas\InsurancePlanInfolist;
use App\Filament\Resources\InsurancePlans\Tables\InsurancePlansTable;
use App\Models\InsurancePlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InsurancePlanResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedRectangleGroup;
    }

    public static function getNavigationGroup(): string
    {
        return 'Insurance';
    }
    public static function getNavigationSort(): ?int
    {
        return 2;
    }
    protected static ?string $model = InsurancePlan::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return InsurancePlanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InsurancePlanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InsurancePlansTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListInsurancePlans::route('/'),
            'create' => CreateInsurancePlan::route('/create'),
            'view'   => ViewInsurancePlan::route('/{record}'),
            'edit'   => EditInsurancePlan::route('/{record}/edit'),
        ];
    }
}
