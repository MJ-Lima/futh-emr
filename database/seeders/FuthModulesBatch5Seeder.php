<?php
namespace Database\Seeders;
use App\Models\{GeneralStoreCategory, GeneralStoreItem, GeneralStoreLocation, GeneralStoreStock, Department, User};
use Illuminate\Database\Seeder;

class FuthModulesBatch5Seeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding General Store demo data...');
        $hospitalId = 1;
        $admin = User::first();

        // Categories
        $cats = [
            ['name' => 'Medical Consumables', 'code' => 'GMC', 'type' => 'Medical Supplies'],
            ['name' => 'Surgical Consumables', 'code' => 'GSC', 'type' => 'Surgical Consumables'],
            ['name' => 'Lab Consumables', 'code' => 'GLC', 'type' => 'Lab Consumables'],
            ['name' => 'Office Supplies', 'code' => 'GOS', 'type' => 'Office Supplies'],
            ['name' => 'Cleaning Materials', 'code' => 'GCM', 'type' => 'Cleaning'],
            ['name' => 'Linens', 'code' => 'GLN', 'type' => 'Linens'],
            ['name' => 'Furniture', 'code' => 'GFN', 'type' => 'Furniture'],
            ['name' => 'IT Equipment', 'code' => 'GIT', 'type' => 'IT'],
        ];
        foreach ($cats as $c) {
            GeneralStoreCategory::firstOrCreate(['code' => $c['code']], array_merge($c, ['hospital_id' => $hospitalId, 'is_active' => true]));
        }

        // Locations
        $locs = [
            ['name' => 'Central General Store', 'code' => 'CGS-001', 'type' => 'Main Store', 'building' => 'Admin Block', 'floor' => 'Ground', 'room' => 'G05'],
            ['name' => 'Theatre Store', 'code' => 'GTS-001', 'type' => 'Theatre Store', 'building' => 'Theatre Block', 'floor' => 'First', 'room' => 'T101'],
            ['name' => 'Lab Store', 'code' => 'GLS-001', 'type' => 'Sub-Store', 'building' => 'Lab Block', 'floor' => 'Ground', 'room' => 'L003'],
            ['name' => 'Emergency Store', 'code' => 'GES-001', 'type' => 'Emergency Store', 'building' => 'A&E', 'floor' => 'Ground', 'room' => 'AE001'],
            ['name' => 'Ward Store MMW', 'code' => 'GWS-MMW', 'type' => 'Ward Store', 'building' => 'Ward Block', 'floor' => 'Ground', 'room' => 'MMW-Store'],
        ];
        foreach ($locs as $l) {
            GeneralStoreLocation::firstOrCreate(['code' => $l['code']], array_merge($l, ['hospital_id' => $hospitalId, 'is_active' => true, 'in_charge_id' => $admin->id]));
        }

        // Items
        $items = [
            ['name' => 'Surgical Gloves 7.5', 'code' => 'GSG-75', 'cat' => 'GSC', 'unit' => 'Box of 100', 'cost' => 4500, 'sell' => 0, 'reorder' => 20],
            ['name' => 'Examination Gloves L', 'code' => 'GEG-L', 'cat' => 'GSC', 'unit' => 'Box of 100', 'cost' => 2500, 'sell' => 0, 'reorder' => 30],
            ['name' => 'Face Mask (Surgical)', 'code' => 'GFM-S', 'cat' => 'GSC', 'unit' => 'Box of 50', 'cost' => 1500, 'sell' => 0, 'reorder' => 50],
            ['name' => 'N95 Respirator', 'code' => 'GN95', 'cat' => 'GSC', 'unit' => 'Piece', 'cost' => 350, 'sell' => 0, 'reorder' => 200],
            ['name' => 'Syringe 5ml', 'code' => 'GSY-5', 'cat' => 'GMC', 'unit' => 'Piece', 'cost' => 25, 'sell' => 0, 'reorder' => 1000],
            ['name' => 'Syringe 10ml', 'code' => 'GSY-10', 'cat' => 'GMC', 'unit' => 'Piece', 'cost' => 35, 'sell' => 0, 'reorder' => 1000],
            ['name' => 'IV Giving Set', 'code' => 'GIV-GS', 'cat' => 'GMC', 'unit' => 'Piece', 'cost' => 250, 'sell' => 0, 'reorder' => 200],
            ['name' => 'Cannula 18G', 'code' => 'GCA-18', 'cat' => 'GMC', 'unit' => 'Piece', 'cost' => 200, 'sell' => 0, 'reorder' => 200],
            ['name' => 'Cannula 20G', 'code' => 'GCA-20', 'cat' => 'GMC', 'unit' => 'Piece', 'cost' => 200, 'sell' => 0, 'reorder' => 200],
            ['name' => 'Gauze Swab 10x10', 'code' => 'GGS-10', 'cat' => 'GMC', 'unit' => 'Pack of 100', 'cost' => 1500, 'sell' => 0, 'reorder' => 100],
            ['name' => 'Cotton Wool 500g', 'code' => 'GCW-500', 'cat' => 'GMC', 'unit' => 'Roll', 'cost' => 1200, 'sell' => 0, 'reorder' => 50],
            ['name' => 'Surgical Spirit 1L', 'code' => 'GSS-1L', 'cat' => 'GMC', 'unit' => 'Bottle', 'cost' => 800, 'sell' => 0, 'reorder' => 50],
            ['name' => 'Test Tube (Plain)', 'code' => 'GTT-P', 'cat' => 'GLC', 'unit' => 'Pack of 100', 'cost' => 2000, 'sell' => 0, 'reorder' => 30],
            ['name' => 'Test Tube (EDTA)', 'code' => 'GTT-E', 'cat' => 'GLC', 'unit' => 'Pack of 100', 'cost' => 2500, 'sell' => 0, 'reorder' => 30],
            ['name' => 'Microscope Slide', 'code' => 'GMS-50', 'cat' => 'GLC', 'unit' => 'Pack of 50', 'cost' => 1500, 'sell' => 0, 'reorder' => 20],
            ['name' => 'Bed Sheet (White)', 'code' => 'GBS-W', 'cat' => 'GLN', 'unit' => 'Piece', 'cost' => 2500, 'sell' => 0, 'reorder' => 50],
            ['name' => 'Patient Gown', 'code' => 'GPG-G', 'cat' => 'GLN', 'unit' => 'Piece', 'cost' => 3000, 'sell' => 0, 'reorder' => 50],
            ['name' => 'Pillow Case', 'code' => 'GPC-P', 'cat' => 'GLN', 'unit' => 'Piece', 'cost' => 800, 'sell' => 0, 'reorder' => 100],
            ['name' => 'A4 Paper Rim', 'code' => 'GAP-R', 'cat' => 'GOS', 'unit' => 'Rim', 'cost' => 5500, 'sell' => 0, 'reorder' => 50],
            ['name' => 'Pen (Blue) Box', 'code' => 'GPB-B', 'cat' => 'GOS', 'unit' => 'Box of 50', 'cost' => 3500, 'sell' => 0, 'reorder' => 20],
            ['name' => 'Bleach 5L', 'code' => 'GBL-5', 'cat' => 'GCM', 'unit' => 'Container', 'cost' => 2500, 'sell' => 0, 'reorder' => 30],
            ['name' => 'Detergent 5kg', 'code' => 'GDT-5', 'cat' => 'GCM', 'unit' => 'Pack', 'cost' => 4500, 'sell' => 0, 'reorder' => 20],
            ['name' => 'Diesel (Litre)', 'code' => 'GDL-1', 'cat' => 'Medical Supplies', 'unit' => 'Litre', 'cost' => 1200, 'sell' => 0, 'reorder' => 500],
        ];

        $mainStore = GeneralStoreLocation::where('code', 'CGS-001')->first();
        foreach ($items as $i) {
            $cat = GeneralStoreCategory::where('code', $i['cat'])->first();
            $item = GeneralStoreItem::firstOrCreate(
                ['code' => $i['code']],
                [
                    'hospital_id' => $hospitalId,
                    'general_store_category_id' => $cat?->id,
                    'name' => $i['name'],
                    'unit_of_measure' => $i['unit'],
                    'cost_price' => $i['cost'],
                    'selling_price' => $i['sell'],
                    'reorder_level' => $i['reorder'],
                    'reorder_quantity' => $i['reorder'] * 2,
                    'is_consumable' => true,
                    'is_active' => true,
                ]
            );
            // Add stock
            GeneralStoreStock::firstOrCreate(
                ['general_store_item_id' => $item->id, 'general_store_location_id' => $mainStore->id, 'batch_number' => 'GS-' . $item->id . '-2026'],
                [
                    'hospital_id' => $hospitalId,
                    'quantity_received' => $i['reorder'] * 3,
                    'quantity_remaining' => rand($i['reorder'], $i['reorder'] * 3),
                    'unit_cost' => $i['cost'],
                    'received_date' => now()->subDays(30),
                    'is_quarantined' => false,
                ]
            );
        }

        $this->command->info('General Store seeded:');
        $this->command->info('- ' . GeneralStoreCategory::count() . ' categories');
        $this->command->info('- ' . GeneralStoreLocation::count() . ' locations');
        $this->command->info('- ' . GeneralStoreItem::count() . ' items');
        $this->command->info('- ' . GeneralStoreStock::count() . ' stock batches');
    }
}
