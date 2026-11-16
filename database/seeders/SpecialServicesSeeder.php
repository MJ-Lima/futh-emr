<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

/**
 * Seeds the special non-clinical services requested for FUTH Lafia:
 *
 *   - Internship placements   — 3, 6 and 9 months
 *   - Research / data access  — Diploma, BSc, MSc and PhD students
 *   - Gas plant sales         — oxygen bought by outsiders AND patients
 *   - Application forms
 *   - ID cards                — Staff, NHIS and NASHIA
 *
 * Every service is walk-in billable (buyers are usually not registered
 * patients) and restricted to the roles that operate the corresponding
 * desk via `allowed_roles`.
 *
 * !!! IMPORTANT — PLACEHOLDER PRICES !!!
 * Where no official hospital tariff exists, a clearly-flagged default
 * price has been set (grounded on the hospital's legacy tariff where an
 * equivalent existed: application form ₦1,000; staff ID card ₦2,000;
 * ethical clearance ₦3,500). Review and update every price via
 * Billing & Finance → Services before going live.
 *
 * Safe to re-run: rows are upserted by their unique `code`.
 */
class SpecialServicesSeeder extends Seeder
{
    protected int $hospitalId = 1;

    public function run(): void
    {
        $categories = $this->seedCategories();
        $services   = $this->serviceDefinitions($categories);

        foreach ($services as $svc) {
            Service::withTrashed()->updateOrCreate(
                ['code' => $svc['code']],
                array_merge($svc['attributes'], ['deleted_at' => null])
            );
        }

        $this->command?->info(
            'Special services seeded: ' . count($services) . ' services across '
            . count($categories) . ' categories (internship, research, gas '
            . 'plant, application forms, ID cards). Remember to review the '
            . 'placeholder prices under Billing & Finance → Services.'
        );
    }

    protected function seedCategories(): array
    {
        $definitions = [
            ['code' => 'SPEC-INT', 'name' => 'Internship & Training',        'type' => 'Other',      'color' => '#0d6efd', 'sort' => 90],
            ['code' => 'SPEC-RES', 'name' => 'Research & Data Collection',   'type' => 'Other',      'color' => '#6f42c1', 'sort' => 91],
            ['code' => 'SPEC-GAS', 'name' => 'Gas Plant',                    'type' => 'Consumable', 'color' => '#fd7e14', 'sort' => 92],
            ['code' => 'SPEC-IDC', 'name' => 'ID Cards',                     'type' => 'Other',      'color' => '#198754', 'sort' => 93],
            ['code' => 'SPEC-APP', 'name' => 'Application Forms',            'type' => 'Other',      'color' => '#6c757d', 'sort' => 94],
        ];

        $ids = [];
        foreach ($definitions as $def) {
            $category = ServiceCategory::withTrashed()->updateOrCreate(
                ['code' => $def['code']],
                [
                    'hospital_id' => $this->hospitalId,
                    'parent_id'   => null,
                    'name'        => $def['name'],
                    'type'        => $def['type'],
                    'color'       => $def['color'],
                    'description' => $def['name'] . ' — special (non-clinical) service desk',
                    'is_active'   => true,
                    'sort_order'  => $def['sort'],
                    'deleted_at'  => null,
                ]
            );
            $ids[$def['code']] = $category->id;
        }

        return $ids;
    }

    protected function serviceDefinitions(array $categories): array
    {
        $internshipRoles = ['super_admin', 'medical_administrator', 'accountant', 'biller'];
        $researchRoles   = ['super_admin', 'medical_administrator', 'accountant', 'biller'];
        $gasPlantRoles   = ['super_admin', 'accountant', 'biller', 'cashier'];
        $idCardRoles     = ['super_admin', 'medical_records_officer', 'insurance_officer', 'accountant', 'biller', 'head_of_records'];
        $formRoles       = ['super_admin', 'medical_records_officer', 'accountant', 'biller', 'head_of_records'];

        $placeholder = 'PLACEHOLDER PRICE — review and update via Billing & Finance → Services. ';

        return [
            // ---------------------------------------------------------
            // Internship placements (3 / 6 / 9 months)
            // ---------------------------------------------------------
            [
                'code' => 'SPEC-INT-003',
                'attributes' => [
                    'hospital_id' => $this->hospitalId, 'service_category_id' => $categories['SPEC-INT'],
                    'name' => 'Internship Fee — 3 Months Placement', 'category' => 'Other', 'sub_category' => 'General',
                    'standard_price' => 10000, 'nhia_rate' => 10000, 'nashia_rate' => 10000, 'private_rate' => 10000,
                    'unit_of_measure' => 'placement', 'allowed_roles' => $internshipRoles, 'allows_walk_in' => true,
                    'description' => $placeholder . 'Internship / training placement fee for 3 months. Added by the internship desk; payment collected at the cashier point.',
                    'status' => 'Active',
                ],
            ],
            [
                'code' => 'SPEC-INT-006',
                'attributes' => [
                    'hospital_id' => $this->hospitalId, 'service_category_id' => $categories['SPEC-INT'],
                    'name' => 'Internship Fee — 6 Months Placement', 'category' => 'Other', 'sub_category' => 'General',
                    'standard_price' => 20000, 'nhia_rate' => 20000, 'nashia_rate' => 20000, 'private_rate' => 20000,
                    'unit_of_measure' => 'placement', 'allowed_roles' => $internshipRoles, 'allows_walk_in' => true,
                    'description' => $placeholder . 'Internship / training placement fee for 6 months. Added by the internship desk; payment collected at the cashier point.',
                    'status' => 'Active',
                ],
            ],
            [
                'code' => 'SPEC-INT-009',
                'attributes' => [
                    'hospital_id' => $this->hospitalId, 'service_category_id' => $categories['SPEC-INT'],
                    'name' => 'Internship Fee — 9 Months Placement', 'category' => 'Other', 'sub_category' => 'General',
                    'standard_price' => 30000, 'nhia_rate' => 30000, 'nashia_rate' => 30000, 'private_rate' => 30000,
                    'unit_of_measure' => 'placement', 'allowed_roles' => $internshipRoles, 'allows_walk_in' => true,
                    'description' => $placeholder . 'Internship / training placement fee for 9 months. Added by the internship desk; payment collected at the cashier point.',
                    'status' => 'Active',
                ],
            ],

            // ---------------------------------------------------------
            // Research / data collection (Diploma, BSc, MSc, PhD)
            // ---------------------------------------------------------
            [
                'code' => 'SPEC-RES-DIP',
                'attributes' => [
                    'hospital_id' => $this->hospitalId, 'service_category_id' => $categories['SPEC-RES'],
                    'name' => 'Research / Data Collection Fee — Diploma Student', 'category' => 'Other', 'sub_category' => 'General',
                    'standard_price' => 3500, 'nhia_rate' => 3500, 'nashia_rate' => 3500, 'private_rate' => 3500,
                    'unit_of_measure' => 'study', 'allowed_roles' => $researchRoles, 'allows_walk_in' => true,
                    'description' => $placeholder . 'Research / data collection access fee for Diploma students (default aligned with the legacy ethical-clearance tariff of ₦3,500).',
                    'status' => 'Active',
                ],
            ],
            [
                'code' => 'SPEC-RES-BSC',
                'attributes' => [
                    'hospital_id' => $this->hospitalId, 'service_category_id' => $categories['SPEC-RES'],
                    'name' => 'Research / Data Collection Fee — BSc Student', 'category' => 'Other', 'sub_category' => 'General',
                    'standard_price' => 3500, 'nhia_rate' => 3500, 'nashia_rate' => 3500, 'private_rate' => 3500,
                    'unit_of_measure' => 'study', 'allowed_roles' => $researchRoles, 'allows_walk_in' => true,
                    'description' => $placeholder . 'Research / data collection access fee for undergraduate (BSc) students (default aligned with the legacy ethical-clearance tariff of ₦3,500).',
                    'status' => 'Active',
                ],
            ],
            [
                'code' => 'SPEC-RES-MSC',
                'attributes' => [
                    'hospital_id' => $this->hospitalId, 'service_category_id' => $categories['SPEC-RES'],
                    'name' => 'Research / Data Collection Fee — MSc Student', 'category' => 'Other', 'sub_category' => 'General',
                    'standard_price' => 5000, 'nhia_rate' => 5000, 'nashia_rate' => 5000, 'private_rate' => 5000,
                    'unit_of_measure' => 'study', 'allowed_roles' => $researchRoles, 'allows_walk_in' => true,
                    'description' => $placeholder . 'Research / data collection access fee for postgraduate (MSc) students.',
                    'status' => 'Active',
                ],
            ],
            [
                'code' => 'SPEC-RES-PHD',
                'attributes' => [
                    'hospital_id' => $this->hospitalId, 'service_category_id' => $categories['SPEC-RES'],
                    'name' => 'Research / Data Collection Fee — PhD Student', 'category' => 'Other', 'sub_category' => 'General',
                    'standard_price' => 7500, 'nhia_rate' => 7500, 'nashia_rate' => 7500, 'private_rate' => 7500,
                    'unit_of_measure' => 'study', 'allowed_roles' => $researchRoles, 'allows_walk_in' => true,
                    'description' => $placeholder . 'Research / data collection access fee for doctoral (PhD) students.',
                    'status' => 'Active',
                ],
            ],

            // ---------------------------------------------------------
            // Gas plant — oxygen sold to outsiders and patients
            // ---------------------------------------------------------
            [
                'code' => 'SPEC-GAS-001',
                'attributes' => [
                    'hospital_id' => $this->hospitalId, 'service_category_id' => $categories['SPEC-GAS'],
                    'name' => 'Gas Plant — Medical Oxygen (per cylinder)', 'category' => 'Consumable', 'sub_category' => 'General',
                    'standard_price' => 8000, 'nhia_rate' => 8000, 'nashia_rate' => 8000, 'private_rate' => 8000,
                    'unit_of_measure' => 'cylinder', 'allowed_roles' => $gasPlantRoles, 'allows_walk_in' => true,
                    'description' => $placeholder . 'Medical oxygen sold from the hospital gas plant. Bought by external (walk-in) customers AND registered patients (default aligned with the legacy oxygen tariff of ₦8,000). Add more cylinder sizes/prices via Services if needed.',
                    'status' => 'Active',
                ],
            ],

            // ---------------------------------------------------------
            // Application form
            // ---------------------------------------------------------
            [
                'code' => 'SPEC-APP-001',
                'attributes' => [
                    'hospital_id' => $this->hospitalId, 'service_category_id' => $categories['SPEC-APP'],
                    'name' => 'Application Form', 'category' => 'Other', 'sub_category' => 'General',
                    'standard_price' => 1000, 'nhia_rate' => 1000, 'nashia_rate' => 1000, 'private_rate' => 1000,
                    'unit_of_measure' => 'form', 'allowed_roles' => $formRoles, 'allows_walk_in' => true,
                    'description' => 'Application form fee (default matches the legacy hospital tariff of ₦1,000). Added at the records/front desk; payment collected at the cashier point.',
                    'status' => 'Active',
                ],
            ],

            // ---------------------------------------------------------
            // ID cards — Staff, NHIS, NASHIA
            // ---------------------------------------------------------
            [
                'code' => 'SPEC-IDC-STAFF',
                'attributes' => [
                    'hospital_id' => $this->hospitalId, 'service_category_id' => $categories['SPEC-IDC'],
                    'name' => 'ID Card — Staff', 'category' => 'Other', 'sub_category' => 'General',
                    'standard_price' => 2000, 'nhia_rate' => 2000, 'nashia_rate' => 2000, 'private_rate' => 2000,
                    'unit_of_measure' => 'card', 'allowed_roles' => $idCardRoles, 'allows_walk_in' => true,
                    'description' => 'Staff identity card fee (default matches the legacy hospital tariff of ₦2,000).',
                    'status' => 'Active',
                ],
            ],
            [
                'code' => 'SPEC-IDC-NHIS',
                'attributes' => [
                    'hospital_id' => $this->hospitalId, 'service_category_id' => $categories['SPEC-IDC'],
                    'name' => 'ID Card — NHIS Enrollee', 'category' => 'Other', 'sub_category' => 'General',
                    'standard_price' => 2000, 'nhia_rate' => 2000, 'nashia_rate' => 2000, 'private_rate' => 2000,
                    'unit_of_measure' => 'card', 'allowed_roles' => $idCardRoles, 'allows_walk_in' => true,
                    'description' => 'NHIS (federal NHIA) enrollee identity card fee (default aligned with the legacy staff ID card tariff of ₦2,000).',
                    'status' => 'Active',
                ],
            ],
            [
                'code' => 'SPEC-IDC-NASHIA',
                'attributes' => [
                    'hospital_id' => $this->hospitalId, 'service_category_id' => $categories['SPEC-IDC'],
                    'name' => 'ID Card — NASHIA Enrollee', 'category' => 'Other', 'sub_category' => 'General',
                    'standard_price' => 2000, 'nhia_rate' => 2000, 'nashia_rate' => 2000, 'private_rate' => 2000,
                    'unit_of_measure' => 'card', 'allowed_roles' => $idCardRoles, 'allows_walk_in' => true,
                    'description' => 'NASHIA (Nasarawa State Health Insurance Agency) enrollee identity card fee (default aligned with the legacy staff ID card tariff of ₦2,000).',
                    'status' => 'Active',
                ],
            ],
        ];
    }
}
