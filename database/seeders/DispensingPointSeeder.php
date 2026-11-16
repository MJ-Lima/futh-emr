<?php

namespace Database\Seeders;

use App\Models\DispensingPoint;
use App\Models\Hospital;
use Illuminate\Database\Seeder;

class DispensingPointSeeder extends Seeder
{
    public function run(): void
    {
        $hospital = Hospital::where('code', 'FUTH-LF')->first();

        $points = [
            ['Main Pharmacy',     'PHARM-MAIN', 'Ground Floor, Block A'],
            ['Emergency Pharmacy','PHARM-ER',   'Emergency Department'],
            ['ANC Pharmacy',      'PHARM-ANC',  'Antenatal Clinic'],
            ['Paediatric Pharmacy','PHARM-PAED','Children Ward'],
            ['Inpatient Pharmacy','PHARM-IPD',  'Wards 1-5'],
            ['Theatre Pharmacy',  'PHARM-OR',   'Operating Theatre'],
        ];

        foreach ($points as [$name, $code, $location]) {
            DispensingPoint::firstOrCreate(
                ['code' => $code],
                [
                    'hospital_id' => $hospital->id,
                    'name' => $name,
                    'location' => $location,
                    'status' => true,
                ]
            );
        }
    }
}
