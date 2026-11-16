<?php

namespace Database\Seeders;

use App\Models\Drug;
use App\Models\DrugBatch;
use App\Models\DrugCategory;
use App\Models\DrugInteraction;
use App\Models\Hospital;
use Illuminate\Database\Seeder;

/**
 * Seed pharmacy catalog using WHO Essential Medicines List (EML) categories
 * and ATC (Anatomical Therapeutic Chemical) classification codes.
 */
class DrugCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $hospital = Hospital::where('code', 'FUTH-LF')->first();

        $categories = [
            ['N', 'Nervous system', 'Analgesics, anaesthetics, anticonvulsants'],
            ['J', 'Antiinfectives for systemic use', 'Antibiotics, antivirals, antifungals, antimalarials'],
            ['C', 'Cardiovascular system', 'Antihypertensives, cardiac drugs'],
            ['A', 'Alimentary tract and metabolism', 'Antacids, antidiarrhoeals, antidiabetics'],
            ['R', 'Respiratory system', 'Bronchodilators, antihistamines'],
            ['M', 'Musculo-skeletal system', 'NSAIDs, muscle relaxants'],
            ['G', 'Genito urinary system', 'Diuretics, hormonal contraceptives'],
            ['H', 'Systemic hormonalonal preparations', 'Corticosteroids'],
            ['D', 'Dermatologicals', 'Topical antifungals, antiseptics'],
            ['P', 'Antiparasitic products', 'Antimalarials, antihelminthics'],
            ['V', 'Various', 'Nutrients, electrolytes, vitamins'],
            ['S', 'Sensory organs', 'Ophthalmic, otic preparations'],
        ];

        foreach ($categories as [$code, $name, $desc]) {
            DrugCategory::firstOrCreate(['code' => $code], ['name' => $name, 'description' => $desc, 'status' => true]);
        }

        // WHO Essential Medicines List — Nigeria context
        $drugs = [
            // [name, generic, code, atc, category, dosage_form, strength, unit, cost, sell, controlled, antenatal, paed]
            ['Paracetamol Tab', 'Paracetamol', 'DRG-001', 'N02BE01', 'N', 'Tablet', '500 mg', 'tablet', 5, 10, false, true, true],
            ['Ibuprofen Tab',   'Ibuprofen',   'DRG-002', 'M01AE01', 'M', 'Tablet', '400 mg', 'tablet', 8, 15, false, false, false],
            ['Aspirin Tab',     'Acetylsalicylic acid', 'DRG-003', 'N02BA01', 'N', 'Tablet', '75 mg', 'tablet', 4, 8, false, false, false],
            ['Diclofenac Tab',  'Diclofenac sodium', 'DRG-004', 'M01AB05', 'M', 'Tablet', '50 mg', 'tablet', 7, 14, false, false, false],
            ['Amoxicillin Cap', 'Amoxicillin', 'DRG-005', 'J01CA04', 'J', 'Capsule', '500 mg', 'capsule', 12, 25, false, true, true],
            ['Amoxicillin Susp','Amoxicillin', 'DRG-006', 'J01CA04', 'J', 'Suspension', '125 mg/5 mL', 'bottle', 80, 150, false, true, true],
            ['Cotrimoxazole Tab', 'Co-trimoxazole', 'DRG-007', 'J01EE01', 'J', 'Tablet', '480 mg', 'tablet', 6, 12, false, false, true],
            ['Metronidazole Tab','Metronidazole', 'DRG-008', 'J01XD01', 'J', 'Tablet', '400 mg', 'tablet', 5, 11, false, false, false],
            ['Artemether/Lumefantrine', 'Artemether + Lumefantrine', 'DRG-009', 'P01BF02', 'P', 'Tablet', '20/120 mg', 'tablet', 50, 100, false, true, true],
            ['Artesunate Inject', 'Artesunate', 'DRG-010', 'P01BE03', 'P', 'Injection', '60 mg/mL', 'vial', 150, 300, false, true, true],
            ['Chloroquine Tab','Chloroquine phosphate', 'DRG-011', 'P01BA01', 'P', 'Tablet', '250 mg', 'tablet', 8, 15, false, true, true],
            ['Sulphadoxine/Pyrimethamine','Sulfadoxine+Pyrimethamine','DRG-012', 'P01BF01', 'P', 'Tablet', '500/25 mg', 'tablet', 25, 50, false, false, false],
            ['Amodiaquine Tab','Amodiaquine', 'DRG-013', 'P01BA06', 'P', 'Tablet', '200 mg', 'tablet', 9, 18, false, true, true],
            ['Zidovudine/Lamivudine Tab','Zidovudine + Lamivudine','DRG-014', 'J05AR01', 'J', 'Tablet', '300/150 mg', 'tablet', 80, 0, false, true, false],
            ['Tenofovir/Lamivudine/Dolutegravir','Tenofovir + Lamivudine + Dolutegravir','DRG-015', 'J05AR18', 'J', 'Tablet', '300/300/50 mg', 'tablet', 120, 0, false, true, true],
            ['Isoniazid Tab', 'Isoniazid', 'DRG-016', 'J04AC01', 'J', 'Tablet', '300 mg', 'tablet', 4, 8, false, true, true],
            ['Rifampicin Cap', 'Rifampicin', 'DRG-017', 'J04AB02', 'J', 'Capsule', '300 mg', 'capsule', 12, 25, false, false, true],
            ['Ethambutol Tab', 'Ethambutol', 'DRG-018', 'J04AK02', 'J', 'Tablet', '400 mg', 'tablet', 7, 14, false, false, false],
            ['Pyrazinamide Tab','Pyrazinamide', 'DRG-019', 'J04AK03', 'J', 'Tablet', '500 mg', 'tablet', 9, 18, false, true, true],
            ['Amlodipine Tab', 'Amlodipine', 'DRG-020', 'C08CA01', 'C', 'Tablet', '10 mg', 'tablet', 6, 13, false, false, false],
            ['Lisinopril Tab', 'Lisinopril', 'DRG-021', 'C09AA03', 'C', 'Tablet', '10 mg', 'tablet', 7, 14, false, true, false],
            ['Atenolol Tab', 'Atenolol', 'DRG-022', 'C07AB03', 'C', 'Tablet', '50 mg', 'tablet', 5, 11, false, false, false],
            ['Hydrochlorothiazide','Hydrochlorothiazide', 'DRG-023', 'C03AA03', 'C', 'Tablet', '25 mg', 'tablet', 4, 9, false, false, false],
            ['Furosemide Tab', 'Furosemide', 'DRG-024', 'C03CA01', 'C', 'Tablet', '40 mg', 'tablet', 5, 11, false, false, false],
            ['Metformin Tab', 'Metformin', 'DRG-025', 'A10BA02', 'A', 'Tablet', '500 mg', 'tablet', 6, 13, false, true, true],
            ['Glibenclamide Tab', 'Glibenclamide', 'DRG-026', 'A10BB01', 'A', 'Tablet', '5 mg', 'tablet', 5, 11, false, false, false],
            ['Insulin (soluble)', 'Insulin (regular)', 'DRG-027', 'A10AB01', 'A', 'Injection', '100 IU/mL', 'vial', 350, 700, false, true, true],
            ['Salbutamol Inh', 'Salbutamol', 'DRG-028', 'R03AC02', 'R', 'Inhaler', '100 mcg/dose', 'inhaler', 350, 700, false, true, true],
            ['Hydrocortisone Inj', 'Hydrocortisone', 'DRG-029', 'H02AB09', 'H', 'Injection', '100 mg', 'vial', 80, 160, false, true, true],
            ['Prednisolone Tab', 'Prednisolone', 'DRG-030', 'H02AB07', 'H', 'Tablet', '5 mg', 'tablet', 5, 11, false, false, true],
            ['Omeprazole Cap', 'Omeprazole', 'DRG-031', 'A02BC01', 'A', 'Capsule', '20 mg', 'capsule', 8, 17, false, true, false],
            ['Aluminium/Magnesium Antacid', 'Al + Mg hydroxide', 'DRG-032', 'A02AD10', 'A', 'Suspension', '200 mg/5 mL', 'bottle', 120, 240, false, true, true],
            ['Oral Rehydration Salts', 'ORS', 'DRG-033', 'A07CA03', 'A', 'Powder', '20.5 g/L', 'sachet', 35, 70, false, true, true],
            ['Zinc Sulphate Tab', 'Zinc sulfate', 'DRG-034', 'A12CX01', 'A', 'Tablet', '20 mg', 'tablet', 3, 7, false, true, true],
            ['Ferrous Sulphate Tab', 'Ferrous sulfate', 'DRG-035', 'B03AA07', 'A', 'Tablet', '200 mg', 'tablet', 4, 9, false, true, true],
            ['Folic Acid Tab', 'Folic acid', 'DRG-036', 'B03BB01', 'A', 'Tablet', '5 mg', 'tablet', 2, 5, false, true, true],
            ['Magnesium Sulfate Inj','Magnesium sulfate', 'DRG-037', 'A06AD04', 'A', 'Injection', '500 mg/mL', 'vial', 60, 120, false, true, false],
            ['Oxytocin Inj', 'Oxytocin', 'DRG-038', 'H01BB02', 'H', 'Injection', '10 IU/mL', 'ampoule', 80, 160, false, true, false],
            ['Diazepam Inj', 'Diazepam', 'DRG-039', 'N05BA01', 'N', 'Injection', '5 mg/mL', 'ampoule', 25, 50, true, false, false],
            ['Phenytoin Tab', 'Phenytoin', 'DRG-040', 'N03AB02', 'N', 'Tablet', '100 mg', 'tablet', 5, 11, false, true, true],
            ['Carbamazepine Tab','Carbamazepine', 'DRG-041', 'N03AF01', 'N', 'Tablet', '200 mg', 'tablet', 8, 17, false, true, true],
            ['Haloperidol Tab', 'Haloperidol', 'DRG-042', 'N05AD01', 'N', 'Tablet', '5 mg', 'tablet', 7, 14, true, false, false],
            ['Chlorpromazine Tab','Chlorpromazine', 'DRG-043', 'N05AA01', 'N', 'Tablet', '100 mg', 'tablet', 8, 16, true, false, false],
            ['Adrenaline Inj', 'Epinephrine', 'DRG-044', 'C01CA24', 'C', 'Injection', '1 mg/mL', 'ampoule', 70, 140, false, true, true],
            ['Atropine Inj', 'Atropine sulfate', 'DRG-045', 'A03BA04', 'A', 'Injection', '0.6 mg/mL', 'ampoule', 35, 70, false, true, true],
            ['Povidone Iodine Soln','Povidone iodine', 'DRG-046', 'D08AG01', 'D', 'Solution', '10%', 'bottle', 80, 160, false, true, true],
            ['Gentamicin Inj', 'Gentamicin', 'DRG-047', 'J01GB03', 'J', 'Injection', '40 mg/mL', 'ampoule', 25, 50, false, false, false],
            ['Ceftriaxone Inj', 'Ceftriaxone', 'DRG-048', 'J01DD04', 'J', 'Injection', '1 g', 'vial', 150, 300, false, true, true],
            ['Ciprofloxacin Tab', 'Ciprofloxacin', 'DRG-049', 'J01MA02', 'J', 'Tablet', '500 mg', 'tablet', 10, 20, false, false, false],
            ['Quinine Tab', 'Quinine sulfate', 'DRG-050', 'P01BC01', 'P', 'Tablet', '300 mg', 'tablet', 7, 14, false, true, true],
        ];

        foreach ($drugs as [$name, $generic, $code, $atc, $catCode, $form, $strength, $unit, $cost, $sell, $controlled, $antenatal, $paed]) {
            $category = DrugCategory::where('code', $catCode)->first();
            $drug = Drug::firstOrCreate(
                ['code' => $code],
                [
                    'hospital_id' => $hospital->id,
                    'drug_category_id' => $category?->id,
                    'name' => $name,
                    'generic_name' => $generic,
                    'atc_code' => $atc,
                    'dosage_form' => $form,
                    'strength' => $strength,
                    'unit_of_measure' => $unit,
                    'cost_price' => $cost,
                    'selling_price' => $sell,
                    'reorder_level' => 100,
                    'reorder_quantity' => 500,
                    'is_controlled' => $controlled,
                    'is_antenatal_safe' => $antenatal,
                    'is_pediatric_safe' => $paed,
                    'status' => true,
                ]
            );

            // Create one active batch per drug
            DrugBatch::firstOrCreate(
                ['drug_id' => $drug->id, 'batch_number' => 'BATCH-' . $code],
                [
                    'expiry_date' => now()->addMonths(rand(6, 24)),
                    'manufactured_date' => now()->subMonths(rand(3, 12)),
                    'quantity_received' => 1000,
                    'quantity_remaining' => rand(50, 950),
                    'cost_price' => $cost,
                    'supplier' => 'FUTH Central Store',
                    'received_date' => now()->subMonths(3),
                    'is_quarantined' => false,
                ]
            );
        }

        // Common drug-drug interactions (clinically significant)
        $interactions = [
            [['DRG-020', 'DRG-022'], 'Major', 'Altered metabolism', 'Amlodipine + Atenolol — additive hypotensive effect; monitor BP closely.', 'Use with caution, monitor BP', 'Stockley 2024'],
            [['DRG-022', 'DRG-023'], 'Moderate', 'Additive', 'Beta-blocker + Thiazide — additive antihypertensive; risk of bradycardia.', 'Monitor HR & BP', 'Medscape'],
            [['DRG-002', 'DRG-029'], 'Moderate', 'Increased toxicity', 'NSAID + Corticosteroid — increased GI bleed risk.', 'Co-prescribe PPI', 'Stockley 2024'],
            [['DRG-005', 'DRG-030'], 'Minor', 'Decreased efficacy', 'Amoxicillin absorption unaffected; Prednisolone may reduce immune response.', 'Routine monitoring', 'Medscape'],
            [['DRG-039', 'DRG-030'], 'Major', 'Increased toxicity', 'Diazepam + Prednisolone — CNS depression risk.', 'Avoid co-prescription', 'Stockley 2024'],
            [['DRG-049', 'DRG-026'], 'Major', 'Altered metabolism', 'Ciprofloxacin + Glibenclamide — hypoglycaemia risk.', 'Monitor blood glucose', 'Stockley 2024'],
        ];

        foreach ($interactions as [[$a, $b], $severity, $effect, $desc, $action, $source]) {
            $drugA = Drug::where('code', $a)->first();
            $drugB = Drug::where('code', $b)->first();
            if ($drugA && $drugB) {
                DrugInteraction::firstOrCreate(
                    ['drug_a_id' => $drugA->id, 'drug_b_id' => $drugB->id],
                    [
                        'severity' => $severity,
                        'effect' => $effect,
                        'description' => $desc,
                        'clinical_action' => $action,
                        'source' => $source,
                        'status' => true,
                    ]
                );
            }
        }
    }
}
