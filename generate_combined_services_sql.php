#!/usr/bin/env php
<?php
/**
 * Generates a single, self-contained SQL file that:
 *   1. Disables FK checks
 *   2. Truncates services + service_categories
 *   3. Inserts all service_categories (46 rows) FIRST
 *   4. Inserts all services (9,306 rows) SECOND
 *   5. Re-enables FK checks
 *
 * The output file can be imported directly via phpMyAdmin's Import tab.
 *
 * Usage:
 *   php generate_combined_services_sql.php
 *
 * Output:
 *   database/seeders/sql/04_combined_services_import.sql
 */

$sqlDir = __DIR__ . '/database/seeders/sql';
$outputFile = $sqlDir . '/04_combined_services_import.sql';

// Read the source files
$servicesFile = $sqlDir . '/03_services.sql';
$servicesOnlyFile = $sqlDir . '/03_services_only.sql';

if (!file_exists($servicesFile)) {
    fwrite(STDERR, "ERROR: {$servicesFile} not found\n");
    exit(1);
}

echo "Reading {$servicesFile}...\n";
$servicesContent = file_get_contents($servicesFile);

// Extract INSERT statements
$categoriesInserts = extractInsertStatements($servicesContent, 'service_categories');
$servicesInserts = extractInsertStatements($servicesContent, 'services');

// If services not found in 03_services.sql, try 03_services_only.sql
if (empty($servicesInserts) && file_exists($servicesOnlyFile)) {
    echo "Services not found in 03_services.sql, trying 03_services_only.sql...\n";
    $servicesOnlyContent = file_get_contents($servicesOnlyFile);
    $servicesInserts = extractInsertStatements($servicesOnlyContent, 'services');
}

echo "Found " . count($categoriesInserts) . " INSERT statement(s) for service_categories\n";
echo "Found " . count($servicesInserts) . " INSERT statement(s) for services\n";

if (empty($categoriesInserts)) {
    fwrite(STDERR, "ERROR: No service_categories INSERT statements found!\n");
    exit(1);
}
if (empty($servicesInserts)) {
    fwrite(STDERR, "ERROR: No services INSERT statements found!\n");
    exit(1);
}

// Build the combined SQL file
echo "Generating combined SQL file...\n";

$output = "";
$output .= "-- ============================================================\n";
$output .= "-- 04_combined_services_import.sql\n";
$output .= "-- Self-contained import file for service_categories + services\n";
$output .= "-- Import order: service_categories FIRST, then services (FK constraint)\n";
$output .= "-- FK checks disabled for the entire import\n";
$output .= "--\n";
$output .= "-- Import via phpMyAdmin: Import tab -> Choose File -> Go\n";
$output .= "-- Or via MySQL CLI: mysql -u root futh_lafia_emr < 04_combined_services_import.sql\n";
$output .= "-- ============================================================\n";
$output .= "\n";
$output .= "-- Disable FK checks for the entire import session\n";
$output .= "SET FOREIGN_KEY_CHECKS = 0;\n";
$output .= "SET UNIQUE_CHECKS = 0;\n";
$output .= "SET SQL_MODE = '';\n";
$output .= "\n";
$output .= "-- Truncate both tables (services first, then categories — reverse FK order)\n";
$output .= "TRUNCATE TABLE `services`;\n";
$output .= "TRUNCATE TABLE `service_categories`;\n";
$output .= "\n";
$output .= "-- ============================================================\n";
$output .= "-- Step 1: Insert service_categories (46 rows)\n";
$output .= "-- ============================================================\n";
$output .= "\n";

foreach ($categoriesInserts as $sql) {
    $output .= $sql . "\n";
}

$output .= "\n";
$output .= "-- Verify: should be 46 rows\n";
$output .= "-- SELECT COUNT(*) FROM service_categories;\n";
$output .= "\n";
$output .= "-- ============================================================\n";
$output .= "-- Step 2: Insert services (9,306 rows)\n";
$output .= "-- ============================================================\n";
$output .= "\n";

foreach ($servicesInserts as $i => $sql) {
    $output .= "-- services chunk " . ($i + 1) . "/" . count($servicesInserts) . "\n";
    $output .= $sql . "\n";
    $output .= "\n";
}

$output .= "-- Verify: should be 9306 rows\n";
$output .= "-- SELECT COUNT(*) FROM services;\n";
$output .= "\n";
$output .= "-- Re-enable FK checks\n";
$output .= "SET FOREIGN_KEY_CHECKS = 1;\n";
$output .= "SET UNIQUE_CHECKS = 1;\n";
$output .= "\n";
$output .= "-- Done!\n";

file_put_contents($outputFile, $output);

$fileSize = number_format(filesize($outputFile));
echo "\n✓ Generated: {$outputFile}\n";
echo "  File size: {$fileSize} bytes\n";
echo "  Categories INSERTs: " . count($categoriesInserts) . "\n";
echo "  Services INSERTs: " . count($servicesInserts) . "\n";
echo "\nImport this file via phpMyAdmin's Import tab.\n";

// ============================================================
// Helper functions (strpos-based, no regex backtrack limit)
// ============================================================

function extractInsertStatements(string $content, string $tableName): array
{
    $inserts = [];
    $offset = 0;

    while (($pos = stripos($content, 'INSERT', $offset)) !== false) {
        $rest = substr($content, $pos, 200);
        $headerPattern = '/^INSERT\s+(?:IGNORE\s+)?INTO\s+`' . preg_quote($tableName, '/') . '`/i';

        if (!preg_match($headerPattern, $rest)) {
            $offset = $pos + 6;
            continue;
        }

        $endPos = findStatementEnd($content, $pos);

        if ($endPos === false) {
            $offset = $pos + 6;
            continue;
        }

        $inserts[] = substr($content, $pos, $endPos - $pos + 1);
        $offset = $endPos + 1;
    }

    return $inserts;
}

function findStatementEnd(string $content, int $start): int|false
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
                if ($i + 1 < $len && $content[$i + 1] === $stringChar) {
                    $i++;
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
