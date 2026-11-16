<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->visible(true),
            DeleteAction::make()->visible(true),
        ];
    }

    /**
     * Handle role assignment BEFORE the record is saved.
     *
     * We pull the 'roles' field out of the form data (it's marked ->dehydrated(false)
     * in UserForm, so Filament would otherwise discard it). Then we call syncRoles()
     * after the user is saved — syncRoles() properly clears Spatie's permission cache
     * and triggers all the right events.
     *
     * This replaces the broken pattern of Select::make('roles')->relationship('roles', 'name')
     * which writes directly to model_has_roles and bypasses Spatie's cache invalidation.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Stash the roles in a property so afterSave() can use them.
        // The 'roles' key is removed from $data because we don't want Filament
        // to try to save it as a regular column.
        $this->pendingRoles = $data['roles'] ?? null;
        unset($data['roles']);

        return $data;
    }

    protected ?array $pendingRoles = null;

    protected function afterSave(): void
    {
        $user = $this->record;

        // Only sync if the roles field was actually submitted
        if ($this->pendingRoles !== null) {
            // syncRoles() will:
            //  1. Write to model_has_roles pivot
            //  2. Detach roles no longer in the list
            //  3. Fire Spatie's internal events
            //  4. Clear the permission cache via the registrar
            $user->syncRoles($this->pendingRoles);

            // Belt-and-suspenders: explicitly clear the cache. In rare cases
            // (e.g. if the user model isn't booting the HasRoles trait properly,
            // or if cache store is shared across workers), the cache clear inside
            // syncRoles() can be missed.
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            // Invalidate ALL active sessions for this user — forces re-login so
            // the new roles take effect immediately. Without this, the user's
            // existing session keeps the old roles until they re-login or the
            // session expires.
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
