<?php

namespace App\Filament\Resources\ProviderApplications;

use App\Filament\Resources\ProviderApplications\Pages\ListProviderApplications;
use App\Filament\Resources\ProviderApplications\Pages\ViewProviderApplication;
use App\Filament\Resources\ProviderApplications\Schemas\ProviderApplicationForm;
use App\Filament\Resources\ProviderApplications\Schemas\ProviderApplicationInfolist;
use App\Filament\Resources\ProviderApplications\Tables\ProviderApplicationsTable;
use App\Models\ProviderApplication;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProviderApplicationResource extends Resource
{
    protected static ?string $model = ProviderApplication::class;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedUserPlus;
    }

    public static function getNavigationLabel(): string
    {
        return 'Provider Applications';
    }

    public static function getNavigationGroup(): string
    {
        return 'Administration';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) ProviderApplication::where('status', 'pending')->count() ?: null;
    }

    public static function form(Schema $schema): Schema
    {
        return ProviderApplicationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProviderApplicationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProviderApplicationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProviderApplications::route('/'),
            'view'  => ViewProviderApplication::route('/{record}'),
        ];
    }
}
