<?php

namespace Database\Seeders;

use App\Models\{Clinic, Department, Hospital, State, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FuthBaseDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding base data (hospital, departments, clinics, states, users)...');

        // Hospital
        Hospital::firstOrCreate(
            ['code' => 'FUTH-LF'],
            ['name' => 'Federal University Teaching Hospital, Lafia', 'status' => 1]
        );

        // Departments
        $depts = [
            ['name' => 'General Medicine', 'code' => 'GM', 'description' => 'Internal medicine & general practice'],
            ['name' => 'Surgery', 'code' => 'SRG', 'description' => 'General & specialist surgery'],
            ['name' => 'Paediatrics', 'code' => 'PAED', 'description' => 'Child health'],
            ['name' => 'Obstetrics & Gynaecology', 'code' => 'OBGYN', 'description' => 'Maternal & women health'],
            ['name' => 'Accident & Emergency', 'code' => 'A&E', 'description' => 'Emergency medicine'],
            ['name' => 'Laboratory', 'code' => 'LAB', 'description' => 'Clinical laboratory services'],
            ['name' => 'Radiology', 'code' => 'RAD', 'description' => 'Imaging services'],
            ['name' => 'Pharmacy', 'code' => 'PHARM', 'description' => 'Pharmaceutical services'],
            ['name' => 'Psychiatry', 'code' => 'PSY', 'description' => 'Mental health'],
            ['name' => 'Ophthalmology', 'code' => 'OPH', 'description' => 'Eye care'],
            ['name' => 'ENT', 'code' => 'ENT', 'description' => 'Ear, nose, throat'],
            ['name' => 'Dental', 'code' => 'DENT', 'description' => 'Dental services'],
            ['name' => 'Physiotherapy', 'code' => 'PHY', 'description' => 'Rehabilitation'],
        ];
        foreach ($depts as $d) {
            Department::firstOrCreate(
                ['code' => $d['code']],
                array_merge($d, ['hospital_id' => 1, 'status' => 1])
            );
        }

        // Clinics
        $clinics = [
            ['name' => 'General Medicine Clinic', 'code' => 'GMC'],
            ['name' => 'Surgical Clinic', 'code' => 'SRC'],
            ['name' => 'Paediatrics Clinic', 'code' => 'PAEDC'],
            ['name' => 'Antenatal Clinic', 'code' => 'ANC'],
            ['name' => 'Postnatal Clinic', 'code' => 'PNC'],
            ['name' => 'Family Planning Clinic', 'code' => 'FPC'],
            ['name' => 'Eye Clinic', 'code' => 'EYC'],
            ['name' => 'ENT Clinic', 'code' => 'ENTC'],
            ['name' => 'Dental Clinic', 'code' => 'DENC'],
            ['name' => 'Psychiatry Clinic', 'code' => 'PSYC'],
            ['name' => 'Physiotherapy Clinic', 'code' => 'PHYC'],
        ];
        foreach ($clinics as $c) {
            Clinic::firstOrCreate(
                ['code' => $c['code']],
                array_merge($c, ['hospital_id' => 1, 'status' => 1])
            );
        }

        // Nigerian States (subset)
        $states = [
            ['name' => 'Nasarawa', 'code' => 'NAS'],
            ['name' => 'Plateau', 'code' => 'PLT'],
            ['name' => 'Benue', 'code' => 'BEN'],
            ['name' => 'Kogi', 'code' => 'KOG'],
            ['name' => 'FCT Abuja', 'code' => 'FCT'],
            ['name' => 'Kaduna', 'code' => 'KAD'],
            ['name' => 'Lagos', 'code' => 'LAG'],
            ['name' => 'Rivers', 'code' => 'RIV'],
            ['name' => 'Kano', 'code' => 'KAN'],
            ['name' => 'Oyo', 'code' => 'OYO'],
        ];
        foreach ($states as $s) {
            State::firstOrCreate(['code' => $s['code']], $s);
        }

        // Users
        $password = Hash::make('Futh@2026');
        $users = [
            ['name' => 'System Administrator', 'email' => 'admin@futhlafia.gov.ng', 'hospital_id' => 1, 'department_id' => 1, 'status' => 'active'],
            ['name' => 'Okoro Chukwuemeka', 'email' => 'dr.okoro@futhlafia.gov.ng', 'hospital_id' => 1, 'department_id' => 1, 'status' => 'active'],
            ['name' => 'Adebayo Funmilayo', 'email' => 'dr.adebayo@futhlafia.gov.ng', 'hospital_id' => 1, 'department_id' => 4, 'status' => 'active'],
            ['name' => 'John Bala', 'email' => 'nurse.john@futhlafia.gov.ng', 'hospital_id' => 1, 'department_id' => 1, 'status' => 'active'],
            ['name' => 'Musa Ibrahim', 'email' => 'pharm.musa@futhlafia.gov.ng', 'hospital_id' => 1, 'department_id' => 8, 'status' => 'active'],
            ['name' => 'Aisha Yusuf', 'email' => 'lab.aisha@futhlafia.gov.ng', 'hospital_id' => 1, 'department_id' => 6, 'status' => 'active'],
            ['name' => 'Tunde Okafor', 'email' => 'rad.tunde@futhlafia.gov.ng', 'hospital_id' => 1, 'department_id' => 7, 'status' => 'active'],
            ['name' => 'Hauwa Sani', 'email' => 'records.hauwa@futhlafia.gov.ng', 'hospital_id' => 1, 'department_id' => 1, 'status' => 'active'],
            ['name' => 'David Eze', 'email' => 'accounts.david@futhlafia.gov.ng', 'hospital_id' => 1, 'department_id' => 1, 'status' => 'active'],
            ['name' => 'Amina Bello', 'email' => 'insurance.officer@futhlafia.gov.ng', 'hospital_id' => 1, 'department_id' => 1, 'status' => 'active'],
        ];
        foreach ($users as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                array_merge($u, ['password' => $password])
            );
        }

        $this->command->info('Base data seeded:');
        $this->command->info('- ' . Hospital::count() . ' hospital(s)');
        $this->command->info('- ' . Department::count() . ' departments');
        $this->command->info('- ' . Clinic::count() . ' clinics');
        $this->command->info('- ' . State::count() . ' states');
        $this->command->info('- ' . User::count() . ' users');
    }
}
