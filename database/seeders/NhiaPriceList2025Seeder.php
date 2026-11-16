<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Imports the complete NHIA Procedure Price List 2025 (April 2025) as the
 * tariff for BOTH insurance schemes:
 *
 *   - NHIS  — federal NHIA rate  → services.nhia_rate
 *   - NASHIA — Nasarawa State rate → services.nashia_rate
 *
 * The list applies to both schemes, so both columns receive the same
 * published price. Each rate remains individually editable afterwards via
 * Billing & Finance → Services.
 *
 * Safe to re-run: services are upserted by their unique `code`, so prices
 * are refreshed on every run without duplicating rows.
 *
 * Data source: database/seeders/data/nhia_price_list_2025.json (parsed
 * from the official NHIA Procedure Price List 2025 PDF, nhia.gov.ng).
 */
class NhiaPriceList2025Seeder extends Seeder
{
    /** FUTH Lafia Teaching Hospital — matches the existing tariff data. */
    protected int $hospitalId = 1;

    public function run(): void
    {
        $path = database_path('seeders/data/nhia_price_list_2025.json');

        if (! file_exists($path)) {
            $this->command?->error("NHIA price list data file not found: {$path}");

            return;
        }

        $payload = json_decode((string) file_get_contents($path), true);

        if (empty($payload['sections'])) {
            $this->command?->error('NHIA price list data file is empty or invalid.');

            return;
        }

        $sectionCount = 0;
        $serviceCount = 0;

        foreach ($payload['sections'] as $section) {
            $category = $this->upsertSectionCategory($section);
            $sectionCount++;

            foreach ($section['services'] as $svc) {
                $this->upsertService($section, $category->id, $svc);
                $serviceCount++;
            }
        }

        // Refresh the NHIS/NASHIA tariff columns on the handful of basic
        // demo services seeded with futh_emr_full_dump.sql, so their prices
        // match the April 2025 list where an obvious equivalent exists.
        $this->alignLegacyDemoServices();

        $this->command?->info(
            "NHIA Procedure Price List 2025 imported: {$serviceCount} services "
            . "across {$sectionCount} sections (nhia_rate + nashia_rate both set)."
        );
    }

    protected function upsertSectionCategory(array $section): ServiceCategory
    {
        return ServiceCategory::withTrashed()->updateOrCreate(
            ['code' => $section['category_code']],
            [
                'hospital_id' => $this->hospitalId,
                'parent_id'   => null,
                'name'        => 'NHIA 2025 — ' . $section['name'],
                'type'        => $section['category_type'],
                'description' => 'NHIA Procedure Price List 2025 (April 2025): ' . $section['name'],
                'is_active'   => true,
                'deleted_at'  => null,
            ]
        );
    }

    protected function upsertService(array $section, int $categoryId, array $svc): Service
    {
        $description = 'NHIA Procedure Price List 2025 (April 2025) — '
            . $section['name']
            . ($svc['subsection'] ? ' › ' . $svc['subsection'] : '')
            . '. Rate applies to NHIS (federal NHIA) and NASHIA (Nasarawa State) patients.';

        if (! empty($svc['synthesized_code'])) {
            $description .= ' Code synthesized — the printed list omits a code for this row.';
        }

        return Service::withTrashed()->updateOrCreate(
            ['code' => $svc['code']],
            [
                'hospital_id'   => $this->hospitalId,
                'service_category_id' => $categoryId,
                'name'          => $svc['name'],
                'nhia_code'     => $svc['nhia_code'],
                'category'      => $section['services_category'],
                'sub_category'  => $this->subCategoryFor($section, $svc),
                'standard_price' => $svc['price'],
                'nhia_rate'     => $svc['price'],   // NHIS (federal NHIA)
                'nashia_rate'   => $svc['price'],   // NASHIA (Nasarawa State)
                'private_rate'  => $svc['price'],   // default; edit later per service
                'cost_price'    => 0,
                'unit_of_measure' => $svc['unit_of_measure'],
                'is_taxable'    => false,
                'tax_percentage' => 0,
                'description'   => $description,
                'requires_pre_authorization' => (bool) $svc['requires_pre_authorization'],
                'allowed_roles' => null,           // any billing role may bill it
                'allows_walk_in' => false,
                'status'        => 'Active',
                'deleted_at'    => null,
            ]
        );
    }

    /**
     * Map a service onto the services.sub_category enum:
     *  - section defaults (Maternity, Dental, Optical, Mental Health, …)
     *  - Minor/Intermediate/Major procedure groups → Surgery
     *  - consultation lines in the General section → Specialist
     */
    protected function subCategoryFor(array $section, array $svc): string
    {
        $default = $section['default_sub_category'] ?? 'General';
        $subsection = strtolower((string) ($svc['subsection'] ?? ''));

        if ($default !== 'General') {
            // Section-specific default (Maternity / Dental / Optical / …)
            return $default;
        }

        if (str_contains($subsection, 'minor') || str_contains($subsection, 'intermediate')
            || str_contains($subsection, 'major') || str_contains($subsection, 'surger')
            || str_contains($subsection, 'mua') || str_contains($subsection, 'pop')) {
            return 'Surgery';
        }

        if ($section['prefix'] === '010' && str_contains(strtolower($svc['name']), 'consultation')) {
            return 'Specialist';
        }

        return 'General';
    }

    /**
     * The basic demo services from futh_emr_full_dump.sql carry pre-2025
     * prices. Align the obvious ones with the April 2025 list so the demo
     * data is consistent (matched by NHIA code).
     */
    protected function alignLegacyDemoServices(): void
    {
        $mapping = [
            // demo service code => [nhia_code, April-2025 price, sub_category]
            'MAT-NVD' => ['NHIA-030-002', 35000.00, 'Maternity'],   // Normal Delivery
            'MAT-CS'  => ['NHIA-033-004', 180000.00, 'Maternity'],  // Caesarean Section
            'CONS-SPC' => ['NHIA-010-001', 4000.00, 'Specialist'],  // Specialist Consultation
            'WARD-GEN' => ['NHIA-010-005', 2000.00, 'General'],     // Hospital Bed Occupancy
        ];

        foreach ($mapping as $code => [$nhiaCode, $price, $subCategory]) {
            Service::withTrashed()->where('code', $code)->update([
                'nhia_code'    => $nhiaCode,
                'standard_price' => $price,
                'nhia_rate'    => $price,
                'nashia_rate'  => $price,
                'private_rate' => $price,
                'sub_category' => $subCategory,
                'status'       => 'Active',
            ]);
        }
    }
}
