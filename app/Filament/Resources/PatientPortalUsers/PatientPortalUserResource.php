<?php

namespace App\Filament\Resources\PatientPortalUsers;

use App\Filament\Resources\PatientPortalUsers\Pages\ListPatientPortalUsers;
use App\Filament\Resources\PatientPortalUsers\Pages\ViewPatientPortalUser;
use App\Filament\Resources\PatientPortalUsers\Schemas\PatientPortalUserInfolist;
use App\Filament\Resources\PatientPortalUsers\Tables\PatientPortalUsersTable;
use App\Models\PatientPortalUser;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatientPortalUserResource extends Resource
{
    protected static ?string $model = PatientPortalUser::class;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedUsers;
    }

    public static function getNavigationLabel(): string
    {
        return 'Patient Portal Users';
    }

    public static function getNavigationGroup(): string
    {
        return 'Administration';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) PatientPortalUser::where('status', 'pending')->count() ?: null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Forms\Components\Select::make('status')
                ->options(['pending' => 'Pending', 'active' => 'Active', 'suspended' => 'Suspended', 'locked' => 'Locked'])
                ->required(),
            \Filament\Forms\Components\TextInput::make('phone')->tel(),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PatientPortalUserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatientPortalUsersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatientPortalUsers::route('/'),
            'view'  => ViewPatientPortalUser::route('/{record}'),
        ];
    }
}
