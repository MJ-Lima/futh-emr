<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\LabPanel;
use App\Models\LabTest;
use Illuminate\Database\Seeder;

/**
 * Lab catalog using LOINC (Logical Observation Identifiers Names and Codes) —
 * the international standard for identifying laboratory observations.
 */
class LabCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $hospital = Hospital::where('code', 'FUTH-LF')->first();

        $panels = [
            ['Full Blood Count', 'FBC', '58410-2', 'Whole blood', 'EDTA', 'Complete blood count panel', 1500, 4],
            ['Liver Function Test', 'LFT', '24221-7', 'Serum', 'Plain', 'Hepatic function panel', 2500, 6],
            ['Renal Function Test', 'RFT', '24360-2', 'Serum', 'Plain', 'Renal function panel', 2000, 4],
            ['Lipid Profile', 'LIPID', '24331-3', 'Serum', 'Plain', 'Lipid panel', 2500, 6],
            ['Blood Glucose Panel', 'GLUC', '2345-7', 'Plasma', 'Fluoride', 'Glucose & HbA1c', 1200, 4],
            ['Urinalysis', 'UA', '24356-0', 'Urine', 'Sterile', 'Routine urine examination', 500, 2],
            ['Malaria Screen', 'MAL', '10501-5', 'Whole blood', 'EDTA', 'Malaria microscopy & RDT', 800, 2],
            ['HIV Screening', 'HIV', '89567-1', 'Serum', 'Plain', 'HIV rapid test', 1000, 2],
            ['Typhoid Screen', 'TYPH', '22028-7', 'Serum', 'Plain', 'Widal test', 1000, 2],
            ['Pregnancy Test', 'PT', '2112-1', 'Urine', 'Sterile', 'Urine β-hCG', 500, 1],
            ['Sickling Test', 'SICK', '91580-3', 'Whole blood', 'EDTA', 'Sickling + Hb electrophoresis', 1200, 6],
            ['Cross Match', 'XM', '1156-9', 'Whole blood', 'EDTA', 'Blood group & cross-match', 1500, 3],
        ];

        foreach ($panels as [$name, $code, $loinc, $specimen, $container, $desc, $price, $turnaround]) {
            LabPanel::firstOrCreate(
                ['code' => $code],
                [
                    'hospital_id' => $hospital->id,
                    'name' => $name,
                    'loinc_code' => $loinc,
                    'specimen_type' => $specimen,
                    'container' => $container,
                    'description' => $desc,
                    'price' => $price,
                    'turnaround_hours' => $turnaround,
                    'status' => true,
                ]
            );
        }

        // Individual LOINC-coded lab tests
        $tests = [
            // FBC components
            ['Haemoglobin', 'HB', '718-7', 'g/dL', '11.5', '16.5', 'Whole blood', 'EDTA', 200, 4, 'FBC'],
            ['White Blood Cell Count', 'WBC', '6690-2', 'x10^9/L', '4', '11', 'Whole blood', 'EDTA', 200, 4, 'FBC'],
            ['Platelet Count', 'PLT', '777-3', 'x10^9/L', '150', '450', 'Whole blood', 'EDTA', 200, 4, 'FBC'],
            ['Red Blood Cell Count', 'RBC', '789-8', 'x10^12/L', '4.0', '5.5', 'Whole blood', 'EDTA', 200, 4, 'FBC'],
            ['PCV / Haematocrit', 'PCV', '4544-3', '%', '36', '50', 'Whole blood', 'EDTA', 200, 4, 'FBC'],
            // LFT
            ['ALT', 'ALT', '1742-6', 'U/L', '7', '40', 'Serum', 'Plain', 300, 6, 'LFT'],
            ['AST', 'AST', '1920-8', 'U/L', '8', '40', 'Serum', 'Plain', 300, 6, 'LFT'],
            ['Alkaline Phosphatase', 'ALP', '6768-6', 'U/L', '40', '130', 'Serum', 'Plain', 300, 6, 'LFT'],
            ['Total Bilirubin', 'TBIL', '1975-2', 'μmol/L', '2', '21', 'Serum', 'Plain', 350, 6, 'LFT'],
            ['Total Protein', 'TP', '2885-2', 'g/L', '60', '80', 'Serum', 'Plain', 300, 6, 'LFT'],
            ['Albumin', 'ALB', '1751-7', 'g/L', '35', '50', 'Serum', 'Plain', 300, 6, 'LFT'],
            // RFT
            ['Urea', 'UREA', '3094-0', 'mmol/L', '2.5', '7.0', 'Serum', 'Plain', 300, 4, 'RFT'],
            ['Creatinine', 'CREAT', '2160-0', 'μmol/L', '60', '120', 'Serum', 'Plain', 300, 4, 'RFT'],
            ['Sodium', 'NA', '2951-2', 'mmol/L', '135', '145', 'Serum', 'Plain', 300, 4, 'RFT'],
            ['Potassium', 'K', '2823-3', 'mmol/L', '3.5', '5.0', 'Serum', 'Plain', 300, 4, 'RFT'],
            ['Bicarbonate', 'HCO3', '1963-8', 'mmol/L', '22', '29', 'Serum', 'Plain', 300, 4, 'RFT'],
            // Glucose
            ['Fasting Blood Glucose', 'FBG', '1558-6', 'mmol/L', '3.9', '6.1', 'Plasma', 'Fluoride', 400, 4, 'GLUC'],
            ['HbA1c', 'HBA1C', '4548-4', '%', '4.0', '5.6', 'Whole blood', 'EDTA', 800, 24, 'GLUC'],
            ['Random Blood Glucose', 'RBG', '2339-0', 'mmol/L', '3.9', '7.8', 'Plasma', 'Fluoride', 400, 4, 'GLUC'],
            // Lipid
            ['Total Cholesterol', 'TC', '2093-3', 'mmol/L', '<5.0', '<5.0', 'Serum', 'Plain', 400, 6, 'LIPID'],
            ['HDL Cholesterol', 'HDL', '2085-9', 'mmol/L', '>1.0', '>1.5', 'Serum', 'Plain', 400, 6, 'LIPID'],
            ['LDL Cholesterol', 'LDL', '13457-7', 'mmol/L', '<3.0', '<3.0', 'Serum', 'Plain', 400, 6, 'LIPID'],
            ['Triglycerides', 'TG', '2571-8', 'mmol/L', '<1.7', '<1.7', 'Serum', 'Plain', 400, 6, 'LIPID'],
            // Malaria
            ['Malaria Parasite (Blood Film)', 'MPBF', '10501-5', '/HPF', 'Negative', 'Negative', 'Whole blood', 'EDTA', 500, 2, 'MAL'],
            ['Malaria RDT', 'MRDT', '9156-9', '', 'Negative', 'Negative', 'Whole blood', 'EDTA', 700, 1, 'MAL'],
            // HIV
            ['HIV 1/2 Antibody', 'HIVAB', '89567-1', '', 'Non-reactive', 'Non-reactive', 'Serum', 'Plain', 800, 2, 'HIV'],
            // Urinalysis components
            ['Urine Protein', 'UPROT', '5804-0', 'mg/dL', 'Negative', 'Negative', 'Urine', 'Sterile', 100, 2, 'UA'],
            ['Urine Glucose', 'UGLUC', '2350-7', 'mmol/L', 'Negative', 'Negative', 'Urine', 'Sterile', 100, 2, 'UA'],
            ['Urine pH', 'UPH', '5802-4', '', '5.0', '8.0', 'Urine', 'Sterile', 100, 2, 'UA'],
            ['Urine Specific Gravity', 'USG', '5811-5', '', '1.005', '1.030', 'Urine', 'Sterile', 100, 2, 'UA'],
        ];

        foreach ($tests as [$name, $code, $loinc, $unit, $low, $high, $specimen, $container, $price, $turnaround, $panelCode]) {
            $panel = $panelCode ? LabPanel::where('code', $panelCode)->first() : null;
            LabTest::firstOrCreate(
                ['code' => $code],
                [
                    'hospital_id' => $hospital->id,
                    'lab_panel_id' => $panel?->id,
                    'name' => $name,
                    'loinc_code' => $loinc,
                    'specimen_type' => $specimen,
                    'container' => $container,
                    'unit' => $unit,
                    'reference_range_low' => $low,
                    'reference_range_high' => $high,
                    'price' => $price,
                    'turnaround_hours' => $turnaround,
                    'status' => true,
                ]
            );
        }
    }
}
