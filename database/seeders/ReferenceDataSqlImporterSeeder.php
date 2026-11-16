<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Imports reference data from external SQL dump files:
 *   - ICD-10 diagnosis codes (~74,000 rows)
 *   - NACRS Nigeria presenting complaints (~332 rows)
 *   - Service catalog (~9,306 services + 46 categories)
 *   - Nigerian States & LGAs (alternative to NigeriaGeographySeeder)
 *
 * Each import is wrapped in a try/catch and uses INSERT IGNORE / CREATE TABLE IF NOT EXISTS
 * so the seeder is idempotent — safe to run multiple times.
 *
 * Usage:
 *   php artisan db:seed --class=ReferenceDataSqlImporterSeeder --force
 *
 * Or as part of a full refresh:
 *   php artisan migrate:fresh --seed --force
 */
class ReferenceDataSqlImporterSeeder extends Seeder
{
    /**
     * Folder containing the source SQL files.
     * Default location: database/seeders/sql/
     */
    private const SQL_DIR = __DIR__ . '/sql';

    public function run(): void
    {
        $this->command->info('=== Reference Data SQL Importer ===');

        // Disable FK checks during import for safety
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            $this->importIcd10Codes();
            $this->importPresentingComplaints();
            $this->importServices();
            $this->importStatesAndLgas();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->command->info('=== Reference data import complete ===');
    }

    /**
     * ICD-10 diagnosis codes (~74,000 rows).
     * Creates the `icd10_codes` table if it doesn't exist.
     */
    private function importIcd10Codes(): void
    {
        $file = self::SQL_DIR . '/01_icd10_codes_fixed.sql';

        if (!file_exists($file)) {
            $this->command->warn("Skipped ICD-10 import: file not found at {$file}");
            return;
        }

        $this->command->info('Importing ICD-10 codes (this may take 1-2 minutes)...');

        try {
            // Truncate existing data to make the import idempotent
            if (Schema::hasTable('icd10_codes')) {
                DB::table('icd10_codes')->truncate();
                $this->command->info('  - Truncated existing icd10_codes table');
            }

            $sql = file_get_contents($file);
            DB::unprepared($sql);

            $count = DB::table('icd10_codes')->count();
            $this->command->info("  ✓ Imported {$count} ICD-10 codes");
        } catch (\Throwable $e) {
            $this->command->error("  ✗ ICD-10 import failed: " . $e->getMessage());
        }
    }

    /**
     * NACRS Nigeria presenting complaints (~332 rows).
     * Creates the `presenting_complaints` table if it doesn't exist.
     */
    private function importPresentingComplaints(): void
    {
        $file = self::SQL_DIR . '/02_presenting_complaints.sql';

        if (!file_exists($file)) {
            $this->command->warn("Skipped presenting complaints import: file not found at {$file}");
            return;
        }

        $this->command->info('Importing presenting complaints...');

        try {
            if (Schema::hasTable('presenting_complaints')) {
                DB::table('presenting_complaints')->truncate();
                $this->command->info('  - Truncated existing presenting_complaints table');
            }

            $sql = file_get_contents($file);
            DB::unprepared($sql);

            $count = DB::table('presenting_complaints')->count();
            $this->command->info("  ✓ Imported {$count} presenting complaints");
        } catch (\Throwable $e) {
            $this->command->error("  ✗ Presenting complaints import failed: " . $e->getMessage());
        }
    }

    /**
     * Service catalog (~9,306 services + 46 categories).
     * Inserts into existing `service_categories` and `services` tables.
     *
     * Uses a robust statement-by-statement import (not DB::unprepared on the
     * whole file) so that errors are reported clearly instead of swallowed.
     */
    private function importServices(): void
    {
        if (!Schema::hasTable('services')) {
            $this->command->warn('Skipped services import: services table does not exist yet');
            return;
        }

        $beforeCount = DB::table('services')->count();
        $this->command->info("Importing service catalog (current rows: {$beforeCount})...");

        // Disable FK checks and strict mode at the DB level
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('SET UNIQUE_CHECKS = 0');
        DB::statement('SET SQL_MODE = ""');

        try {
            $this->importServicesCategories();
            $this->importServicesRows();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            DB::statement('SET UNIQUE_CHECKS = 1');
        }

        $svcCount = DB::table('services')->count();
        $catCount = Schema::hasTable('service_categories') ? DB::table('service_categories')->count() : 0;
        $this->command->info("  ✓ Services table now has {$svcCount} rows");
        $this->command->info("  ✓ Service categories table now has {$catCount} rows");
    }

    /**
     * Import service_categories from 03_services.sql.
     */
    private function importServicesCategories(): void
    {
        if (!Schema::hasTable('service_categories')) {
            return;
        }

        $file = self::SQL_DIR . '/03_services.sql';
        if (!file_exists($file)) {
            return;
        }

        $content = file_get_contents($file);
        $inserts = $this->extractInsertStatements($content, 'service_categories');

        if (empty($inserts)) {
            return;
        }

        DB::table('service_categories')->truncate();

        foreach ($inserts as $sql) {
            try {
                DB::statement($sql);
            } catch (\Throwable $e) {
                $this->command->warn("  service_categories INSERT failed: " . $e->getMessage());
            }
        }
    }

    /**
     * Import services rows from 03_services.sql or 03_services_only.sql.
     */
    private function importServicesRows(): void
    {
        $files = ['03_services.sql', '03_services_only.sql'];

        DB::table('services')->truncate();

        foreach ($files as $filename) {
            $file = self::SQL_DIR . '/' . $filename;
            if (!file_exists($file)) {
                continue;
            }

            $content = file_get_contents($file);
            $inserts = $this->extractInsertStatements($content, 'services');

            if (empty($inserts)) {
                $this->command->warn("  ! {$filename} contains no INSERT statements for `services` — skipping");
                continue;
            }

            $this->command->info("  Trying {$filename} (" . count($inserts) . " INSERT statements)...");

            $errors = 0;
            $firstError = null;
            $insertsSucceeded = 0;

            foreach ($inserts as $i => $sql) {
                try {
                    DB::statement($sql);
                    $insertsSucceeded++;
                } catch (\Throwable $e) {
                    $errors++;
                    if ($firstError === null) {
                        $firstError = $e->getMessage();
                        $this->command->error("  INSERT #" . ($i + 1) . " failed: " . $e->getMessage());
                        $this->command->error("  SQL preview: " . substr($sql, 0, 250) . '...');
                    }
                }
            }

            $count = DB::table('services')->count();
            if ($count > 0) {
                $this->command->info("  ✓ {$filename}: {$insertsSucceeded}/" . count($inserts) . " INSERTs succeeded, {$count} rows in table (errors: {$errors})");
                return; // Success
            } else {
                $this->command->warn("  ✗ {$filename}: 0 rows imported, errors: {$errors}");
                if ($firstError) {
                    $this->command->warn("  First error: {$firstError}");
                }
            }
        }

        $this->command->error("  ✗ All services SQL files failed");
    }

    /**
     * Extract INSERT statements for a specific table using strpos().
     *
     * This avoids PCRE's backtrack_limit which silently fails on large files
     * (~2MB) and returns 0 matches. Uses simple string searching instead.
     */
    private function extractInsertStatements(string $content, string $tableName): array
    {
        $inserts = [];
        $offset = 0;

        while (($pos = stripos($content, 'INSERT', $offset)) !== false) {
            // Check if this INSERT is for our target table
            $rest = substr($content, $pos, 200);
            $headerPattern = '/^INSERT\s+(?:IGNORE\s+)?INTO\s+`' . preg_quote($tableName, '/') . '`/i';

            if (!preg_match($headerPattern, $rest)) {
                $offset = $pos + 6;
                continue;
            }

            // Find the end of this statement (next semicolon outside string literals)
            $endPos = $this->findStatementEnd($content, $pos);

            if ($endPos === false) {
                $offset = $pos + 6;
                continue;
            }

            $inserts[] = substr($content, $pos, $endPos - $pos + 1);
            $offset = $endPos + 1;
        }

        return $inserts;
    }

    /**
     * Find the position of the semicolon that ends the SQL statement starting at $start,
     * skipping over semicolons inside string literals.
     * Also handles SQL's doubled-quote escape ('' or "").
     */
    private function findStatementEnd(string $content, int $start): int|false
    {
        $len = strlen($content);
        $inString = false;
        $stringChar = '';
        $escaped = false;

        for ($i = $start; $i < $len; $i++) {
            $char = $content[$i];

            if ($escaped) { $escaped = false; continue; }
            if ($char === '\\') { $escaped = true; continue; }

            if ($inString) {
                if ($char === $stringChar) {
                    // Check for doubled quote (SQL escape: '' or "")
                    if ($i + 1 < $len && $content[$i + 1] === $stringChar) {
                        $i++; // Skip the doubled quote
                        continue;
                    }
                    $inString = false;
                    $stringChar = '';
                }
            } else {
                if ($char === "'" || $char === '"') { $inString = true; $stringChar = $char; }
                elseif ($char === ';') { return $i; }
            }
        }

        return false;
    }

    /**
     * Nigerian States & LGAs.
     * Creates `states_tb` and `lgas` tables (separate from the app's `states`/`locals` tables).
     * These are reference tables for address verification.
     */
    private function importStatesAndLgas(): void
    {
        $file = self::SQL_DIR . '/States and LGAs.sql';

        if (!file_exists($file)) {
            $this->command->warn("Skipped States & LGAs import: file not found at {$file}");
            return;
        }

        $this->command->info('Importing States & LGAs reference data...');

        try {
            $sql = file_get_contents($file);
            DB::unprepared($sql);

            $stateCount = DB::table('states_tb')->count();
            $lgaCount = DB::table('lgas')->count();
            $this->command->info("  ✓ Imported {$stateCount} states and {$lgaCount} LGAs");
        } catch (\Throwable $e) {
            $this->command->error("  ✗ States & LGAs import failed: " . $e->getMessage());
        }
    }
}
