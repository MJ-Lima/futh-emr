<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    protected ?array $pendingRoles = null;

    /**
     * Pull 'roles' out of the form data before Filament tries to save it
     * as a regular column. It's marked ->dehydrated(false) in UserForm,
     * but we use this hook to stash the value for afterCreate().
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pendingRoles = $data['roles'] ?? null;
        unset($data['roles']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $user = $this->record;

        // Mark email as verified for new users
        $user->email_verified_at = now();
        $user->save();

        // Assign roles via Spatie's assignRole() — this properly writes to
        // model_has_roles AND clears the permission cache.
        if ($this->pendingRoles !== null && !empty($this->pendingRoles)) {
            $user->assignRole($this->pendingRoles);

            // Explicit cache clear — belt and suspenders
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
