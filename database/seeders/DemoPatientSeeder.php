<?php

namespace Database\Seeders;

use App\Models\Allergy;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicalNote;
use App\Models\Diagnosis;
use App\Models\Encounter;
use App\Models\Hospital;
use App\Models\LabOrder;
use App\Models\LabOrderItem;
use App\Models\LabResult;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\RadiologyModality;
use App\Models\RadiologyOrder;
use App\Models\RadiologyResult;
use App\Models\Drug;
use App\Models\User;
use App\Models\Vitals;
use Illuminate\Database\Seeder;

class DemoPatientSeeder extends Seeder
{
    public function run(): void
    {
        $hospital = Hospital::where('code', 'FUTH-LF')->first();
        $clinic = Clinic::where('hospital_id', $hospital->id)->where('code', 'GOP')->first();
        $doctor = User::where('email', 'dr.okoro@futhlafia.gov.ng')->first();

        $patients = [
            ['Aisha', 'Mohammed', 'F', '1990-03-12', 'Lafia', 'Nasarawa', '08030000001'],
            ['Chidi', 'Eze', 'M', '1985-06-22', 'Akwanga', 'Nasarawa', '08030000002'],
            ['Fatima', 'Bello', 'F', '1978-11-04', 'Keffi', 'Nasarawa', '08030000003'],
            ['John', 'Okafor', 'M', '1995-09-18', 'Lafia', 'Nasarawa', '08030000004'],
            ['Grace', 'Adeyemi', 'F', '2001-01-30', 'Doma', 'Nasarawa', '08030000005'],
            ['Ibrahim', 'Sani', 'M', '1968-08-14', 'Lafia', 'Nasarawa', '08030000006'],
        ];

        foreach ($patients as [$first, $last, $gender, $dob, $city, $state, $phone]) {
            $patient = Patient::firstOrCreate(
                ['phonemain' => $phone],
                [
                    'firstname' => $first,
                    'middlename' => '',
                    'lastname' => $last,
                    'dob' => $dob,
                    'gender' => strtolower($gender),
                    'marital' => 'married',
                    'religion' => 'Christianity',
                    'occupation' => 'Trader',
                    'phonemain' => $phone,
                    'address' => "{$city}, {$state} State",
                    'city' => $city,
                    'state' => $state,
                    'country' => 'Nigeria',
                    'nokname' => 'Family Member',
                    'relationship' => 'Spouse',
                    'nokphone' => '08030000099',
                    'nokaddress' => "{$city}, {$state} State",
                    'status' => 1,
                ]
            );

            // Create one encounter
            $encounter = Encounter::create([
                'hospital_id' => $hospital->id,
                'patient_id' => $patient->id,
                'clinic_id' => $clinic->id,
                'encounter_date' => today(),
                'encounter_time' => now()->subHours(rand(1, 5))->format('H:i:s'),
                'visit_type' => 'Outpatient',
                'status' => 'In Progress',
                'chief_complaint' => 'Fever and headache for 3 days',
                'created_by' => $doctor->id,
            ]);

            // Vitals
            Vitals::create([
                'hospital_id' => $hospital->id,
                'patient_id' => $patient->id,
                'encounter_id' => $encounter->id,
                'recorded_by' => $doctor->id,
                'recorded_at' => now(),
                'temperature_c' => 38.4,
                'systolic_bp' => 120,
                'diastolic_bp' => 80,
                'heart_rate' => 88,
                'respiratory_rate' => 18,
                'spo2' => 98,
                'weight_kg' => 65,
                'height_cm' => 165,
            ]);

            // Clinical note (SOAP)
            ClinicalNote::create([
                'hospital_id' => $hospital->id,
                'patient_id' => $patient->id,
                'encounter_id' => $encounter->id,
                'author_id' => $doctor->id,
                'note_type' => 'Progress',
                'title' => 'Day 1 progress note',
                'subjective' => 'Patient complains of fever (3 days), headache, body pains. No vomiting, no diarrhoea.',
                'objective' => 'T 38.4°C, BP 120/80, HR 88, RR 18, SpO2 98%. General examination: acutely ill-looking, no pallor, no jaundice.',
                'assessment' => 'Suspected malaria. Differential: typhoid fever, URTI.',
                'plan' => 'Send MP+RDT, FBC. Start artemether/lumefantrine. Paracetamol for fever. Review in 48 hours.',
                'is_signed' => true,
                'signed_at' => now(),
                'signed_by' => $doctor->id,
            ]);

            // Diagnosis (ICD-10)
            Diagnosis::create([
                'patient_id' => $patient->id,
                'encounter_id' => $encounter->id,
                'recorded_by' => $doctor->id,
                'icd10_code' => 'B54',
                'description' => 'Unspecified malaria',
                'type' => 'Working',
                'status' => 'Active',
                'onset_date' => today()->subDays(3),
            ]);

            // Allergy (1 in 3 patients)
            if (rand(1, 3) === 1) {
                Allergy::create([
                    'patient_id' => $patient->id,
                    'recorded_by' => $doctor->id,
                    'encounter_id' => $encounter->id,
                    'substance' => 'Sulfonamides',
                    'category' => 'Medication',
                    'criticality' => 'High',
                    'severity' => 'Moderate',
                    'reaction' => 'Rash',
                    'status' => 'Active',
                ]);
            }

            // Lab order
            $mpTest = LabTest::where('code', 'MPBF')->first();
            $fbcPanel = \App\Models\LabPanel::where('code', 'FBC')->first();
            $hbTest = LabTest::where('code', 'HB')->first();
            if ($mpTest || $hbTest) {
                $labOrder = LabOrder::create([
                    'hospital_id' => $hospital->id,
                    'patient_id' => $patient->id,
                    'encounter_id' => $encounter->id,
                    'requested_by' => $doctor->id,
                    'priority' => 'Urgent',
                    'status' => 'Pending',
                    'clinical_notes' => 'Investigate malaria',
                    'specimen_source' => 'Whole blood',
                ]);
                if ($mpTest) {
                    LabOrderItem::create([
                        'lab_order_id' => $labOrder->id,
                        'lab_test_id' => $mpTest->id,
                        'test_name' => $mpTest->name,
                        'loinc_code' => $mpTest->loinc_code,
                        'price' => $mpTest->price,
                        'status' => 'Pending',
                    ]);
                }
                if ($hbTest) {
                    LabOrderItem::create([
                        'lab_order_id' => $labOrder->id,
                        'lab_test_id' => $hbTest->id,
                        'test_name' => $hbTest->name,
                        'loinc_code' => $hbTest->loinc_code,
                        'price' => $hbTest->price,
                        'status' => 'Pending',
                    ]);
                }
            }

            // Radiology order (chest X-ray for some)
            if (rand(1, 2) === 1) {
                $xray = RadiologyModality::where('code', 'XRAY')->first();
                if ($xray) {
                    RadiologyOrder::create([
                        'hospital_id' => $hospital->id,
                        'patient_id' => $patient->id,
                        'encounter_id' => $encounter->id,
                        'modality_id' => $xray->id,
                        'requested_by' => $doctor->id,
                        'examination' => 'Chest X-Ray PA view',
                        'body_part' => 'Chest',
                        'priority' => 'Routine',
                        'status' => 'Pending',
                        'clinical_indication' => 'Suspected pneumonia / TB screening',
                    ]);
                }
            }

            // Prescription
            $al = Drug::where('code', 'DRG-009')->first();
            $para = Drug::where('code', 'DRG-001')->first();
            if ($al && $para) {
                $rx = Prescription::create([
                    'hospital_id' => $hospital->id,
                    'patient_id' => $patient->id,
                    'encounter_id' => $encounter->id,
                    'prescriber_id' => $doctor->id,
                    'priority' => 'Routine',
                    'status' => 'Pending',
                ]);
                PrescriptionItem::create([
                    'prescription_id' => $rx->id,
                    'drug_id' => $al->id,
                    'drug_name' => $al->name,
                    'dosage_form' => $al->dosage_form,
                    'strength' => $al->strength,
                    'dose' => '4 tablets',
                    'route' => 'PO',
                    'frequency' => 'BD',
                    'duration' => '3 days',
                    'quantity' => 24,
                    'quantity_dispensed' => 0,
                    'instructions' => 'Take 4 tablets stat, then 4 tablets at 8, 24, 48 hours',
                    'status' => 'Pending',
                ]);
                PrescriptionItem::create([
                    'prescription_id' => $rx->id,
                    'drug_id' => $para->id,
                    'drug_name' => $para->name,
                    'dosage_form' => $para->dosage_form,
                    'strength' => $para->strength,
                    'dose' => '1 tablet',
                    'route' => 'PO',
                    'frequency' => 'TDS',
                    'duration' => '3 days',
                    'quantity' => 9,
                    'quantity_dispensed' => 0,
                    'instructions' => 'For fever as needed',
                    'status' => 'Pending',
                ]);
            }
        }

        // Add a few appointments for today
        $patients = Patient::all();
        foreach ($patients->take(4) as $i => $patient) {
            Appointment::create([
                'hospital_id' => $hospital->id,
                'patient_id' => $patient->id,
                'clinic_id' => $clinic->id,
                'provider_id' => $doctor->id,
                'appointment_date' => today(),
                'appointment_time' => sprintf('%02d:00:00', 9 + $i),
                'estimated_duration' => 30,
                'visit_type' => 'New',
                'priority' => 'Routine',
                'reason_for_visit' => 'Follow-up consultation',
                'status' => 'Scheduled',
                'created_by' => $doctor->id,
            ]);
        }
    }
}
