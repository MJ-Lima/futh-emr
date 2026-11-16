<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Department;
use App\Models\Hospital;
use Illuminate\Database\Seeder;

class HospitalStructureSeeder extends Seeder
{
    public function run(): void
    {
        // Federal University Teaching Hospital, Lafia
        $hospital = Hospital::firstOrCreate(
            ['code' => 'FUTH-LF'],
            ['name' => 'Federal University Teaching Hospital, Lafia', 'status' => true]
        );

        $departments = [
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

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['hospital_id' => $hospital->id, 'code' => $dept['code']],
                [
                    'name' => $dept['name'],
                    'description' => $dept['description'] ?? null,
                    'status' => true,
                ]
            );
        }

        $clinics = [
            ['name' => 'General Outpatient Clinic', 'code' => 'GOP'],
            ['name' => 'Antenatal Clinic', 'code' => 'ANC'],
            ['name' => 'Immunisation Clinic', 'code' => 'IMM'],
            ['name' => 'HIV/ART Clinic', 'code' => 'ART'],
            ['name' => 'TB Clinic', 'code' => 'TB'],
            ['name' => 'Hypertension Clinic', 'code' => 'HTN'],
            ['name' => 'Diabetes Clinic', 'code' => 'DM'],
            ['name' => 'Surgical Clinic', 'code' => 'SURG'],
            ['name' => 'Paediatric Clinic', 'code' => 'PAED'],
            ['name' => 'Mental Health Clinic', 'code' => 'PSY'],
            ['name' => 'Eye Clinic', 'code' => 'EYE'],
        ];

        foreach ($clinics as $clinic) {
            Clinic::firstOrCreate(
                ['hospital_id' => $hospital->id, 'code' => $clinic['code']],
                ['name' => $clinic['name'], 'status' => true]
            );
        }
    }
}
