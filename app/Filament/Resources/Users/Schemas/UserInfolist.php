<?php

namespace App\Filament\Resources\Users\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Account Information')
                ->icon('heroicon-m-user-circle')
                ->schema([
                    TextEntry::make('name')->label('Full Name'),
                    TextEntry::make('email')->label('Email Address')->copyable(),
                    TextEntry::make('phone')->label('Phone')->placeholder('—'),
                    TextEntry::make('status')->label('Account Status')->badge()
                        ->color(fn ($s) => match ($s) {
                            'active'    => 'success',
                            'inactive'  => 'gray',
                            'suspended' => 'warning',
                            'locked'    => 'danger',
                            default      => 'gray',
                        }),
                ])->columns(2),

            Section::make('Roles & Permissions')
                ->icon('heroicon-m-shield-check')
                ->schema([
                    TextEntry::make('roles.name')
                        ->label('Assigned Roles')
                        ->badge()
                        ->color('info')
                        ->placeholder('No roles assigned'),
                ]),

            Section::make('Hospital Assignment')
                ->icon('heroicon-m-building-office-2')
                ->schema([
                    TextEntry::make('hospital.name')->label('Hospital')->placeholder('—'),
                    TextEntry::make('department.name')->label('Department')->placeholder('—'),
                    TextEntry::make('defaultClinic.name')->label('Default Clinic')->placeholder('—'),
                    TextEntry::make('last_login_at')->label('Last Login')->dateTime('d M Y, H:i')->placeholder('Never'),
                ])->columns(2),
        ]);
    }
}
