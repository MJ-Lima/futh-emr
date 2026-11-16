<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Seeder;

class SpecialtyClinicsSeeder extends Seeder
{
    public function run(): void
    {
        $clinics = [
            ['name' => 'Antenatal Care (ANC) Clinic', 'code' => 'ANC', 'clinic_type' => 'ANC'],
            ['name' => 'Surgical Outpatient Department (SOPD)', 'code' => 'SOPD', 'clinic_type' => 'SOPD'],
            ['name' => 'Dental Clinic', 'code' => 'DENTAL', 'clinic_type' => 'Dental'],
            ['name' => 'ENT (Ear, Nose, Throat) Clinic', 'code' => 'ENT', 'clinic_type' => 'ENT'],
            ['name' => 'Paediatric Clinic', 'code' => 'PAED', 'clinic_type' => 'Paediatric'],
            ['name' => 'Eye (Ophthalmology) Clinic', 'code' => 'EYE', 'clinic_type' => 'Eye'],
            ['name' => 'Psychiatry Clinic', 'code' => 'PSYCH', 'clinic_type' => 'Psychiatry'],
            ['name' => 'Psychology Clinic', 'code' => 'PSYCH-EDU', 'clinic_type' => 'Psychology'],
        ];

        foreach ($clinics as $c) {
            Clinic::firstOrCreate(
                ['code' => $c['code']],
                [
                    'hospital_id' => 1,
                    'name' => $c['name'],
                    'clinic_type' => $c['clinic_type'],
                    'status' => true,
                ]
            );
        }

        $this->command->info('Specialty clinics seeded: ' . count($clinics) . ' clinics');
    }
}
