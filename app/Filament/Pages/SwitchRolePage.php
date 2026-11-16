<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Spatie\Permission\PermissionRegistrar;

class SwitchRolePage extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected string $view = 'filament.pages.switch-role';

    protected static ?string $title = 'Switch Role';

    protected static \UnitEnum|string|null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 99;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && $user->hasMultipleRoles();
    }

    public function getViewData(): array
    {
        $user = auth()->user();

        return [
            'roles'      => $user->roles()->orderBy('name')->get(),
            'activeRole' => $user->getActiveRole(),
        ];
    }

    public function switchRole(string $roleName): void
    {
        $user = auth()->user();

        if (! $user->hasRole($roleName)) {
            Notification::make()
                ->title('You do not have that role.')
                ->danger()
                ->send();
            return;
        }

        $user->switchActiveRole($roleName);

        // Clear the permission cache so the new active role takes effect
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Notification::make()
            ->title("Active role switched to: {$roleName}")
            ->success()
            ->send();

        redirect()->route('filament.admin.pages.dashboard');
    }
}
