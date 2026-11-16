<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\PermissionRegistrar;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected ?array $pendingPermissions = null;

    /**
     * Pull 'permissions' out of the form data before Filament saves.
     * The field is marked ->dehydrated(false) in RoleResource::form(),
     * so without this hook the value would be discarded.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pendingPermissions = $data['permissions'] ?? null;
        unset($data['permissions']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $role = $this->record;

        if ($this->pendingPermissions !== null && !empty($this->pendingPermissions)) {
            // givePermissionTo() accepts an array of permission names and properly
            // clears the cache after attaching each one.
            $role->givePermissionTo($this->pendingPermissions);

            // Belt-and-suspenders: explicitly clear the cache.
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
}
