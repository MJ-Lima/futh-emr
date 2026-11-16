<?php

namespace Database\Seeders;

use App\Models\LabUnit;
use App\Models\Laboratory;
use Illuminate\Database\Seeder;

class LaboratoryAndLabUnitSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------------
        // 1. Create the 4 laboratories
        // ---------------------------------------------------------------
        $labs = [
            [
                'hospital_id' => 1,
                'name'        => 'DNA Laboratory',
                'code'        => 'DNA',
                'lab_type'    => 'dna',
                'address'     => 'FUTH Lafia — DNA Lab Building',
                'phone'       => '',
                'is_active'   => true,
            ],
            [
                'hospital_id' => 1,
                'name'        => 'Main Laboratory',
                'code'        => 'MAIN',
                'lab_type'    => 'main',
                'address'     => 'FUTH Lafia — Main Lab Complex',
                'phone'       => '',
                'is_active'   => true,
            ],
            [
                'hospital_id' => 1,
                'name'        => 'FUTH Annex Laboratory',
                'code'        => 'ANNEX_LAB',
                'lab_type'    => 'subsidiary',
                'address'     => 'FUTH Annex — Lab Department',
                'phone'       => '',
                'is_active'   => true,
            ],
            [
                'hospital_id' => 1,
                'name'        => 'FUTH Taimako Laboratory',
                'code'        => 'TAIMAKO_LAB',
                'lab_type'    => 'subsidiary',
                'address'     => 'FUTH Taimako Subsidiary — Lab Department',
                'phone'       => '',
                'is_active'   => true,
            ],
        ];

        foreach ($labs as $lab) {
            Laboratory::firstOrCreate(['code' => $lab['code']], $lab);
        }

        $this->command->info('Laboratories seeded: ' . Laboratory::count() . ' labs.');

        // ---------------------------------------------------------------
        // 2. Create lab units — Main Lab has 7, others have 1 (General)
        // ---------------------------------------------------------------
        $mainLab = Laboratory::where('code', 'MAIN')->first();
        $dnaLab  = Laboratory::where('code', 'DNA')->first();
        $annex   = Laboratory::where('code', 'ANNEX_LAB')->first();
        $taimako = Laboratory::where('code', 'TAIMAKO_LAB')->first();

        $mainUnits = [
            ['name' => 'Haematology',         'code' => 'HAEM',  'description' => 'Blood cell counts, coagulation, haematocrit'],
            ['name' => 'Chemical Pathology',  'code' => 'CHEM',  'description' => 'Blood chemistry, electrolytes, liver & renal function'],
            ['name' => 'Microbiology',       'code' => 'MICRO', 'description' => 'Bacterial, fungal, and mycobacterial cultures & sensitivity'],
            ['name' => 'Parasitology',        'code' => 'PARA',  'description' => 'Malaria parasites, stool microscopy, ova & cysts'],
            ['name' => 'Serology',            'code' => 'SERO',  'description' => 'HIV, Hepatitis, VDRL, Typhoid serology'],
            ['name' => 'Histopathology',      'code' => 'HISTO', 'description' => 'Tissue biopsy processing and reporting'],
            ['name' => 'Blood Bank',          'code' => 'BB',    'description' => 'Blood grouping, cross-matching, blood unit storage'],
        ];

        foreach ($mainUnits as $u) {
            LabUnit::firstOrCreate(
                ['laboratory_id' => $mainLab->id, 'code' => $u['code']],
                [
                    'name' => $u['name'],
                    'description' => $u['description'],
                    'is_active' => true,
                ]
            );
        }

        // DNA Lab — single molecular unit
        if ($dnaLab) {
            LabUnit::firstOrCreate(
                ['laboratory_id' => $dnaLab->id, 'code' => 'DNA'],
                ['name' => 'DNA / Molecular', 'description' => 'PCR, DNA testing, molecular diagnostics', 'is_active' => true]
            );
        }
        // Annex Lab — single general unit
        if ($annex) {
            LabUnit::firstOrCreate(
                ['laboratory_id' => $annex->id, 'code' => 'GEN'],
                ['name' => 'General Lab', 'description' => 'General lab tests at FUTH Annex', 'is_active' => true]
            );
        }
        // Taimako Lab — single general unit
        if ($taimako) {
            LabUnit::firstOrCreate(
                ['laboratory_id' => $taimako->id, 'code' => 'GEN'],
                ['name' => 'General Lab', 'description' => 'General lab tests at FUTH Taimako', 'is_active' => true]
            );
        }

        $this->command->info('Lab units seeded: ' . LabUnit::count() . ' units.');
    }
}
