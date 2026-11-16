<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Hospital;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $hospital = Hospital::where('code', 'FUTH-LF')->first();

        $accounts = [
            // [email, name, title, staff_type, designation, dept_code]
            ['admin@futhlafia.gov.ng',  'System Administrator', 'Mr.', 'Administrator',  'ICT Lead',       'GM'],
            ['dr.okoro@futhlafia.gov.ng', 'Okoro Chukwuemeka', 'Dr.',  'Doctor',          'Consultant Physician', 'GM'],
            ['dr.adebayo@futhlafia.gov.ng', 'Adebayo Funmilayo', 'Dr.', 'Doctor',         'Senior Registrar Obstetrics', 'OBGYN'],
            ['nurse.john@futhlafia.gov.ng', 'John Bala',         'Mr.',  'Nurse',           'Principal Nursing Officer', 'GM'],
            ['pharm.musa@futhlafia.gov.ng', 'Musa Ibrahim',      'Pharm.','Pharmacist',    'Chief Pharmacist', 'PHARM'],
            ['lab.aisha@futhlafia.gov.ng',  'Aisha Yusuf',       'Mrs.', 'Laboratory Scientist', 'Senior Lab Scientist', 'LAB'],
            ['rad.tunde@futhlafia.gov.ng',  'Tunde Okafor',      'Mr.',  'Radiographer',   'Senior Radiographer', 'RAD'],
            ['records.hauwa@futhlafia.gov.ng', 'Hauwa Sani',     'Mrs.', 'Medical Records','Records Officer', 'GM'],
            ['accounts.david@futhlafia.gov.ng', 'David Eze',     'Mr.',  'Accountant',     'Finance Officer', 'GM'],
        ];

        foreach ($accounts as [$email, $name, $title, $staffType, $designation, $deptCode]) {
            [$firstName, $lastName] = explode(' ', $name, 2);

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'hospital_id' => $hospital->id,
                    'status' => 'active',
                ]
            );

            // Map staff_type (Title Case, matches enum) to snake_case role name
            $roleMap = [
                'Administrator'         => 'medical_administrator',
                'Doctor'                => 'doctor',
                'Nurse'                 => 'nurse',
                'Pharmacist'            => 'pharmacist',
                'Laboratory Scientist'  => 'laboratory_scientist',
                'Radiographer'          => 'radiographer',
                'Medical Records'       => 'medical_records_officer',
                'Accountant'            => 'accountant',
            ];

            if (! $user->hasRole($roleMap[$staffType] ?? 'doctor')) {
                $user->assignRole($roleMap[$staffType] ?? 'doctor');
            }

            $department = Department::where('hospital_id', $hospital->id)
                ->where('code', $deptCode)->first();

            Staff::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'hospital_id' => $hospital->id,
                    'department_id' => $department?->id,
                    'staff_number' => 'FUTH/' . strtoupper(uniqid()),
                    'title' => $title,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'gender' => in_array($title, ['Mrs.', 'Miss']) ? 'Female' : 'Male',
                    'phone' => '+23480000000' . rand(10, 99),
                    'designation' => $designation,
                    'staff_type' => $staffType,
                    'status' => true,
                ]
            );
        }
    }
}
