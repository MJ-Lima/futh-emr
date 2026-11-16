<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\PermissionRegistrar;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected ?array $pendingPermissions = null;

    /**
     * Pull 'permissions' out of the form data before Filament saves.
     * The field is marked ->dehydrated(false) in RoleResource::form(),
     * so without this hook the value would be discarded.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingPermissions = $data['permissions'] ?? null;
        unset($data['permissions']);

        return $data;
    }

    protected function afterSave(): void
    {
        $role = $this->record;

        if ($this->pendingPermissions !== null) {
            // syncPermissions() writes to role_has_permissions AND clears the cache.
            $role->syncPermissions($this->pendingPermissions);

            // Belt-and-suspenders: explicitly clear the cache.
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
