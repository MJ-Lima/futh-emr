<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FuthPermissionsBatch2Seeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Syncing FUTH Lafia EMR permissions batch 2...');

        $newResources = [
            'ward', 'bed', 'admission', 'bedTransfer', 'dischargeSummary',
            'chartOfAccount', 'journalEntry', 'journalLine', 'budget',
            'expenseClaim', 'pettyCashTransaction',
            'appointmentType', 'clinicSchedule', 'providerSchedule',
            'appointmentSlot', 'holiday', 'leaveRequest',
        ];

        $actions = ['view', 'view_any', 'create', 'update', 'restore', 'restore_any',
                    'replicate', 'reorder', 'delete', 'delete_any',
                    'force_delete', 'force_delete_any'];

        foreach ($newResources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => $action . '_' . $resource,
                    'guard_name' => 'web',
                ]);
            }
        }

        $rolePerms = [
            'Super Admin' => ['*'],

            'Medical Administrator' => [
                'view_any_ward', 'view_ward', 'create_ward', 'update_ward',
                'view_any_bed', 'view_bed', 'create_bed', 'update_bed',
                'view_any_admission', 'view_admission', 'create_admission', 'update_admission',
                'view_any_bedTransfer', 'view_bedTransfer', 'create_bedTransfer', 'update_bedTransfer',
                'view_any_dischargeSummary', 'view_dischargeSummary', 'create_dischargeSummary', 'update_dischargeSummary',
                'view_any_appointmentType', 'view_appointmentType', 'create_appointmentType', 'update_appointmentType',
                'view_any_clinicSchedule', 'view_clinicSchedule', 'create_clinicSchedule', 'update_clinicSchedule',
                'view_any_providerSchedule', 'view_providerSchedule', 'create_providerSchedule', 'update_providerSchedule',
                'view_any_appointmentSlot', 'view_appointmentSlot', 'create_appointmentSlot', 'update_appointmentSlot',
                'view_any_holiday', 'view_holiday', 'create_holiday', 'update_holiday',
                'view_any_leaveRequest', 'view_leaveRequest', 'create_leaveRequest', 'update_leaveRequest',
                'view_any_chartOfAccount', 'view_chartOfAccount',
                'view_any_budget', 'view_budget', 'create_budget', 'update_budget',
                'view_any_expenseClaim', 'view_expenseClaim', 'create_expenseClaim', 'update_expenseClaim',
                'view_any_journalEntry', 'view_journalEntry',
            ],

            'Doctor' => [
                'view_any_ward', 'view_ward',
                'view_any_bed', 'view_bed',
                'view_any_admission', 'view_admission', 'create_admission', 'update_admission',
                'view_any_bedTransfer', 'view_bedTransfer', 'create_bedTransfer',
                'view_any_dischargeSummary', 'view_dischargeSummary', 'create_dischargeSummary', 'update_dischargeSummary',
                'view_any_appointmentType', 'view_appointmentType',
                'view_any_clinicSchedule', 'view_clinicSchedule',
                'view_any_providerSchedule', 'view_providerSchedule',
                'view_any_appointmentSlot', 'view_appointmentSlot', 'update_appointmentSlot',
                'view_any_leaveRequest', 'view_leaveRequest', 'create_leaveRequest',
            ],

            'Nurse' => [
                'view_any_ward', 'view_ward',
                'view_any_bed', 'view_bed', 'update_bed',
                'view_any_admission', 'view_admission', 'update_admission',
                'view_any_bedTransfer', 'view_bedTransfer',
                'view_any_dischargeSummary', 'view_dischargeSummary',
                'view_any_appointmentSlot', 'view_appointmentSlot',
                'view_any_leaveRequest', 'view_leaveRequest', 'create_leaveRequest',
            ],

            'Pharmacist' => [
                'view_any_appointmentSlot', 'view_appointmentSlot',
                'view_any_leaveRequest', 'view_leaveRequest', 'create_leaveRequest',
            ],

            'Medical Records Officer' => [
                'view_any_ward', 'view_ward',
                'view_any_bed', 'view_bed',
                'view_any_admission', 'view_admission',
                'view_any_dischargeSummary', 'view_dischargeSummary',
                'view_any_appointmentType', 'view_appointmentType',
                'view_any_clinicSchedule', 'view_clinicSchedule',
                'view_any_providerSchedule', 'view_providerSchedule',
                'view_any_appointmentSlot', 'view_appointmentSlot',
                'view_any_holiday', 'view_holiday',
                'view_any_leaveRequest', 'view_leaveRequest',
            ],

            'Accountant' => [
                'view_any_chartOfAccount', 'view_chartOfAccount', 'create_chartOfAccount', 'update_chartOfAccount',
                'view_any_journalEntry', 'view_journalEntry', 'create_journalEntry', 'update_journalEntry',
                'view_any_journalLine', 'view_journalLine', 'create_journalLine', 'update_journalLine',
                'view_any_budget', 'view_budget', 'create_budget', 'update_budget',
                'view_any_expenseClaim', 'view_expenseClaim', 'create_expenseClaim', 'update_expenseClaim',
                'view_any_pettyCashTransaction', 'view_pettyCashTransaction', 'create_pettyCashTransaction', 'update_pettyCashTransaction',
                'view_any_admission', 'view_admission',
                'view_any_leaveRequest', 'view_leaveRequest',
            ],

            'Insurance Officer' => [
                'view_any_admission', 'view_admission',
                'view_any_dischargeSummary', 'view_dischargeSummary',
                'view_any_appointmentSlot', 'view_appointmentSlot',
            ],
        ];

        foreach ($rolePerms as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            if ($perms === ['*']) {
                $role->syncPermissions(Permission::all());
            } else {
                // Use givePermissionTo to ADD to existing permissions (not sync, which would remove prior ones)
                foreach ($perms as $p) {
                    $role->givePermissionTo($p);
                }
            }
        }

        $this->command->info('Permissions batch 2 synced.');
    }
}
