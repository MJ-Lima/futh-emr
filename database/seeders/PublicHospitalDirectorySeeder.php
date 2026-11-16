<?php

namespace Database\Seeders;

use App\Models\Hospital;
use Illuminate\Database\Seeder;

/**
 * Seeds the extended hospitals table with sample public-directory data so the
 * public landing page and hospital directory have content out of the box.
 *
 * All contact details below are SAMPLE data — edit them to match the real
 * details of each hospital before going live.
 */
class PublicHospitalDirectorySeeder extends Seeder
{
    public function run(): void
    {
        $hospitals = [
            [
                'name'      => 'Federal University Teaching Hospital, Lafia',
                'code'      => 'FUTH-LF',
                'type'      => 'Teaching',
                'tagline'   => 'Tertiary healthcare excellence for Nasarawa State.',
                'description' => 'The Federal University Teaching Hospital, Lafia is a leading tertiary healthcare institution providing specialist clinical services, medical education and research for Nasarawa State and surrounding communities.',
                'address_line1' => 'KM 8 Jos Road, Makurdi Junction',
                'city'      => 'Lafia',
                'state'     => 'Nasarawa',
                'phone_main' => '08030000001',
                'phone_alt'  => '08030000002',
                'email'     => 'care@futhlafia.gov.ng',
                'website'   => 'https://futhlafia.gov.ng',
                'latitude'  => '8.5000',
                'longitude' => '8.5167',
                'services'  => ['Outpatient', 'Inpatient', 'Emergency', 'Maternity', 'Surgery', 'Pharmacy', 'Laboratory', 'Radiology', 'Ultrasound', 'Physiotherapy', 'Antenatal', 'Eye Clinic'],
                'operating_hours' => ['Monday' => '08:00 - 20:00', 'Tuesday' => '08:00 - 20:00', 'Wednesday' => '08:00 - 20:00', 'Thursday' => '08:00 - 20:00', 'Friday' => '08:00 - 20:00', 'Saturday' => '08:00 - 18:00', 'Sunday' => 'Emergency only'],
                'is_public' => true,
                'accepts_online_registration' => true,
            ],
            [
                'name'      => 'FUTH General Hospital, Akwanga',
                'code'      => 'FUTH-AK',
                'type'      => 'General',
                'tagline'   => 'General and specialist care for Akwanga and surrounds.',
                'description' => 'A general hospital within the FUTH network, offering outpatient, inpatient and maternity services to the people of Akwanga.',
                'address_line1' => 'Akwanga Road, off Jos–Keffi Highway',
                'city'      => 'Akwanga',
                'state'     => 'Nasarawa',
                'phone_main' => '08030000011',
                'email'     => 'akwanga@futhlafia.gov.ng',
                'services'  => ['Outpatient', 'Inpatient', 'Maternity', 'Pharmacy', 'Laboratory', 'Antenatal', 'Vaccination'],
                'operating_hours' => ['Monday' => '08:00 - 18:00', 'Tuesday' => '08:00 - 18:00', 'Wednesday' => '08:00 - 18:00', 'Thursday' => '08:00 - 18:00', 'Friday' => '08:00 - 18:00', 'Saturday' => '09:00 - 14:00', 'Sunday' => 'Closed'],
                'is_public' => true,
                'accepts_online_registration' => true,
            ],
            [
                'name'      => 'FUTH Specialist Clinic, Keffi',
                'code'      => 'FUTH-KF',
                'type'      => 'Specialist',
                'tagline'   => 'Specialist outpatient consultations and diagnostics.',
                'description' => 'A specialist clinic offering cardiology, internal medicine, paediatrics and gynaecology consultations, plus a full diagnostic laboratory and imaging centre.',
                'address_line1' => '1 Dauda Keffi Road',
                'city'      => 'Keffi',
                'state'     => 'Nasarawa',
                'phone_main' => '08030000021',
                'email'     => 'keffi@futhlafia.gov.ng',
                'services'  => ['Outpatient', 'Cardiology', 'Internal Medicine', 'Paediatrics', 'Gynaecology', 'Laboratory', 'Radiology', 'Ultrasound'],
                'operating_hours' => ['Monday' => '09:00 - 17:00', 'Tuesday' => '09:00 - 17:00', 'Wednesday' => '09:00 - 17:00', 'Thursday' => '09:00 - 17:00', 'Friday' => '09:00 - 17:00', 'Saturday' => '10:00 - 14:00', 'Sunday' => 'Closed'],
                'is_public' => true,
                'accepts_online_registration' => true,
            ],
            [
                'name'      => 'FUTH Maternity Centre, Doma',
                'code'      => 'FUTH-DM',
                'type'      => 'Maternity',
                'tagline'   => 'Safe motherhood and child welfare in Doma.',
                'description' => 'A dedicated maternity centre providing antenatal care, safe delivery, postnatal services and child welfare clinics.',
                'address_line1' => 'Doma Local Government Secretariat Road',
                'city'      => 'Doma',
                'state'     => 'Nasarawa',
                'phone_main' => '08030000031',
                'email'     => 'doma@futhlafia.gov.ng',
                'services'  => ['Antenatal', 'Maternity', 'Child Welfare', 'Vaccination', 'Outpatient'],
                'operating_hours' => ['Monday' => '08:00 - 16:00', 'Tuesday' => '08:00 - 16:00', 'Wednesday' => '08:00 - 16:00', 'Thursday' => '08:00 - 16:00', 'Friday' => '08:00 - 16:00', 'Saturday' => '09:00 - 13:00', 'Sunday' => 'Closed'],
                'is_public' => true,
                'accepts_online_registration' => true,
            ],
            [
                'name'      => 'FUTH Primary Health Centre, Nasarawa-Eggon',
                'code'      => 'FUTH-NE',
                'type'      => 'Primary',
                'tagline'   => 'Community primary care for Nasarawa-Eggon.',
                'description' => 'A primary health centre delivering essential outpatient care, immunisation, and community health outreach for the Nasarawa-Eggon community.',
                'address_line1' => 'Eggon Road, Nasarawa-Eggon',
                'city'      => 'Nasarawa-Eggon',
                'state'     => 'Nasarawa',
                'phone_main' => '08030000041',
                'email'     => 'eggon@futhlafia.gov.ng',
                'services'  => ['Outpatient', 'Vaccination', 'Antenatal', 'Child Welfare', 'Minor Procedures'],
                'operating_hours' => ['Monday' => '08:00 - 16:00', 'Tuesday' => '08:00 - 16:00', 'Wednesday' => '08:00 - 16:00', 'Thursday' => '08:00 - 16:00', 'Friday' => '08:00 - 16:00', 'Saturday' => 'Closed', 'Sunday' => 'Closed'],
                'is_public' => true,
                'accepts_online_registration' => true,
            ],
            [
                'name'      => 'FUTH Telemedicine Hub, Abuja Liaison',
                'code'      => 'FUTH-AB',
                'type'      => 'Clinic',
                'tagline'   => 'Telemedicine and referral liaison with Abuja.',
                'description' => 'A telemedicine and referral coordination clinic linking patients in the FCT with FUTH Lafia specialist services.',
                'address_line1' => '2 Garki Road, Abuja Liaison Office',
                'city'      => 'Abuja',
                'state'     => 'FCT',
                'phone_main' => '08030000051',
                'email'     => 'abuja@futhlafia.gov.ng',
                'services'  => ['Telemedicine', 'Outpatient', 'Lab Referral', 'Imaging Referral'],
                'operating_hours' => ['Monday' => '09:00 - 17:00', 'Tuesday' => '09:00 - 17:00', 'Wednesday' => '09:00 - 17:00', 'Thursday' => '09:00 - 17:00', 'Friday' => '09:00 - 17:00', 'Saturday' => 'Closed', 'Sunday' => 'Closed'],
                'is_public' => true,
                'accepts_online_registration' => true,
            ],
        ];

        foreach ($hospitals as $data) {
            Hospital::updateOrCreate(
                ['code' => $data['code']],
                array_merge($data, [
                    'status' => true,
                    'country' => 'Nigeria',
                ])
            );
        }

        $this->command->info('Seeded ' . count($hospitals) . ' public-directory hospitals.');
    }
}
