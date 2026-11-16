<?php

namespace App\Filament\Resources\Users\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use App\Models\Role;
class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Account Information')
                ->icon('heroicon-m-user-circle')
                ->description('User identity and credentials')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->placeholder('e.g. Dr. Okoro Emmanuel'),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText(fn (string $operation): string =>
                                $operation === 'edit' ? 'Leave blank to keep current password.' : 'Choose a strong password.'),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->placeholder('e.g. 08031234567'),
                    ]),
                ]),

            Section::make('Role & Permissions')
                ->icon('heroicon-m-shield-check')
                ->description('Assign one or more roles. Changes take effect immediately — Spatie\'s cache is cleared and the user\'s session is invalidated on save.')
                ->schema([
                    // IMPORTANT: We use ->options() instead of ->relationship() because
                    // ->relationship() writes directly to the model_has_roles pivot table,
                    // bypassing Spatie's syncRoles() method. That means Spatie's permission
                    // cache is never invalidated, and role changes "don't reflect sometimes".
                    // By using ->options() + dehydrated(false), the roles field is NOT saved
                    // automatically by Filament. Instead, the EditUser/CreateUser pages handle
                    // it manually via afterSave/afterCreate hooks that call $user->syncRoles()
                    // — which properly clears the cache and triggers the right events.
                    Select::make('roles')
                        ->label('Roles')
                        ->options(fn () => Role::pluck('name', 'name')->sort()->toArray())
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->dehydrated(false) // Don't save via Filament's auto-relationship logic
                        ->afterStateHydrated(function (Select $component, $state, $record) {
                            // When editing, populate the field with the user's current role names
                            if ($record) {
                                $component->state($record->roles->pluck('name')->toArray());
                            }
                        })
                        ->helperText('Select one or more roles. Changes are saved via Spatie\'s syncRoles() so the cache is properly cleared.'),
                    ToggleButtons::make('status')
                        ->label('Account Status')
                        ->options([
                            'active'    => 'Active',
                            'inactive'  => 'Inactive',
                            'suspended' => 'Suspended',
                            'locked'    => 'Locked',
                        ])
                        ->colors([
                            'active'    => 'success',
                            'inactive'  => 'gray',
                            'suspended' => 'warning',
                            'locked'    => 'danger',
                        ])
                        ->icons([
                            'active'    => 'heroicon-m-check-circle',
                            'inactive'  => 'heroicon-m-x-circle',
                            'suspended' => 'heroicon-m-exclamation-triangle',
                            'locked'    => 'heroicon-m-lock-closed',
                        ])
                        ->inline()
                        ->default('active')
                        ->required(),
                ]),

            Section::make('Hospital Assignment')
                ->icon('heroicon-m-building-office-2')
                ->description('Where this user is currently posted')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('hospital_id')
                            ->label('Hospital')
                            ->relationship('hospital', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),
                        Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false),
                        Select::make('default_clinic_id')
                            ->label('Default Clinic')
                            ->relationship('defaultClinic', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false),
                    ]),
                ]),
        ]);
    }
}
