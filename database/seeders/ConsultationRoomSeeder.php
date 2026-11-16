<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\ConsultationRoom;
use Illuminate\Database\Seeder;

class ConsultationRoomSeeder extends Seeder
{
    public function run(): void
    {
        $clinics = Clinic::all();

        if ($clinics->isEmpty()) {
            $this->command->warn('No clinics found. Run FuthEmrSeeder first.');
            return;
        }

        // Per-clinic room configuration (capacity defaults to 1)
        // You can adjust these to match your real hospital layout
        $roomsPerClinic = [
            'GEN-MED-C'    => 4,   // General Medicine Clinic — 4 rooms
            'OG-CLINIC'    => 3,   // O&G Clinic — 3 rooms
            'PAED-CLINIC'  => 3,   // Paediatric Clinic — 3 rooms
            'SURG-OPD'     => 4,   // Surgical Outpatient — 4 rooms
            'AE-UNIT'      => 6,   // A&E Unit — 6 rooms (busier)
            'LAB-COL'      => 0,   // Lab Collection — no consultation rooms
            'XR-UNIT'      => 0,   // X-Ray Unit — no consultation rooms
            'PHARM-DISP'   => 0,   // Pharmacy Dispensary — no consultation rooms
        ];

        foreach ($clinics as $clinic) {
            $roomCount = $roomsPerClinic[$clinic->code] ?? 2;  // default 2 rooms

            for ($i = 1; $i <= $roomCount; $i++) {
                ConsultationRoom::firstOrCreate(
                    ['clinic_id' => $clinic->id, 'code' => "R{$i}"],
                    [
                        'hospital_id' => $clinic->hospital_id,
                        'name' => "Consultation Room {$i}",
                        'capacity' => 1,
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('Consultation rooms seeded: ' . ConsultationRoom::count() . ' rooms across ' . $clinics->count() . ' clinics.');
    }
}
