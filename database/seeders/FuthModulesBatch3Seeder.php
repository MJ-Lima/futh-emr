<?php

namespace Database\Seeders;

use App\Models\{BloodDonation, BloodDonor, BloodRequest, BloodTransfusion, BloodUnit, Department, DrugStockLevel, GoodsReceivedNote, GoodsReceivedNoteItem, Patient, Procedure, ProcedureBooking, ProcedureCategory, Service, ServiceCategory, StockTake, StoreCategory, StoreItem, StoreLocation, StoreMovement, StoreRequisition, StoreRequisitionItem, StoreStock, User, Ward};
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FuthModulesBatch3Seeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding FUTH Lafia EMR modules batch 3 (Store, Blood Bank, Procedures, Service Categories, GRN)...');

        $hospitalId = 1;
        $adminUser = User::where('email', 'admin@futhlafia.gov.ng')->first();
        $doctor = User::where('email', 'dr.okoro@futhlafia.gov.ng')->first();
        $pharmacist = User::where('email', 'pharm.musa@futhlafia.gov.ng')->first();
        $labScientist = User::where('email', 'lab.aisha@futhlafia.gov.ng')->first();
        $patients = Patient::limit(6)->get();

        // ============ 1. STORE MODULE ============
        $storeCats = [
            ['name' => 'Medical Supplies', 'code' => 'MSC', 'type' => 'Medical Supplies'],
            ['name' => 'Personal Protective Equipment', 'code' => 'PPE', 'type' => 'PPE'],
            ['name' => 'Office Supplies', 'code' => 'OFC', 'type' => 'Office Supplies'],
            ['name' => 'Cleaning Materials', 'code' => 'CLN', 'type' => 'Cleaning'],
            ['name' => 'Linens', 'code' => 'LNS', 'type' => 'Linens'],
            ['name' => 'Fuel & Lubricants', 'code' => 'FUL', 'type' => 'Fuel & Lubricants'],
            ['name' => 'Stationery', 'code' => 'STN', 'type' => 'Stationery'],
            ['name' => 'IT Supplies', 'code' => 'ITS', 'type' => 'IT'],
        ];
        foreach ($storeCats as $c) {
            StoreCategory::firstOrCreate(
                ['code' => $c['code']],
                array_merge($c, ['hospital_id' => $hospitalId, 'is_active' => true])
            );
        }

        $storeLocations = [
            ['name' => 'Main Store', 'code' => 'MS-001', 'type' => 'Main Store', 'building' => 'Admin Block', 'floor' => 'Ground', 'room' => 'G05', 'department_id' => 1],
            ['name' => 'Theatre Store', 'code' => 'TS-001', 'type' => 'Theatre Store', 'building' => 'Theatre Block', 'floor' => 'First', 'room' => 'T101', 'department_id' => 2],
            ['name' => 'Lab Store', 'code' => 'LS-001', 'type' => 'Lab Store', 'building' => 'Lab Block', 'floor' => 'Ground', 'room' => 'L003', 'department_id' => 6],
            ['name' => 'Emergency Store', 'code' => 'ES-001', 'type' => 'Emergency Store', 'building' => 'A&E Block', 'floor' => 'Ground', 'room' => 'AE001', 'department_id' => 5],
            ['name' => 'Ward Store (Male Medical)', 'code' => 'WS-MMW', 'type' => 'Ward Store', 'building' => 'Ward Block', 'floor' => 'Ground', 'room' => 'MMW-Store', 'department_id' => 1],
        ];
        foreach ($storeLocations as $l) {
            StoreLocation::firstOrCreate(
                ['code' => $l['code']],
                array_merge($l, ['hospital_id' => $hospitalId, 'is_active' => true, 'in_charge_id' => $adminUser->id])
            );
        }

        // Store items
        $storeItems = [
            ['name' => 'Surgical Gloves (Size 7.5)', 'code' => 'MSG-SG75', 'category' => 'PPE', 'unit' => 'Box of 100', 'cost' => 4500, 'sell' => 5500, 'reorder' => 20],
            ['name' => 'Surgical Gloves (Size 8)', 'code' => 'MSG-SG80', 'category' => 'PPE', 'unit' => 'Box of 100', 'cost' => 4500, 'sell' => 5500, 'reorder' => 20],
            ['name' => 'Examination Gloves (L)', 'code' => 'MSG-EXL', 'category' => 'PPE', 'unit' => 'Box of 100', 'cost' => 2500, 'sell' => 3000, 'reorder' => 30],
            ['name' => 'Surgical Face Mask', 'code' => 'MSK-SFM', 'category' => 'PPE', 'unit' => 'Box of 50', 'cost' => 1500, 'sell' => 2000, 'reorder' => 50],
            ['name' => 'N95 Respirator', 'code' => 'MSK-N95', 'category' => 'PPE', 'unit' => 'Piece', 'cost' => 350, 'sell' => 500, 'reorder' => 200],
            ['name' => 'Syringe 5ml Disposable', 'code' => 'MSY-5ML', 'category' => 'MSC', 'unit' => 'Piece', 'cost' => 25, 'sell' => 50, 'reorder' => 1000],
            ['name' => 'Syringe 10ml Disposable', 'code' => 'MSY-10ML', 'category' => 'MSC', 'unit' => 'Piece', 'cost' => 35, 'sell' => 70, 'reorder' => 1000],
            ['name' => 'Needle 21G', 'code' => 'MND-21G', 'category' => 'MSC', 'unit' => 'Piece', 'cost' => 15, 'sell' => 30, 'reorder' => 2000],
            ['name' => 'Needle 23G', 'code' => 'MND-23G', 'category' => 'MSC', 'unit' => 'Piece', 'cost' => 15, 'sell' => 30, 'reorder' => 2000],
            ['name' => 'IV Giving Set', 'code' => 'MIV-GS', 'category' => 'MSC', 'unit' => 'Piece', 'cost' => 250, 'sell' => 400, 'reorder' => 200],
            ['name' => 'Cannula 18G', 'code' => 'MCA-18G', 'category' => 'MSC', 'unit' => 'Piece', 'cost' => 200, 'sell' => 350, 'reorder' => 200],
            ['name' => 'Cannula 20G', 'code' => 'MCA-20G', 'category' => 'MSC', 'unit' => 'Piece', 'cost' => 200, 'sell' => 350, 'reorder' => 200],
            ['name' => 'Gauze Swab 10x10', 'code' => 'MGS-1010', 'category' => 'MSC', 'unit' => 'Pack of 100', 'cost' => 1500, 'sell' => 2000, 'reorder' => 100],
            ['name' => 'Cotton Wool 500g', 'code' => 'MCW-500', 'category' => 'MSC', 'unit' => 'Roll', 'cost' => 1200, 'sell' => 1800, 'reorder' => 50],
            ['name' => 'Surgical Spirit 1L', 'code' => 'MSS-1L', 'category' => 'MSC', 'unit' => 'Bottle', 'cost' => 800, 'sell' => 1200, 'reorder' => 50],
            ['name' => 'Savlon 1L', 'code' => 'MSV-1L', 'category' => 'MSC', 'unit' => 'Bottle', 'cost' => 1500, 'sell' => 2200, 'reorder' => 30],
            ['name' => 'Dettol 1L', 'code' => 'MDT-1L', 'category' => 'MSC', 'unit' => 'Bottle', 'cost' => 1800, 'sell' => 2500, 'reorder' => 30],
            ['name' => 'Bed Sheet (White)', 'code' => 'LBS-WHT', 'category' => 'LNS', 'unit' => 'Piece', 'cost' => 2500, 'sell' => 3500, 'reorder' => 50],
            ['name' => 'Patient Gown', 'code' => 'LPG-GWN', 'category' => 'LNS', 'unit' => 'Piece', 'cost' => 3000, 'sell' => 4500, 'reorder' => 50],
            ['name' => 'Diesel (Generator)', 'code' => 'FUL-DIE', 'category' => 'FUL', 'unit' => 'Litre', 'cost' => 1200, 'sell' => 0, 'reorder' => 500],
            ['name' => 'A4 Paper Rim', 'code' => 'STN-A4', 'category' => 'STN', 'unit' => 'Rim', 'cost' => 5500, 'sell' => 7000, 'reorder' => 50],
            ['name' => 'Pen (Blue)', 'code' => 'STN-PNB', 'category' => 'STN', 'unit' => 'Box of 50', 'cost' => 3500, 'sell' => 4500, 'reorder' => 20],
            ['name' => 'Bleach 5L', 'code' => 'CLN-BL5', 'category' => 'CLN', 'unit' => 'Container', 'cost' => 2500, 'sell' => 3500, 'reorder' => 30],
            ['name' => 'Detergent 5kg', 'code' => 'CLN-DT5', 'category' => 'CLN', 'unit' => 'Pack', 'cost' => 4500, 'sell' => 6000, 'reorder' => 20],
        ];

        foreach ($storeItems as $i) {
            $cat = StoreCategory::where('code', $i['category'])->first();
            StoreItem::firstOrCreate(
                ['code' => $i['code']],
                [
                    'hospital_id'      => $hospitalId,
                    'store_category_id' => $cat?->id,
                    'name'             => $i['name'],
                    'unit_of_measure'  => $i['unit'],
                    'cost_price'       => $i['cost'],
                    'selling_price'    => $i['sell'],
                    'reorder_level'    => $i['reorder'],
                    'reorder_quantity' => $i['reorder'] * 2,
                    'is_consumable'    => true,
                    'is_active'        => true,
                ]
            );
        }

        // Add stock for some items
        $mainStore = StoreLocation::where('code', 'MS-001')->first();
        $stockData = [
            ['code' => 'MSG-SG75', 'batch' => 'PPE-2024-001', 'received' => 100, 'remaining' => 75],
            ['code' => 'MSK-SFM', 'batch' => 'PPE-2024-002', 'received' => 200, 'remaining' => 150],
            ['code' => 'MSY-5ML', 'batch' => 'SYR-2024-001', 'received' => 5000, 'remaining' => 4200],
            ['code' => 'MSY-10ML', 'batch' => 'SYR-2024-002', 'received' => 3000, 'remaining' => 2500],
            ['code' => 'MND-21G', 'batch' => 'NDL-2024-001', 'received' => 10000, 'remaining' => 8200],
            ['code' => 'MND-23G', 'batch' => 'NDL-2024-002', 'received' => 10000, 'remaining' => 7500],
            ['code' => 'MIV-GS', 'batch' => 'IVS-2024-001', 'received' => 500, 'remaining' => 320],
            ['code' => 'MCA-18G', 'batch' => 'CAN-2024-001', 'received' => 500, 'remaining' => 280],
            ['code' => 'MCA-20G', 'batch' => 'CAN-2024-002', 'received' => 500, 'remaining' => 310],
            ['code' => 'LBS-WHT', 'batch' => 'LNS-2024-001', 'received' => 200, 'remaining' => 145],
            ['code' => 'FUL-DIE', 'batch' => 'FUL-2024-001', 'received' => 2000, 'remaining' => 850],
        ];

        foreach ($stockData as $s) {
            $item = StoreItem::where('code', $s['code'])->first();
            if (!$item) continue;
            StoreStock::firstOrCreate(
                ['store_item_id' => $item->id, 'store_location_id' => $mainStore->id, 'batch_number' => $s['batch']],
                [
                    'hospital_id'       => $hospitalId,
                    'quantity_received' => $s['received'],
                    'quantity_remaining'=> $s['remaining'],
                    'unit_cost'         => $item->cost_price,
                    'received_date'     => now()->subDays(45),
                    'is_quarantined'    => false,
                ]
            );
        }

        // Store requisition example
        $req = StoreRequisition::firstOrCreate(
            ['requisition_number' => 'REQ-' . now()->year . '-000001'],
            [
                'hospital_id' => $hospitalId,
                'requesting_department_id' => 5,
                'requesting_location_id' => StoreLocation::where('code', 'ES-001')->first()->id,
                'source_location_id' => $mainStore->id,
                'requested_by' => $adminUser->id,
                'required_date' => now()->addDay(),
                'priority' => 'Urgent',
                'purpose' => 'Restock A&E for night shift',
                'status' => 'Approved',
                'approved_by' => $adminUser->id,
                'approved_date' => now(),
            ]
        );
        $item = StoreItem::where('code', 'MSY-5ML')->first();
        StoreRequisitionItem::firstOrCreate(
            ['store_requisition_id' => $req->id, 'store_item_id' => $item->id],
            [
                'quantity_requested' => 500,
                'quantity_approved' => 500,
                'quantity_issued' => 0,
                'unit_cost' => $item->cost_price,
            ]
        );

        // ============ 2. BLOOD BANK ============
        $donors = [
            ['first' => 'Chinedu', 'last' => 'Okafor', 'gender' => 'Male', 'dob' => '1990-05-15', 'blood_group' => 'O+', 'rh' => 'Positive', 'phone' => '08031234567', 'occupation' => 'Engineer'],
            ['first' => 'Fatima', 'last' => 'Ibrahim', 'gender' => 'Female', 'dob' => '1985-08-22', 'blood_group' => 'A+', 'rh' => 'Positive', 'phone' => '08037654321', 'occupation' => 'Teacher'],
            ['first' => 'Samuel', 'last' => 'Eze', 'gender' => 'Male', 'dob' => '1988-12-03', 'blood_group' => 'B+', 'rh' => 'Positive', 'phone' => '08089876543', 'occupation' => 'Civil Servant'],
            ['first' => 'Grace', 'last' => 'Adeyemi', 'gender' => 'Female', 'dob' => '1992-03-17', 'blood_group' => 'AB+', 'rh' => 'Positive', 'phone' => '08052345678', 'occupation' => 'Nurse'],
            ['first' => 'Mohammed', 'last' => 'Bello', 'gender' => 'Male', 'dob' => '1987-07-29', 'blood_group' => 'O-', 'rh' => 'Negative', 'phone' => '08064567890', 'occupation' => 'Trader'],
            ['first' => 'Aisha', 'last' => 'Mohammed', 'gender' => 'Female', 'dob' => '1993-11-08', 'blood_group' => 'A-', 'rh' => 'Negative', 'phone' => '08078901234', 'occupation' => 'Banker'],
            ['first' => 'David', 'last' => 'Olawale', 'gender' => 'Male', 'dob' => '1991-04-21', 'blood_group' => 'B-', 'rh' => 'Negative', 'phone' => '08083456789', 'occupation' => 'Driver'],
            ['first' => 'Mary', 'last' => 'Okon', 'gender' => 'Female', 'dob' => '1989-09-14', 'blood_group' => 'O+', 'rh' => 'Positive', 'phone' => '08095678901', 'occupation' => 'Trader'],
        ];

        foreach ($donors as $d) {
            BloodDonor::firstOrCreate(
                ['first_name' => $d['first'], 'last_name' => $d['last'], 'hospital_id' => $hospitalId],
                [
                    'donor_type' => 'Voluntary',
                    'gender' => $d['gender'],
                    'date_of_birth' => $d['dob'],
                    'phone' => $d['phone'],
                    'occupation' => $d['occupation'],
                    'blood_group' => $d['blood_group'],
                    'rh_factor' => $d['rh'],
                    'total_donations' => 0,
                    'total_volume_donated_ml' => 0,
                    'is_eligible' => true,
                    'status' => 'Active',
                ]
            );
        }

        // Donations + units
        $donorRecords = BloodDonor::all();
        foreach ($donorRecords->take(5) as $idx => $donor) {
            $donation = BloodDonation::firstOrCreate(
                ['donor_id' => $donor->id, 'donation_date' => now()->subDays(10 - $idx)],
                [
                    'hospital_id' => $hospitalId,
                    'donation_time' => '10:00:00',
                    'donation_type' => 'Whole Blood',
                    'volume_collected_ml' => 450,
                    'collected_by' => $labScientist->id,
                    'collection_site' => 'Blood Bank',
                    'vein_used' => 'Left Antecubital',
                    'status' => 'Available',
                ]
            );

            // Create a blood unit
            $unit = BloodUnit::firstOrCreate(
                ['donation_id' => $donation->id],
                [
                    'hospital_id' => $hospitalId,
                    'donor_id' => $donor->id,
                    'blood_group' => $donor->blood_group,
                    'component_type' => 'Whole Blood',
                    'volume_ml' => 450,
                    'collection_date' => $donation->donation_date,
                    'storage_location' => 'Blood Bank Refrigerator A',
                    'storage_temperature_c' => 4.0,
                    'hiv_status' => 'Negative',
                    'hepatitis_b_status' => 'Negative',
                    'hepatitis_c_status' => 'Negative',
                    'syphilis_status' => 'Negative',
                    'malaria_status' => 'Negative',
                    'screened_by' => $labScientist->id,
                    'screened_at' => $donation->donation_date,
                    'is_screened' => true,
                    'is_quarantined' => false,
                    'status' => 'Available',
                ]
            );
        }

        // Blood request for a patient
        if ($patients->isNotEmpty()) {
            BloodRequest::firstOrCreate(
                ['request_number' => 'BTR-' . now()->year . '-000001'],
                [
                    'hospital_id' => $hospitalId,
                    'patient_id' => $patients[2]->id,
                    'requested_by' => $doctor->id,
                    'requested_blood_group' => 'O+',
                    'component_requested' => 'Whole Blood',
                    'units_requested' => 2,
                    'urgency' => 'Urgent',
                    'required_by' => now()->addDay(),
                    'clinical_indication' => 'Severe anaemia, Hb 5.2 g/dL',
                    'diagnosis' => 'Severe Malaria with Anaemia',
                    'patient_hb_level' => 5.2,
                    'patient_blood_group' => 'O+',
                    'patient_rh_factor' => 'Positive',
                    'status' => 'Approved',
                    'approved_by' => $doctor->id,
                    'approved_at' => now(),
                ]
            );
        }

        // ============ 3. PROCEDURES ============
        $procCategories = [
            ['name' => 'Minor Surgical Procedures', 'code' => 'MSP', 'specialty' => 'General Surgery'],
            ['name' => 'Major Surgical Procedures', 'code' => 'MJR', 'specialty' => 'General Surgery'],
            ['name' => 'Obstetric Procedures', 'code' => 'OBS', 'specialty' => 'OBGYN'],
            ['name' => 'Orthopaedic Procedures', 'code' => 'ORT', 'specialty' => 'Orthopaedics'],
            ['name' => 'Endoscopic Procedures', 'code' => 'END', 'specialty' => 'Endoscopy'],
        ];
        foreach ($procCategories as $pc) {
            ProcedureCategory::firstOrCreate(
                ['code' => $pc['code']],
                array_merge($pc, ['hospital_id' => $hospitalId, 'is_active' => true])
            );
        }

        $procedures = [
            ['name' => 'Appendectomy', 'code' => 'APP-001', 'category' => 'MJR', 'type' => 'Major', 'site' => 'Theatre', 'anaesthesia' => 'General', 'duration' => 90, 'fee' => 150000, 'nhia' => 80000, 'surgeon' => 50000, 'anaes' => 25000, 'theatre' => 50000, 'cons' => 25000],
            ['name' => 'Hernia Repair', 'code' => 'HRN-001', 'category' => 'MJR', 'type' => 'Major', 'site' => 'Theatre', 'anaesthesia' => 'Spinal', 'duration' => 75, 'fee' => 120000, 'nhia' => 60000, 'surgeon' => 40000, 'anaes' => 20000, 'theatre' => 40000, 'cons' => 20000],
            ['name' => 'Caesarean Section', 'code' => 'CS-001', 'category' => 'OBS', 'type' => 'Major', 'site' => 'Theatre', 'anaesthesia' => 'Spinal', 'duration' => 60, 'fee' => 180000, 'nhia' => 90000, 'surgeon' => 60000, 'anaes' => 30000, 'theatre' => 60000, 'cons' => 30000],
            ['name' => 'Normal Delivery (Episiotomy)', 'code' => 'NVD-001', 'category' => 'OBS', 'type' => 'Minor', 'site' => 'Theatre', 'anaesthesia' => 'Local', 'duration' => 45, 'fee' => 35000, 'nhia' => 20000, 'surgeon' => 15000, 'anaes' => 5000, 'theatre' => 10000, 'cons' => 5000],
            ['name' => 'Incision & Drainage (Abscess)', 'code' => 'IAD-001', 'category' => 'MSP', 'type' => 'Minor', 'site' => 'Procedure Room', 'anaesthesia' => 'Local', 'duration' => 30, 'fee' => 15000, 'nhia' => 8000, 'surgeon' => 7000, 'anaes' => 0, 'theatre' => 5000, 'cons' => 3000],
            ['name' => 'Suture Removal', 'code' => 'SUR-001', 'category' => 'MSP', 'type' => 'Minor', 'site' => 'Consulting Room', 'anaesthesia' => 'None', 'duration' => 15, 'fee' => 3000, 'nhia' => 1500, 'surgeon' => 1500, 'anaes' => 0, 'theatre' => 0, 'cons' => 500],
            ['name' => 'Wound Debridement', 'code' => 'WDB-001', 'category' => 'MSP', 'type' => 'Minor', 'site' => 'Procedure Room', 'anaesthesia' => 'Local', 'duration' => 30, 'fee' => 10000, 'nhia' => 5000, 'surgeon' => 5000, 'anaes' => 0, 'theatre' => 3000, 'cons' => 2000],
            ['name' => 'Fracture Reduction (Closed)', 'code' => 'FRC-001', 'category' => 'ORT', 'type' => 'Intermediate', 'site' => 'Theatre', 'anaesthesia' => 'General', 'duration' => 60, 'fee' => 80000, 'nhia' => 40000, 'surgeon' => 30000, 'anaes' => 15000, 'theatre' => 25000, 'cons' => 10000],
            ['name' => 'Upper GI Endoscopy', 'code' => 'UGE-001', 'category' => 'END', 'type' => 'Intermediate', 'site' => 'Procedure Room', 'anaesthesia' => 'Sedation', 'duration' => 30, 'fee' => 45000, 'nhia' => 25000, 'surgeon' => 20000, 'anaes' => 10000, 'theatre' => 10000, 'cons' => 5000],
            ['name' => 'Colonoscopy', 'code' => 'CLN-001', 'category' => 'END', 'type' => 'Intermediate', 'site' => 'Procedure Room', 'anaesthesia' => 'Sedation', 'duration' => 45, 'fee' => 60000, 'nhia' => 35000, 'surgeon' => 25000, 'anaes' => 12000, 'theatre' => 15000, 'cons' => 8000],
        ];

        foreach ($procedures as $p) {
            $cat = ProcedureCategory::where('code', $p['category'])->first();
            Procedure::firstOrCreate(
                ['code' => $p['code']],
                [
                    'hospital_id' => $hospitalId,
                    'procedure_category_id' => $cat?->id,
                    'name' => $p['name'],
                    'type' => $p['type'],
                    'site' => $p['site'],
                    'anaesthesia_type' => $p['anaesthesia'],
                    'estimated_duration_minutes' => $p['duration'],
                    'default_pre_op_days' => 1,
                    'default_post_op_days' => 1,
                    'standard_fee' => $p['fee'],
                    'nhia_rate' => $p['nhia'],
                    'private_rate' => $p['fee'] * 1.5,
                    'surgeon_fee' => $p['surgeon'],
                    'anaesthetist_fee' => $p['anaes'],
                    'theatre_fee' => $p['theatre'],
                    'consumables_fee' => $p['cons'],
                    'is_active' => true,
                ]
            );
        }

        // Procedure booking example
        if ($patients->isNotEmpty() && $doctor) {
            $proc = Procedure::where('code', 'APP-001')->first();
            ProcedureBooking::firstOrCreate(
                ['booking_number' => 'PBK-' . now()->year . '-000001'],
                [
                    'hospital_id' => $hospitalId,
                    'patient_id' => $patients[3]->id,
                    'procedure_id' => $proc->id,
                    'surgeon_id' => $doctor->id,
                    'scheduled_start' => now()->addDays(2)->setHour(9)->setMinute(0),
                    'scheduled_end' => now()->addDays(2)->setHour(10)->setMinute(30),
                    'priority' => 'Elective',
                    'booking_status' => 'Confirmed',
                    'consent_signed' => true,
                    'consent_signed_at' => now()->subDay(),
                    'consent_witnessed_by' => $adminUser->id,
                    'fasting_confirmed' => true,
                    'investigations_done' => true,
                    'anaesthesia_review_done' => true,
                    'pre_op_notes' => 'Patient prepared for elective appendectomy. Pre-op investigations within normal limits.',
                    'created_by' => $doctor->id,
                ]
            );
        }

        // ============ 4. SERVICE CATEGORIES ============
        $svcCats = [
            ['name' => 'General Consultations', 'code' => 'CON-GEN', 'type' => 'Consultation', 'color' => '#0f5132'],
            ['name' => 'Specialist Consultations', 'code' => 'CON-SPC', 'type' => 'Consultation', 'color' => '#198754'],
            ['name' => 'Emergency Consultations', 'code' => 'CON-EMR', 'type' => 'Emergency', 'color' => '#dc3545'],
            ['name' => 'Laboratory Tests', 'code' => 'LAB-GEN', 'type' => 'Laboratory', 'color' => '#0dcaf0'],
            ['name' => 'Radiology Services', 'code' => 'RAD-GEN', 'type' => 'Radiology', 'color' => '#6610f2'],
            ['name' => 'Pharmacy Items', 'code' => 'PHM-GEN', 'type' => 'Pharmacy', 'color' => '#fd7e14'],
            ['name' => 'Inpatient Accommodation', 'code' => 'Ward-GEN', 'type' => 'Accommodation', 'color' => '#6c757d'],
            ['name' => 'Minor Procedures', 'code' => 'PRC-MIN', 'type' => 'Procedure', 'color' => '#ffc107'],
            ['name' => 'Major Procedures', 'code' => 'PRC-MAJ', 'type' => 'Procedure', 'color' => '#fd7e14'],
            ['name' => 'Maternity Services', 'code' => 'MAT-GEN', 'type' => 'Procedure', 'color' => '#e83e8c'],
        ];
        foreach ($svcCats as $sc) {
            ServiceCategory::firstOrCreate(
                ['code' => $sc['code']],
                array_merge($sc, ['hospital_id' => $hospitalId, 'is_active' => true])
            );
        }

        // Link existing services to categories
        $consultCat = ServiceCategory::where('code', 'CON-GEN')->first();
        Service::where('code', 'CONS-GEN')->update(['service_category_id' => $consultCat->id]);
        $specConsultCat = ServiceCategory::where('code', 'CON-SPC')->first();
        Service::where('code', 'CONS-SPC')->update(['service_category_id' => $specConsultCat->id]);
        $labCat = ServiceCategory::where('code', 'LAB-GEN')->first();
        Service::whereIn('code', ['LAB-FBC', 'LAB-MPS'])->update(['service_category_id' => $labCat->id]);
        $radCat = ServiceCategory::where('code', 'RAD-GEN')->first();
        Service::whereIn('code', ['RAD-CXR', 'RAD-US-PV'])->update(['service_category_id' => $radCat->id]);

        // ============ 5. PHARMACY STOCK ENHANCEMENTS ============
        // Initialize drug_stock_levels for existing drugs
        $drugs = \App\Models\Drug::limit(20)->get();
        $dispPoint = \App\Models\DispensingPoint::first();
        foreach ($drugs as $drug) {
            DrugStockLevel::firstOrCreate(
                ['drug_id' => $drug->id, 'dispensing_point_id' => $dispPoint?->id],
                [
                    'hospital_id' => $hospitalId,
                    'quantity_on_hand' => rand(50, 500),
                    'quantity_reserved' => 0,
                    'quantity_available' => rand(50, 500),
                    'reorder_level' => $drug->reorder_level,
                    'reorder_quantity' => $drug->reorder_quantity,
                    'last_received_date' => now()->subDays(rand(1, 30)),
                    'last_dispensed_date' => now()->subDays(rand(0, 5)),
                    'stock_status' => 'In Stock',
                ]
            );
        }

        // GRN example
        $supplier = \App\Models\Supplier::first();
        $grn = GoodsReceivedNote::firstOrCreate(
            ['grn_number' => 'GRN-' . now()->year . '-000001'],
            [
                'hospital_id' => $hospitalId,
                'supplier_id' => $supplier?->id,
                'dispensing_point_id' => $dispPoint?->id,
                'received_by' => $pharmacist->id,
                'received_at' => now()->subDays(7),
                'invoice_number' => 'INV-2024-001',
                'delivery_note_number' => 'DN-2024-001',
                'invoice_date' => now()->subDays(10),
                'total_value' => 0,
                'status' => 'Verified',
                'verified_by' => $adminUser->id,
            ]
        );

        // Add a few GRN items
        $sampleDrugs = \App\Models\Drug::limit(5)->get();
        foreach ($sampleDrugs as $d) {
            $qty = rand(100, 500);
            $unitCost = $d->cost_price;
            GoodsReceivedNoteItem::firstOrCreate(
                ['grn_id' => $grn->id, 'drug_id' => $d->id],
                [
                    'drug_name' => $d->name,
                    'batch_number' => 'BT-' . $d->id . '-2024',
                    'expiry_date' => now()->addYears(2),
                    'quantity_ordered' => $qty,
                    'quantity_received' => $qty,
                    'quantity_rejected' => 0,
                    'unit_cost' => $unitCost,
                    'line_total' => $qty * $unitCost,
                ]
            );
        }
        $grn->recalculateTotals();

        $this->command->info('Modules batch 3 seeded:');
        $this->command->info('- Store: ' . StoreCategory::count() . ' categories, ' . StoreLocation::count() . ' locations, ' . StoreItem::count() . ' items, ' . StoreStock::count() . ' stocks, ' . StoreRequisition::count() . ' requisitions');
        $this->command->info('- Blood Bank: ' . BloodDonor::count() . ' donors, ' . BloodDonation::count() . ' donations, ' . BloodUnit::count() . ' units, ' . BloodRequest::count() . ' requests');
        $this->command->info('- Procedures: ' . ProcedureCategory::count() . ' categories, ' . Procedure::count() . ' procedures, ' . ProcedureBooking::count() . ' bookings');
        $this->command->info('- Service Categories: ' . ServiceCategory::count() . ' categories');
        $this->command->info('- Pharmacy Stock: ' . DrugStockLevel::count() . ' drug stock levels, ' . GoodsReceivedNote::count() . ' GRNs');
    }
}
