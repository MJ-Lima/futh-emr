<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FuthPermissionsBatch4Seeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Syncing FUTH Lafia EMR permissions batch 4 (Audit, Reports, Relationships)...');

        $newResources = [
            'auditCategory', 'medicalAudit', 'medicalAuditFinding',
            'internalAudit', 'internalAuditFinding', 'auditFollowUp',
            'patientDependant', 'relationshipType', 'patientRelationship',
            'reportTemplate', 'savedReport', 'reportSchedule',
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
                'view_any_auditCategory', 'view_auditCategory', 'create_auditCategory', 'update_auditCategory',
                'view_any_medicalAudit', 'view_medicalAudit', 'create_medicalAudit', 'update_medicalAudit',
                'view_any_medicalAuditFinding', 'view_medicalAuditFinding', 'create_medicalAuditFinding', 'update_medicalAuditFinding',
                'view_any_internalAudit', 'view_internalAudit', 'create_internalAudit', 'update_internalAudit',
                'view_any_internalAuditFinding', 'view_internalAuditFinding', 'create_internalAuditFinding', 'update_internalAuditFinding',
                'view_any_auditFollowUp', 'view_auditFollowUp', 'create_auditFollowUp', 'update_auditFollowUp',
                'view_any_patientDependant', 'view_patientDependant', 'create_patientDependant', 'update_patientDependant',
                'view_any_relationshipType', 'view_relationshipType', 'create_relationshipType', 'update_relationshipType',
                'view_any_patientRelationship', 'view_patientRelationship', 'create_patientRelationship', 'update_patientRelationship',
                'view_any_reportTemplate', 'view_reportTemplate', 'create_reportTemplate', 'update_reportTemplate',
                'view_any_savedReport', 'view_savedReport', 'create_savedReport',
                'view_any_reportSchedule', 'view_reportSchedule', 'create_reportSchedule', 'update_reportSchedule',
            ],
            'Doctor' => [
                'view_any_medicalAudit', 'view_medicalAudit',
                'view_any_medicalAuditFinding', 'view_medicalAuditFinding',
                'view_any_patientDependant', 'view_patientDependant',
                'view_any_patientRelationship', 'view_patientRelationship',
                'view_any_savedReport', 'view_savedReport',
            ],
            'Nurse' => [
                'view_any_patientDependant', 'view_patientDependant',
                'view_any_patientRelationship', 'view_patientRelationship',
                'view_any_savedReport', 'view_savedReport',
            ],
            'Medical Records Officer' => [
                'view_any_patientDependant', 'view_patientDependant', 'create_patientDependant', 'update_patientDependant',
                'view_any_relationshipType', 'view_relationshipType',
                'view_any_patientRelationship', 'view_patientRelationship', 'create_patientRelationship', 'update_patientRelationship',
                'view_any_savedReport', 'view_savedReport', 'create_savedReport',
                'view_any_reportTemplate', 'view_reportTemplate',
            ],
            'Accountant' => [
                'view_any_internalAudit', 'view_internalAudit', 'create_internalAudit', 'update_internalAudit',
                'view_any_internalAuditFinding', 'view_internalAuditFinding', 'create_internalAuditFinding', 'update_internalAuditFinding',
                'view_any_auditFollowUp', 'view_auditFollowUp',
                'view_any_reportTemplate', 'view_reportTemplate', 'create_reportTemplate',
                'view_any_savedReport', 'view_savedReport', 'create_savedReport',
                'view_any_reportSchedule', 'view_reportSchedule',
            ],
        ];

        foreach ($rolePerms as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            if ($perms === ['*']) {
                $role->syncPermissions(Permission::all());
            } else {
                foreach ($perms as $p) {
                    $role->givePermissionTo($p);
                }
            }
        }

        $this->command->info('Permissions batch 4 synced.');
    }
}
