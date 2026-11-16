<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Robust reference data importer that uses strpos() (not regex) to extract
 * INSERT statements from SQL dump files.
 *
 * The previous version used preg_match_all() which silently failed on large
 * files (~2MB) due to PCRE's backtrack_limit. This version uses simple string
 * searching which has no such limit.
 *
 * Usage:
 *   php artisan db:seed --class=RobustServicesImporter --force
 */
class RobustServicesImporter extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== Robust Services Importer (v2 — strpos-based) ===');
        $this->command->newLine();

        // Disable FK checks at the DB level (not via SQL string in the dump)
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('SET UNIQUE_CHECKS = 0');
        DB::statement('SET SQL_MODE = ""');

        try {
            $this->importServiceCategories();
            $this->importServices();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            DB::statement('SET UNIQUE_CHECKS = 1');
        }

        $this->command->newLine();
        $this->command->info('=== Import complete ===');
        $this->command->info('Total services: ' . DB::table('services')->count());
        $this->command->info('Total service_categories: ' . DB::table('service_categories')->count());
    }

    /**
     * Import service_categories from 03_services.sql.
     * MUST run before services import (FK constraint).
     */
    private function importServiceCategories(): void
    {
        if (!Schema::hasTable('service_categories')) {
            $this->command->error('service_categories table does not exist!');
            return;
        }

        $file = database_path('seeders/sql/03_services.sql');

        if (!file_exists($file)) {
            $this->command->warn('Skipping service_categories: 03_services.sql not found at ' . $file);
            return;
        }

        $this->command->info('Step 1: Importing service_categories...');

        $content = file_get_contents($file);
        $inserts = $this->extractInsertStatements($content, 'service_categories');

        $this->command->info('  Found ' . count($inserts) . ' INSERT statement(s)');

        if (empty($inserts)) {
            $this->command->error('  No INSERT statements found for service_categories!');
            return;
        }

        // Truncate for clean import
        DB::table('service_categories')->truncate();
        $this->command->info('  Truncated service_categories table');

        $totalRows = 0;
        $errors = 0;

        foreach ($inserts as $i => $sql) {
            try {
                DB::statement($sql);
                $rowCount = $this->countRows($sql);
                $totalRows += $rowCount;
                $this->command->info("  INSERT #" . ($i + 1) . ": OK ({$rowCount} rows)");
            } catch (\Throwable $e) {
                $errors++;
                $this->command->error("  INSERT #" . ($i + 1) . " failed: " . $e->getMessage());
                $this->command->error("  SQL preview: " . substr($sql, 0, 200) . '...');
            }
        }

        $count = DB::table('service_categories')->count();
        $this->command->info("  ✓ service_categories: {$count} rows imported (errors: {$errors})");
    }

    /**
     * Import services from 03_services.sql or 03_services_only.sql.
     * Requires service_categories to be imported first (FK constraint).
     */
    private function importServices(): void
    {
        if (!Schema::hasTable('services')) {
            $this->command->error('services table does not exist!');
            return;
        }

        // Verify service_categories has data (FK requirement)
        $catCount = DB::table('service_categories')->count();
        if ($catCount === 0) {
            $this->command->error('Cannot import services: service_categories table is empty!');
            $this->command->error('Services have a FK constraint on service_category_id.');
            $this->command->error('Import service_categories first.');
            return;
        }
        $this->command->newLine();
        $this->command->info("Step 2: Importing services (service_categories has {$catCount} rows)...");

        $files = ['03_services.sql', '03_services_only.sql'];

        // Truncate for clean import
        DB::table('services')->truncate();
        $this->command->info('  Truncated services table');

        foreach ($files as $filename) {
            $file = database_path('seeders/sql/' . $filename);

            if (!file_exists($file)) {
                $this->command->warn("  File not found: {$filename}");
                continue;
            }

            $this->command->info("  Trying {$filename}...");

            $content = file_get_contents($file);
            $inserts = $this->extractInsertStatements($content, 'services');

            if (empty($inserts)) {
                $this->command->warn("  No INSERT statements found for `services` in {$filename}");
                continue;
            }

            $this->command->info("  Found " . count($inserts) . " INSERT statement(s)");

            $totalRows = 0;
            $errors = 0;
            $firstError = null;

            foreach ($inserts as $i => $sql) {
                try {
                    DB::statement($sql);
                    $rowCount = $this->countRows($sql);
                    $totalRows += $rowCount;
                } catch (\Throwable $e) {
                    $errors++;
                    if ($firstError === null) {
                        $firstError = $e->getMessage();
                        $this->command->error("  INSERT #" . ($i + 1) . " failed: " . $e->getMessage());
                        $this->command->error("  SQL preview: " . substr($sql, 0, 250) . '...');
                    }
                    if ($errors <= 3) {
                        $this->command->warn("  INSERT #" . ($i + 1) . " also failed");
                    }
                }
            }

            $count = DB::table('services')->count();

            if ($count > 0) {
                $this->command->info("  ✓ {$filename}: {$count} rows imported (errors: {$errors})");
                return; // Success!
            } else {
                $this->command->warn("  ✗ {$filename}: 0 rows imported, errors: {$errors}");
                if ($firstError) {
                    $this->command->warn("  First error: {$firstError}");
                }
            }
        }

        $this->command->error('  ✗ All SQL files failed to import services');
    }

    /**
     * Extract INSERT statements for a specific table using strpos().
     *
     * This avoids PCRE's backtrack_limit which silently fails on large files.
     * Finds each "INSERT" keyword, checks if it's for the target table,
     * then finds the end of the statement (semicolon outside string literals).
     */
    private function extractInsertStatements(string $content, string $tableName): array
    {
        $inserts = [];
        $offset = 0;
        $len = strlen($content);

        // We search for "INSERT" occurrences
        while (($pos = stripos($content, 'INSERT', $offset)) !== false) {
            // Check if this is "INSERT INTO `tablename`" or "INSERT IGNORE INTO `tablename`"
            $rest = substr($content, $pos, 200); // First 200 chars should be enough for the header
            $headerPattern = '/^INSERT\s+(?:IGNORE\s+)?INTO\s+`' . preg_quote($tableName, '/') . '`/i';

            if (!preg_match($headerPattern, $rest)) {
                $offset = $pos + 6; // Move past this "INSERT"
                continue;
            }

            // Find the end of this statement (next semicolon outside string literals)
            $endPos = $this->findStatementEnd($content, $pos);

            if ($endPos === false) {
                $offset = $pos + 6;
                continue;
            }

            $fullInsert = substr($content, $pos, $endPos - $pos + 1); // Include the semicolon
            $inserts[] = $fullInsert;

            $offset = $endPos + 1;
        }

        return $inserts;
    }

    /**
     * Find the position of the semicolon that ends the SQL statement starting at $start,
     * skipping over semicolons inside string literals.
     */
    private function findStatementEnd(string $content, int $start): int|false
    {
        $len = strlen($content);
        $inString = false;
        $stringChar = '';
        $escaped = false;

        for ($i = $start; $i < $len; $i++) {
            $char = $content[$i];

            if ($escaped) {
                $escaped = false;
                continue;
            }

            if ($char === '\\') {
                $escaped = true;
                continue;
            }

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
                if ($char === "'" || $char === '"') {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($char === ';') {
                    return $i;
                }
            }
        }

        return false;
    }

    /**
     * Count the number of value tuples in an INSERT statement.
     */
    private function countRows(string $sql): int
    {
        // Count occurrences of "),(" or "),\n  (" etc.
        // Simple approximation: count top-level opening parens after VALUES
        $valuesPos = stripos($sql, 'VALUES');
        if ($valuesPos === false) return 0;

        $valuesPart = substr($sql, $valuesPos);
        // Count opening parens that start a new tuple (preceded by , or whitespace or VALUES)
        return preg_match_all('/\(|,\s*\(/', $valuesPart) > 0
            ? substr_count($valuesPart, '(') - substr_count($valuesPart, '()')
            : 0;
    }
}
