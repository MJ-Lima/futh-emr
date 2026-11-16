<?php

namespace App\Filament\Resources\ServicePaymentRules;

use App\Filament\Traits\HasRoleBasedAccess;

use App\Filament\Resources\ServicePaymentRules\Pages\CreateServicePaymentRule;
use App\Filament\Resources\ServicePaymentRules\Pages\EditServicePaymentRule;
use App\Filament\Resources\ServicePaymentRules\Pages\ListServicePaymentRules;
use App\Filament\Resources\ServicePaymentRules\Pages\ViewServicePaymentRule;
use App\Filament\Resources\ServicePaymentRules\Schemas\ServicePaymentRuleForm;
use App\Filament\Resources\ServicePaymentRules\Schemas\ServicePaymentRuleInfolist;
use App\Filament\Resources\ServicePaymentRules\Tables\ServicePaymentRulesTable;
use App\Models\ServicePaymentRule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServicePaymentRuleResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedCog6Tooth;
    }

    public static function getNavigationGroup(): string
    {
        return 'Administration';
    }
    public static function getNavigationLabel(): string
    {
        return 'Payment Rules';
    }

    public static function getNavigationSort(): ?int
    {
        return 28;
    }
    protected static ?string $model = ServicePaymentRule::class;

    protected static ?string $recordTitleAttribute = 'service_name';

    public static function form(Schema $schema): Schema { return ServicePaymentRuleForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return ServicePaymentRuleInfolist::configure($schema); }
    public static function table(Table $table): Table { return ServicePaymentRulesTable::configure($table); }

    public static function getPages(): array {
        return [
            'index' => ListServicePaymentRules::route('/'),
            'create' => CreateServicePaymentRule::route('/create'),
            'view' => ViewServicePaymentRule::route('/{record}'),
            'edit' => EditServicePaymentRule::route('/{record}/edit'),
        ];
    }
}
