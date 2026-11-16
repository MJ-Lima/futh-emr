<?php

namespace Database\Seeders;

use App\Models\DispensingPoint;
use App\Models\Hospital;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $hospital = Hospital::where('code', 'FUTH-LF')->first();

        $suppliers = [
            ['Emzor Pharmaceuticals', 'EMZ', 'Mr. Olu Adeyemi', '+2348033000001', 'sales@emzor.com'],
            ['Fidson Healthcare', 'FID', 'Mrs. Joy Okeke', '+2348033000002', 'sales@fidson.com'],
            ['May & Baker Nigeria', 'MNB', 'Mr. Sam Oke', '+2348033000003', 'orders@mayandbaker.com'],
            ['Evans Medical', 'EVM', 'Dr. Grace Bello', '+2348033000004', 'info@evansmedical.com'],
            ['Swipha Nigeria', 'SWP', 'Mr. Tunde Salami', '+2348033000005', 'sales@swipha.com'],
            ['Unique Pharmaceuticals', 'UNQ', 'Mrs. Aisha Bello', '+2348033000006', 'orders@uniquepharma.com'],
        ];

        foreach ($suppliers as [$name, $code, $contact, $phone, $email]) {
            Supplier::firstOrCreate(
                ['code' => $code],
                [
                    'hospital_id' => $hospital->id,
                    'name' => $name,
                    'contact_person' => $contact,
                    'phone' => $phone,
                    'email' => $email,
                    'address' => 'Lagos, Nigeria',
                    'status' => true,
                ]
            );
        }
    }
}
