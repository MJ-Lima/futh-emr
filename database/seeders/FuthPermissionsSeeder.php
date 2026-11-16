<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Permission, Role};

class FuthPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Permission naming convention: filament-shield style
     *   Format:   "<Action>:<Resource>"
     *   Example:  "ViewAny:Patient", "Create:Patient", "Update:Patient"
     *
     * This matches:
     *   - config/filament-shield.php (case=pascal, separator=:)
     *   - app/Policies/*.php (PatientPolicy, UserPolicy, RolePolicy, etc.)
     *   - app/Filament/Pages/Dashboard.php permission checks
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ---------------------------------------------------------------
        // 1. DEFINE RESOURCES — those with actual database tables and/or
        //    Filament Resources referenced by the Dashboard or Policies.
        // ---------------------------------------------------------------
        $resources = [
            // System
            'User', 'Role',
            // Facility
            'Hospital', 'Department', 'Clinic',
            // Staff & Patients
            'Staff', 'Patient', 'Encounter', 'Appointment',
            // Clinical
            'Vitals', 'ClinicalNote', 'Allergy', 'Diagnosis',
            // Pharmacy
            'Drug', 'DrugBatch', 'Prescription',
            // Laboratory
            'LabOrder',
            // Radiology
            'RadiologyOrder',
            // Scheduling
            'DutyRoster', 'DutyPosting',
            // Insurance & Billing
            'InsuranceProvider', 'InsurancePlan', 'PatientInsurance',
            'InsuranceClaim', 'PreAuthorization',
            'Service', 'Invoice', 'Payment',
            // Wards & Admissions
            'Ward', 'Bed', 'Admission',
            // Blood Bank
            'BloodDonor', 'BloodUnit',
            // Others referenced by the Dashboard
            'ProcedureBooking',
        ];

        // Standard filament-shield CRUD+ permissions per resource
        $crudActions = ['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny'];

        foreach ($resources as $resource) {
            foreach ($crudActions as $action) {
                Permission::firstOrCreate([
                    'name'       => "{$action}:{$resource}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // System-level permissions (kept in human-readable form — these are
        // NOT auto-generated and are checked explicitly in code).
        $systemPermissions = [
            'ViewReportsDashboard',
            'ViewAuditLog',
            'ManageSettings',
            'ManageRoles',
            'ManagePermissions',
            'ExportData',
            'ImportData',
            'ViewAnyClinicData',
            'ViewAnyDepartmentData',
        ];

        foreach ($systemPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $this->command->info('Permissions seeded: ' . Permission::count() . ' total.');

        // ---------------------------------------------------------------
        // 2. DEFINE ROLES AND PERMISSION MAPPING
        // ---------------------------------------------------------------
        // Helper: build the 6 CRUD permission names for a resource.
        $crudFor = function (string $resource) use ($crudActions): array {
            return array_map(fn ($a) => "{$a}:{$resource}", $crudActions);
        };

        $rolesConfig = [
            'super_admin' => [
                'type' => 'full',
            ],
            'doctor' => [
                'resources' => [
                    'Patient', 'Encounter', 'Appointment',
                    'Vitals', 'ClinicalNote', 'Allergy', 'Diagnosis',
                    'Prescription', 'LabOrder', 'RadiologyOrder',
                    'Admission', 'Ward', 'Bed', 'BloodDonor', 'BloodUnit',
                    'ProcedureBooking',
                ],
                'extra' => ['ViewReportsDashboard', 'ExportData', 'ViewAnyClinicData'],
            ],
            'nurse' => [
                'resources' => [
                    'Patient', 'Encounter', 'Appointment',
                    'Vitals', 'ClinicalNote', 'Allergy',
                    'Prescription', 'LabOrder',
                    'Admission', 'Ward', 'Bed', 'BloodDonor',
                ],
                'extra' => ['ViewReportsDashboard'],
            ],
            'pharmacist' => [
                'resources' => [
                    'Patient', 'Encounter',
                    'Drug', 'DrugBatch', 'Prescription',
                ],
                'extra' => ['ViewReportsDashboard', 'ExportData'],
            ],
            'laboratory_scientist' => [
                'resources' => [
                    'Patient', 'Encounter', 'LabOrder',
                ],
                'extra' => ['ViewReportsDashboard'],
            ],
            'radiographer' => [
                'resources' => [
                    'Patient', 'Encounter', 'RadiologyOrder',
                ],
                'extra' => ['ViewReportsDashboard'],
            ],
            'medical_records_officer' => [
                'resources' => [
                    'Patient', 'Encounter', 'Appointment',
                    'Vitals', 'ClinicalNote', 'Diagnosis',
                ],
                'extra' => ['ViewReportsDashboard', 'ExportData'],
            ],
            'accountant' => [
                'resources' => [
                    'Patient', 'Encounter',
                    'Service', 'Invoice', 'Payment',
                    'InsuranceProvider', 'InsurancePlan', 'PatientInsurance',
                    'InsuranceClaim', 'PreAuthorization',
                ],
                'extra' => ['ViewReportsDashboard', 'ExportData'],
            ],
            'insurance_officer' => [
                'resources' => [
                    'Patient', 'Encounter',
                    'InsuranceProvider', 'InsurancePlan', 'PatientInsurance',
                    'InsuranceClaim', 'PreAuthorization',
                    'Service', 'Invoice', 'Payment',
                ],
                'extra' => ['ViewReportsDashboard', 'ExportData'],
            ],
            'medical_administrator' => [
                'type' => 'full',
            ],
            'resident_doctor' => [
                'resources' => [
                    'Patient', 'Encounter', 'Appointment',
                    'Vitals', 'ClinicalNote', 'Allergy', 'Diagnosis',
                    'Prescription', 'LabOrder', 'RadiologyOrder',
                    'Admission', 'Ward', 'Bed',
                ],
                'extra' => ['ViewReportsDashboard', 'ViewAnyClinicData'],
            ],
            'scientific_officer' => [
                'resources' => [
                    'Patient', 'Encounter', 'LabOrder',
                ],
                'extra' => ['ViewReportsDashboard', 'ExportData'],
            ],
        ];

        foreach ($rolesConfig as $roleName => $config) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            if (($config['type'] ?? '') === 'full') {
                $allPermissions = Permission::all();
                $role->syncPermissions($allPermissions);
                $this->command->info("Role [{$roleName}] -> ALL permissions ({$allPermissions->count()})");
            } else {
                $permissionNames = [];
                foreach ($config['resources'] as $resource) {
                    $permissionNames = array_merge($permissionNames, $crudFor($resource));
                }
                if (!empty($config['extra'])) {
                    $permissionNames = array_merge($permissionNames, $config['extra']);
                }
                $existingPerms = Permission::whereIn('name', $permissionNames)->get();
                $role->syncPermissions($existingPerms);
                $this->command->info("Role [{$roleName}] -> {$existingPerms->count()} permissions");
            }
        }

        $this->command->info('Roles and permissions seeded successfully.');
    }
}
