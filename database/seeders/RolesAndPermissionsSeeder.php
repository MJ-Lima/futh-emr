<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // EMR-specific permissions (filament-shield-compatible names)
        $modules = [
            'patient', 'encounter', 'appointment',
            'clinicalNote', 'vitals', 'allergy', 'diagnosis',
            'labPanel', 'labTest', 'labOrder',
            'radiologyModality', 'radiologyOrder',
            'drugCategory', 'drug', 'drugBatch', 'drugInteraction',
            'supplier', 'purchase', 'receipt',
            'dispensingPoint', 'prescription', 'dispensingLog',
            'stockMovement', 'stockReturn',
            'hospital', 'clinic', 'department', 'staff', 'user', 'role',
        ];

        $actions = ['view', 'view_any', 'create', 'update', 'delete', 'restore', 'force_delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action}_{$module}", 'guard_name' => 'web']);
            }
        }

        // EMR roles aligned with international hospital staffing standards
        $roles = [
            'Super Admin' => Permission::all()->pluck('name')->toArray(),
            'Medical Administrator' => array_merge(
                $this->permissionsFor(['patient', 'encounter', 'appointment', 'clinicalNote', 'vitals', 'allergy', 'diagnosis', 'labOrder', 'radiologyOrder', 'prescription']),
                $this->readOnlyFor(['labPanel', 'labTest', 'radiologyModality', 'drug', 'drugBatch', 'supplier', 'purchase', 'receipt', 'dispensingPoint', 'dispensingLog', 'stockMovement', 'stockReturn', 'hospital', 'clinic', 'department', 'staff', 'user']),
                ['view_any_role', 'view_role']
            ),
            'Doctor' => array_merge(
                $this->permissionsFor(['patient', 'encounter', 'appointment', 'clinicalNote', 'vitals', 'allergy', 'diagnosis', 'labOrder', 'radiologyOrder', 'prescription']),
                $this->readOnlyFor(['labPanel', 'labTest', 'radiologyModality', 'drug', 'drugBatch', 'drugInteraction'])
            ),
            'Nurse' => array_merge(
                $this->permissionsFor(['patient', 'encounter', 'vitals', 'clinicalNote', 'allergy']),
                $this->readOnlyFor(['appointment', 'diagnosis', 'labOrder', 'radiologyOrder', 'prescription', 'drug'])
            ),
            'Pharmacist' => array_merge(
                $this->permissionsFor(['drug', 'drugBatch', 'drugCategory', 'drugInteraction', 'supplier', 'purchase', 'receipt', 'dispensingPoint', 'prescription', 'dispensingLog', 'stockMovement', 'stockReturn']),
                $this->readOnlyFor(['patient', 'encounter'])
            ),
            'Laboratory Scientist' => array_merge(
                $this->permissionsFor(['labPanel', 'labTest', 'labOrder']),
                $this->readOnlyFor(['patient', 'encounter'])
            ),
            'Radiographer' => array_merge(
                $this->permissionsFor(['radiologyModality', 'radiologyOrder']),
                $this->readOnlyFor(['patient', 'encounter'])
            ),
            'Medical Records Officer' => array_merge(
                $this->permissionsFor(['patient', 'encounter', 'appointment']),
                $this->readOnlyFor(['clinicalNote', 'vitals', 'allergy', 'diagnosis', 'labOrder', 'radiologyOrder', 'prescription'])
            ),
            'Accountant' => $this->readOnlyFor(['patient', 'encounter', 'purchase', 'receipt', 'dispensingLog', 'stockReturn']),
        ];

        foreach ($roles as $name => $permissions) {
            $role = Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            $role->syncPermissions($permissions);
        }
    }

    private function permissionsFor(array $modules): array
    {
        $actions = ['view', 'view_any', 'create', 'update', 'delete', 'restore', 'force_delete'];
        $result = [];
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $result[] = "{$action}_{$module}";
            }
        }
        return $result;
    }

    private function readOnlyFor(array $modules): array
    {
        $result = [];
        foreach ($modules as $module) {
            $result[] = "view_{$module}";
            $result[] = "view_any_{$module}";
        }
        return $result;
    }
}
