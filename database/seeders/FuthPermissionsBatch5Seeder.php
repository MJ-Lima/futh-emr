<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FuthPermissionsBatch5Seeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Syncing General Store permissions...');
        $newResources = [
            'generalStoreCategory', 'generalStoreLocation', 'generalStoreItem',
            'generalStoreStock', 'generalStoreMovement', 'generalStoreRequisition',
        ];
        $actions = ['view', 'view_any', 'create', 'update', 'restore', 'restore_any',
                    'replicate', 'reorder', 'delete', 'delete_any', 'force_delete', 'force_delete_any'];
        foreach ($newResources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => $action . '_' . $resource, 'guard_name' => 'web']);
            }
        }
        $rolePerms = [
            'Super Admin' => ['*'],
            'Medical Administrator' => [
                'view_any_generalStoreCategory', 'view_generalStoreCategory', 'create_generalStoreCategory', 'update_generalStoreCategory',
                'view_any_generalStoreLocation', 'view_generalStoreLocation', 'create_generalStoreLocation', 'update_generalStoreLocation',
                'view_any_generalStoreItem', 'view_generalStoreItem', 'create_generalStoreItem', 'update_generalStoreItem',
                'view_any_generalStoreStock', 'view_generalStoreStock', 'create_generalStoreStock', 'update_generalStoreStock',
                'view_any_generalStoreMovement', 'view_generalStoreMovement', 'create_generalStoreMovement',
                'view_any_generalStoreRequisition', 'view_generalStoreRequisition', 'create_generalStoreRequisition', 'update_generalStoreRequisition',
            ],
            'Accountant' => [
                'view_any_generalStoreItem', 'view_generalStoreItem',
                'view_any_generalStoreStock', 'view_generalStoreStock',
                'view_any_generalStoreMovement', 'view_generalStoreMovement',
                'view_any_generalStoreRequisition', 'view_generalStoreRequisition',
            ],
            'Medical Records Officer' => [
                'view_any_generalStoreItem', 'view_generalStoreItem',
                'view_any_generalStoreRequisition', 'view_generalStoreRequisition', 'create_generalStoreRequisition',
            ],
        ];
        foreach ($rolePerms as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            if ($perms === ['*']) {
                $role->syncPermissions(Permission::all());
            } else {
                foreach ($perms as $p) $role->givePermissionTo($p);
            }
        }
        $this->command->info('General Store permissions synced.');
    }
}
