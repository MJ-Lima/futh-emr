<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * ORDER MATTERS:
     * 1. Permissions first (creates roles + permissions)
     * 2. FuthEmrSeeder second (creates data and assigns roles to users)
     */
    public function run(): void
    {
        // Step 1: Seed all permissions and role-permission mappings
        $this->call([
            FuthPermissionsSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('=== Permissions & Roles ready ===');

        // Step 2: Seed EMR data (hospitals, departments, clinics, users, etc.)
        $this->call([
            FuthEmrSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('=== EMR Data seeded ===');

        // Step 3: 4-lab architecture (DNA + Main with 7 units + Annex + Taimako)
        $this->call([
            LaboratoryAndLabUnitSeeder::class,
        ]);

        $this->command->info('=== 4-Lab architecture seeded (DNA / Main / Annex / Taimako) ===');

        // Step 4: Consultation rooms (per-clinic, drives auto-allocation after vitals)
        $this->call([
            ConsultationRoomSeeder::class,
        ]);

        $this->command->info('=== Consultation rooms seeded ===');

        // Step 5: Specialty clinics (ANC, SOPD, Dental, ENT, Paediatric, Eye, Psychiatry, Psychology)
        $this->call([
            SpecialtyClinicsSeeder::class,
        ]);

        $this->command->info('=== Specialty clinics seeded ===');

        // Step 6: Presenting complaints + ICD-10 diagnoses + clinic templates
        $this->call([
            PresentingComplaintsSeeder::class,
            Icd10DiagnosesSeeder::class,
            Icd10DiagnosesExtendedSeeder::class,
            ClinicTemplatesSeeder::class,
        ]);

        $this->command->info('=== Presenting complaints, ICD-10 diagnoses (1000+), clinic templates seeded ===');

        // Step 7: Large reference data from SQL dumps (ICD-10 74k, NACRS complaints,
        // service catalog 9k, Nigerian states & LGAs). Skipped gracefully if SQL files missing.
        $this->call([
            ReferenceDataSqlImporterSeeder::class,
        ]);

        $this->command->info('=== Large reference data (SQL dumps) imported ===');
        $this->command->info('Login: admin@futhlafia.gov.ng / Futh@2026');
    }
}
