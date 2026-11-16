<?php

namespace Database\Seeders;

use App\Models\{Clinic, Department, DutyPosting, DutyRoster, Hospital, InsurancePlan, InsuranceProvider, PatientInsurance};
use App\Models\{Drug, DrugBatch, LabOrder, LabOrderItem, LabPanel, LabResult, LabTest, Patient};
use App\Models\{Service, Staff, User, Vitals};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

// DO NOT import App\Models\Role — use only Spatie\Permission\Models\Role
class FuthEmrSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding FUTH Lafia EMR demo data...');

        // =========================================
        // 1. CORE LOOKUP DATA: Hospital, Departments, Clinics
        // =========================================

        Hospital::firstOrCreate(['id' => 1], [
            'name'   => 'Federal University Teaching Hospital, Lafia',
            'code'   => 'FUTH-LAFIA',
            'status' => true,
        ]);

        // --- Departments (IDs 1-8) ---
        $departments = [
            ['id' => 1, 'name' => 'General Medicine',      'code' => 'GEN-MED'],
            ['id' => 2, 'name' => 'Obstetrics & Gynaecology', 'code' => 'O&G'],
            ['id' => 3, 'name' => 'Paediatrics',             'code' => 'PAED'],
            ['id' => 4, 'name' => 'Surgery',                 'code' => 'SURG'],
            ['id' => 5, 'name' => 'Accident & Emergency',    'code' => 'A&E'],
            ['id' => 6, 'name' => 'Laboratory',              'code' => 'LAB'],
            ['id' => 7, 'name' => 'Radiology',               'code' => 'RAD'],
            ['id' => 8, 'name' => 'Pharmacy',                'code' => 'PHARM'],
        ];
        foreach ($departments as $d) {
            Department::firstOrCreate(['id' => $d['id']], [
                'hospital_id' => 1,
                'name'        => $d['name'],
                'code'        => $d['code'],
                'description' => $d['name'],
                'status'      => true,   // boolean, NOT 'Active' string
            ]);
        }

        // --- Clinics (IDs 1-8) — NOTE: clinics table has NO department_id column ---
        $clinics = [
            ['id' => 1, 'name' => 'General Medicine Clinic',  'code' => 'GEN-MED-C'],
            ['id' => 2, 'name' => 'O&G Clinic',               'code' => 'OG-CLINIC'],
            ['id' => 3, 'name' => 'Paediatric Clinic',         'code' => 'PAED-CLINIC'],
            ['id' => 4, 'name' => 'Surgical Outpatient',       'code' => 'SURG-OPD'],
            ['id' => 5, 'name' => 'A&E Unit',                  'code' => 'AE-UNIT'],
            ['id' => 6, 'name' => 'Lab Collection',            'code' => 'LAB-COL'],
            ['id' => 7, 'name' => 'X-Ray Unit',                'code' => 'XR-UNIT'],
            ['id' => 8, 'name' => 'Pharmacy Dispensary',       'code' => 'PHARM-DISP'],
        ];
        foreach ($clinics as $c) {
            Clinic::firstOrCreate(['id' => $c['id']], [
                'hospital_id' => 1,
                'name'        => $c['name'],
                'code'        => $c['code'],
                'status'      => true,   // boolean, NO description, NO department_id
            ]);
        }

        // =========================================
        // 2. USERS + ROLES
        // =========================================
        $password = Hash::make('Futh@2026');

        $usersData = [
            ['email' => 'admin@futhlafia.gov.ng',          'name' => 'System Administrator', 'roles' => ['super_admin'],                  'dept' => 1, 'clinic' => 1],
            ['email' => 'dr.okoro@futhlafia.gov.ng',       'name' => 'Dr. Okoro Emmanuel',    'roles' => ['doctor'],                       'dept' => 1, 'clinic' => 1],
            ['email' => 'dr.adebayo@futhlafia.gov.ng',     'name' => 'Dr. Adebayo Folake',    'roles' => ['doctor'],                       'dept' => 2, 'clinic' => 2],
            ['email' => 'nurse.john@futhlafia.gov.ng',     'name' => 'Nurse John Aliyu',      'roles' => ['nurse'],                        'dept' => 1, 'clinic' => 5],
            ['email' => 'pharm.musa@futhlafia.gov.ng',     'name' => 'Pharm. Musa Ibrahim',   'roles' => ['pharmacist'],                   'dept' => 8, 'clinic' => 8],
            ['email' => 'lab.aisha@futhlafia.gov.ng',      'name' => 'Aisha Bala',            'roles' => ['laboratory_scientist'],         'dept' => 6, 'clinic' => 6],
            ['email' => 'rad.tunde@futhlafia.gov.ng',      'name' => 'Tunde Ogundimu',        'roles' => ['radiographer'],                 'dept' => 7, 'clinic' => 7],
            ['email' => 'records.hauwa@futhlafia.gov.ng',  'name' => 'Hauwa Musa',            'roles' => ['medical_records_officer'],       'dept' => 1, 'clinic' => 1],
            ['email' => 'accounts.david@futhlafia.gov.ng', 'name' => 'David Okonkwo',         'roles' => ['accountant'],                   'dept' => 1, 'clinic' => 1],
            ['email' => 'insurance.officer@futhlafia.gov.ng', 'name' => 'Amina Bello',       'roles' => ['insurance_officer'],            'dept' => 1, 'clinic' => 1],
        ];

        $staffTypes = [
            'admin@futhlafia.gov.ng'          => ['System Administrator', 'Administrator'],
            'dr.okoro@futhlafia.gov.ng'       => ['Consultant Physician', 'Doctor'],
            'dr.adebayo@futhlafia.gov.ng'     => ['Consultant Obstetrician', 'Doctor'],
            'nurse.john@futhlafia.gov.ng'     => ['Senior Nursing Officer', 'Nurse'],
            'pharm.musa@futhlafia.gov.ng'     => ['Chief Pharmacist', 'Pharmacist'],
            'lab.aisha@futhlafia.gov.ng'      => ['Senior Lab Scientist', 'Laboratory Scientist'],
            'rad.tunde@futhlafia.gov.ng'      => ['Senior Radiographer', 'Radiographer'],
            'records.hauwa@futhlafia.gov.ng'  => ['Medical Records Officer', 'Medical Records'],
            'accounts.david@futhlafia.gov.ng' => ['Account Officer', 'Accountant'],
            'insurance.officer@futhlafia.gov.ng' => ['Insurance Officer', 'Other'],
        ];

        foreach ($usersData as $ud) {
            // Create or update user
            $user = User::updateOrCreate(
                ['email' => $ud['email']],
                [
                    'name'          => $ud['name'],
                    'password'      => $password,
                    'hospital_id'   => 1,
                    'department_id' => $ud['dept'],
                    'default_clinic_id' => $ud['clinic'],
                    'status'        => 'active',
                ]
            );

            // Assign Spatie roles
            foreach ($ud['roles'] as $roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $user->assignRole($role);
                }
            }

            // Create staff record
            $st = $staffTypes[$ud['email']] ?? ['Staff', 'Other'];
            Staff::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'hospital_id'   => 1,
                    'department_id' => $ud['dept'],
                    'clinic_id'     => $ud['clinic'],
                    'staff_number'  => 'FUTH/' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'first_name'    => explode(' ', $ud['name'])[0] ?? 'Staff',
                    'last_name'     => explode(' ', $ud['name'])[1] ?? '',
                    'gender'        => 'Male',
                    'designation'   => $st[0],
                    'staff_type'    => $st[1],
                    'status'        => 1,
                ]
            );
        }

        // Update ALL existing users' passwords (in case some were created elsewhere)
        User::query()->update(['password' => $password]);

        // =========================================
        // 3. INSURANCE PROVIDERS + PLANS
        // =========================================
        $providers = [
            ['name' => 'National Health Insurance Authority', 'code' => 'NHIA', 'short_name' => 'NHIA', 'type' => 'NHIA', 'nhia_code' => 'NHIA-NG', 'capitation_rate' => 1500, 'discount_percentage' => 10, 'claim_settlement_days' => 60],
            ['name' => 'Hygeia HMO', 'code' => 'HYGEIA', 'short_name' => 'Hygeia', 'type' => 'HMO', 'capitation_rate' => 2000, 'discount_percentage' => 15, 'claim_settlement_days' => 45],
            ['name' => 'AXA Mansard Health', 'code' => 'AXA-MD', 'short_name' => 'AXA Mansard', 'type' => 'HMO', 'capitation_rate' => 2500, 'discount_percentage' => 12, 'claim_settlement_days' => 30],
            ['name' => 'Reliance HMO', 'code' => 'RLNC', 'short_name' => 'Reliance', 'type' => 'HMO', 'capitation_rate' => 1800, 'discount_percentage' => 10, 'claim_settlement_days' => 45],
            ['name' => 'Greenbay Healthcare', 'code' => 'GRNB', 'short_name' => 'Greenbay', 'type' => 'Private Insurance', 'capitation_rate' => 3000, 'discount_percentage' => 20, 'claim_settlement_days' => 30],
        ];

        foreach ($providers as $p) {
            $provider = InsuranceProvider::firstOrCreate(
                ['code' => $p['code']],
                array_merge($p, [
                    'hospital_id'    => 1,
                    'contact_person' => 'Claims Manager',
                    'phone'          => '+2348000000000',
                    'email'          => strtolower($p['code']) . '@claims.example',
                    'address'        => 'Lagos, Nigeria',
                    'status'         => 'Active',
                ])
            );

            InsurancePlan::firstOrCreate(
                ['code' => $p['code'] . '-STD'],
                [
                    'insurance_provider_id' => $provider->id,
                    'name'                  => $p['short_name'] . ' Standard Plan',
                    'tier'                  => 'Standard',
                    'coverage_type'         => 'Family',
                    'annual_premium'        => 60000,
                    'annual_benefit_limit'  => 500000,
                    'per_encounter_limit'   => 50000,
                    'outpatient_deduction'  => 500,
                    'inpatient_deduction'   => 2000,
                    'copay_percentage'      => 10,
                    'covers_pharmacy'       => true,
                    'covers_lab'            => true,
                    'covers_radiology'      => true,
                    'covers_inpatient'      => true,
                    'covers_maternity'      => true,
                    'covers_dental'         => false,
                    'covers_optical'        => false,
                    'description'           => 'Standard family coverage plan',
                    'status'                => 'Active',
                ]
            );

            if ($p['type'] === 'NHIA') {
                InsurancePlan::firstOrCreate(
                    ['code' => $p['code'] . '-FORMAL'],
                    [
                        'insurance_provider_id' => $provider->id,
                        'name'                  => 'NHIA Formal Sector Plan',
                        'tier'                  => 'Formal',
                        'coverage_type'         => 'Family',
                        'annual_premium'        => 0,
                        'annual_benefit_limit'  => 300000,
                        'per_encounter_limit'   => 30000,
                        'outpatient_deduction'  => 0,
                        'inpatient_deduction'   => 0,
                        'copay_percentage'      => 0,
                        'covers_pharmacy'       => true,
                        'covers_lab'            => true,
                        'covers_radiology'      => true,
                        'covers_inpatient'      => true,
                        'covers_maternity'      => true,
                        'covers_dental'         => false,
                        'covers_optical'        => false,
                        'description'           => 'NHIA Formal Sector Programme',
                        'status'                => 'Active',
                    ]
                );
            }
        }

        // =========================================
        // 4. PATIENT INSURANCE (safe — only if patients exist)
        // =========================================
        $patients = Patient::limit(6)->get();
        $insuranceOfficer = User::where('email', 'insurance.officer@futhlafia.gov.ng')->first();

        if ($patients->isNotEmpty() && $insuranceOfficer) {
            $allPlans = InsurancePlan::all();
            $allProviders = InsuranceProvider::all();

            foreach ($patients as $i => $patient) {
                $provider = $allProviders[$i % $allProviders->count()];
                $plan = $allPlans->where('insurance_provider_id', $provider->id)->first();
                if (!$plan) continue;

                PatientInsurance::firstOrCreate(
                    ['patient_id' => $patient->id],
                    [
                        'insurance_provider_id' => $provider->id,
                        'insurance_plan_id'     => $plan->id,
                        'policy_number'         => 'POL-' . $patient->id . '-' . now()->year,
                        'member_id'             => 'MBR-' . str_pad($patient->id, 6, '0', STR_PAD_LEFT),
                        'principal_relationship' => 'Self',
                        'principal_name'        => $patient->firstname . ' ' . $patient->lastname,
                        'principal_dob'         => $patient->dob?->format('Y-m-d'),
                        'principal_phone'       => $patient->phonemain,
                        'enrollment_date'       => now()->subMonths(6),
                        'effective_date'        => now()->subMonths(6),
                        'expiry_date'           => now()->addYear(),
                        'remaining_benefit'     => $plan->annual_benefit_limit,
                        'is_primary'            => true,
                        'verification_status'   => 'Verified',
                        'verified_at'           => now(),
                        'verified_by'           => $insuranceOfficer->id,
                        'status'                => 'Active',
                    ]
                );
            }
        }

        // =========================================
        // 5. SERVICES (NHIA-aligned tariffs)
        // =========================================
        $services = [
            ['name' => 'General Consultation', 'code' => 'CONS-GEN', 'category' => 'Consultation', 'standard_price' => 3000, 'nhia_rate' => 1500, 'private_rate' => 5000],
            ['name' => 'Specialist Consultation', 'code' => 'CONS-SPC', 'category' => 'Consultation', 'standard_price' => 8000, 'nhia_rate' => 3000, 'private_rate' => 12000],
            ['name' => 'Full Blood Count', 'code' => 'LAB-FBC', 'category' => 'Laboratory', 'standard_price' => 2500, 'nhia_rate' => 1500, 'private_rate' => 4000],
            ['name' => 'Malaria Parasite Test', 'code' => 'LAB-MPS', 'category' => 'Laboratory', 'standard_price' => 1500, 'nhia_rate' => 800, 'private_rate' => 2500],
            ['name' => 'Chest X-Ray', 'code' => 'RAD-CXR', 'category' => 'Radiology', 'standard_price' => 8000, 'nhia_rate' => 5000, 'private_rate' => 12000],
            ['name' => 'Pelvic Ultrasound', 'code' => 'RAD-US-PV', 'category' => 'Radiology', 'standard_price' => 10000, 'nhia_rate' => 6000, 'private_rate' => 15000],
            ['name' => 'Ward Admission (per day)', 'code' => 'WARD-GEN', 'category' => 'Accommodation', 'standard_price' => 15000, 'nhia_rate' => 8000, 'private_rate' => 25000],
            ['name' => 'Normal Delivery', 'code' => 'MAT-NVD', 'category' => 'Procedure', 'standard_price' => 35000, 'nhia_rate' => 20000, 'private_rate' => 60000],
            ['name' => 'Caesarean Section', 'code' => 'MAT-CS', 'category' => 'Procedure', 'standard_price' => 150000, 'nhia_rate' => 80000, 'private_rate' => 250000],
            ['name' => 'IV Cannulation', 'code' => 'PROC-IV', 'category' => 'Procedure', 'standard_price' => 1000, 'nhia_rate' => 500, 'private_rate' => 2000],
        ];

        foreach ($services as $s) {
            Service::firstOrCreate(
                ['code' => $s['code']],
                array_merge($s, [
                    'hospital_id'  => 1,
                    'sub_category' => 'General',
                    'is_taxable'   => false,
                    'status'       => 'Active',
                ])
            );
        }

        // =========================================
        // 6. DUTY ROSTER + POSTINGS
        // =========================================
        $dutyRoster = DutyRoster::firstOrCreate(
            ['code' => 'DR-' . now()->year . '-0001'],
            [
                'hospital_id' => 1,
                'name'       => 'Default Weekly Roster',
                'start_date' => now()->startOfWeek(),
                'end_date'   => now()->endOfWeek(),
                'cycle'      => 'Weekly',
                'status'     => 'Active',
                'created_by' => User::where('email', 'admin@futhlafia.gov.ng')->first()?->id ?? 1,
            ]
        );

        // Role → department/clinic mapping
        $roleDeptMap = [
            'super_admin'            => ['dept' => 1, 'clinic' => 1],
            'doctor'                 => ['dept' => 1, 'clinic' => 1],
            'nurse'                  => ['dept' => 5, 'clinic' => 5],
            'pharmacist'             => ['dept' => 8, 'clinic' => 8],
            'laboratory_scientist'   => ['dept' => 6, 'clinic' => 6],
            'radiographer'           => ['dept' => 7, 'clinic' => 7],
            'medical_records_officer' => ['dept' => 1, 'clinic' => 1],
            'accountant'             => ['dept' => 1, 'clinic' => 1],
            'insurance_officer'      => ['dept' => 1, 'clinic' => 1],
        ];

        $adminUser = User::where('email', 'admin@futhlafia.gov.ng')->first();
        $users = User::all();

        foreach ($users as $u) {
            // Find the user's primary role
            $primaryRole = $u->roles->first()?->name;
            $mapping = $roleDeptMap[$primaryRole] ?? ['dept' => 1, 'clinic' => 1];

            $staffRecord = Staff::where('user_id', $u->id)->first();

            DutyPosting::firstOrCreate(
                ['user_id' => $u->id, 'start_date' => $dutyRoster->start_date],
                [
                    'duty_roster_id' => $dutyRoster->id,
                    'hospital_id'    => 1,
                    'staff_id'       => $staffRecord?->id,
                    'department_id'  => $mapping['dept'],
                    'clinic_id'      => $mapping['clinic'],
                    'start_date'     => $dutyRoster->start_date,
                    'end_date'       => $dutyRoster->end_date,
                    'shift'          => 'Morning',
                    'shift_start'    => '08:00',
                    'shift_end'      => '16:00',
                    'role_in_unit'   => ($primaryRole === 'super_admin') ? 'In-Charge' : 'Officer',
                    'status'         => 'Active',
                    'posted_by'      => $adminUser?->id ?? 1,
                ]
            );
        }

        $this->command->info('Seeding complete.');
        $this->command->info('Login with: admin@futhlafia.gov.ng / Futh@2026');
        $this->command->info('All passwords: Futh@2026');
    }
}
