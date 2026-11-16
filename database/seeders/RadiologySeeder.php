<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\RadiologyModality;
use Illuminate\Database\Seeder;

class RadiologySeeder extends Seeder
{
    public function run(): void
    {
        $hospital = Hospital::where('code', 'FUTH-LF')->first();

        $modalities = [
            ['Plain X-Ray',            'XRAY', 'Conventional radiography',          1500, 4],
            ['Ultrasound',             'US',   'Real-time ultrasonography',          3000, 4],
            ['Computed Tomography',    'CT',   'Cross-sectional CT imaging',         18000, 24],
            ['Magnetic Resonance',     'MR',   'MRI imaging',                        25000, 48],
            ['Fluoroscopy',             'FL',   'Fluoroscopic examinations',          4000, 8],
            ['Mammography',             'MG',   'Breast imaging',                     6000, 24],
            ['Doppler Ultrasound',      'DUS',  'Vascular Doppler studies',           5000, 8],
            ['Interventional Radiology','IVR',  'Image-guided procedures',            10000, 12],
        ];

        foreach ($modalities as [$name, $code, $desc, $price, $turnaround]) {
            RadiologyModality::firstOrCreate(
                ['code' => $code],
                [
                    'hospital_id' => $hospital->id,
                    'name' => $name,
                    'description' => $desc,
                    'price' => $price,
                    'turnaround_hours' => $turnaround,
                    'status' => true,
                ]
            );
        }
    }
}
