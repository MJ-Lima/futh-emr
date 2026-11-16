<?php
// convert_icd10_sql.php — transforms 01_icd10_codes_fixed.sql to match actual schema
// Run from project root: php convert_icd10_sql.php

 $src = __DIR__ . '/database/seeders/sql/01_icd10_codes_fixed.sql';
 $dst = __DIR__ . '/database/seeders/sql/01_icd10_diagnoses_fixed.sql';

if (!file_exists($src)) {
    die("Source file not found: $src\n");
}

 $content = file_get_contents($src);

// 1. Find the CREATE TABLE block and replace it with our actual schema
//    Find the position of the first INSERT statement
 $insertPos = stripos($content, 'INSERT INTO');
if ($insertPos === false) {
    die("No INSERT statements found in source file.\n");
}

// Extract everything before the first INSERT (CREATE TABLE, locks, etc.)
 $header = substr($content, 0, $insertPos);

// Build a new header that matches the actual schema
 $newHeader = <<<'SQL'
-- Converted ICD-10 data for icd10_diagnoses table (actual schema)
SET FOREIGN_KEY_CHECKS=0;
SET UNIQUE_CHECKS=0;
SET SQL_MODE='';
SET AUTOCOMMIT=0;
START TRANSACTION;

SQL;

// 2. Replace table name (already done by previous step, but ensure it's done)
 $body = substr($content, $insertPos);
 $body = str_replace('icd10_codes', 'icd10_diagnoses', $body);

// 3. Replace INSERT column lists to match actual schema:
//    Old: (code, short_description, long_description, is_nf_excluded, is_active, created_at, updated_at)
//    New: (code, description, category, is_active, sort_order, created_at, updated_at)
//
//    Strategy: Map short_description -> description, drop long_description, drop is_nf_excluded

// Match INSERT statements with their column lists
 $pattern = '/INSERT INTO `icd10_diagnoses` \(`code`, `short_description`, `long_description`, `is_nf_excluded`, `is_active`, `created_at`, `updated_at`\) VALUES\s*/';
 $replacement = 'INSERT INTO `icd10_diagnoses` (`code`, `description`, `category`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES ';

 $body = preg_replace($pattern, $replacement, $body);

// 4. Now transform each VALUES tuple from:
//    ('CODE','short desc','long desc',0,1,'ts','ts')
//    to:
//    ('CODE','short desc',NULL,1,0,'ts','ts')
//
// We need to parse each tuple carefully because values contain commas and quotes.

// Find all VALUES (...) blocks
 $tuples = [];
preg_match_all('/\(([^)]+(?:\)[^)]*)*)\)(,|;)/', $body, $matches, PREG_OFFSET_CAPTURE);

if (empty($matches[0])) {
    die("Could not find any VALUES tuples to transform.\n");
}

 $out = substr($body, 0, $matches[0][0][1]); // text before first tuple
foreach ($matches[0] as $i => $fullMatch) {
    $tupleStr = $matches[1][$i][0];
    $sep = $matches[2][$i][0];

    // Parse the tuple: split on commas, respecting quoted strings
    $parts = parseTuple($tupleStr);

    if (count($parts) !== 7) {
        // Can't transform this tuple safely — keep as is
        $out .= '(' . $tupleStr . ')' . $sep;
        continue;
    }

    list($code, $shortDesc, $longDesc, $isNfExcluded, $isActive, $createdAt, $updatedAt) = $parts;

    // Map: code, description=shortDesc, category=NULL, isActive, sort_order=0, created_at, updated_at
    $newTuple = sprintf(
        '(%s,%s,NULL,%s,0,%s,%s)',
        $code,
        $shortDesc,
        $isActive,
        $createdAt,
        $updatedAt
    );

    $out .= $newTuple . $sep;
}

// Add the footer (FK constraint, unlock, etc.)
 $footer = <<<'SQL'

SET FOREIGN_KEY_CHECKS=1;
SET UNIQUE_CHECKS=1;
SET AUTOCOMMIT=1;
COMMIT;

SQL;

// Try to preserve the original ALTER TABLE if present
 $alterPos = strripos($body, 'ALTER TABLE');
if ($alterPos !== false) {
    $alterBlock = substr($body, $alterPos);
    // Fix the table reference in the ALTER too
    $alterBlock = str_replace('icd10_codes', 'icd10_diagnoses', $alterBlock);
    $footer .= $alterBlock;
}

file_put_contents($dst, $newHeader . $out . "\n" . $footer);

echo "Converted SQL written to: $dst\n";
echo "Original size: " . filesize($src) . " bytes\n";
echo "New size: " . filesize($dst) . " bytes\n";

/**
 * Parse a SQL VALUES tuple into its component parts.
 * Handles quoted strings with embedded commas and escaped quotes.
 */
function parseTuple(string $s): array
{
    $parts = [];
    $len = strlen($s);
    $i = 0;

    while ($i < $len) {
        // Skip whitespace
        while ($i < $len && ctype_space($s[$i])) $i++;

        if ($i >= $len) break;

        if ($s[$i] === "'") {
            // Quoted string
            $i++;
            $str = '';
            while ($i < $len) {
                if ($s[$i] === '\\' && $i + 1 < $len) {
                    $str .= $s[$i] . $s[$i+1];
                    $i += 2;
                    continue;
                }
                if ($s[$i] === "'") {
                    // Check for escaped quote (doubled)
                    if ($i + 1 < $len && $s[$i+1] === "'") {
                        $str .= "''";
                        $i += 2;
                        continue;
                    }
                    $i++;
                    break;
                }
                $str .= $s[$i];
                $i++;
            }
            $parts[] = "'" . $str . "'";
        } elseif ($s[$i] === 'N' && substr($s, $i, 4) === 'NULL') {
            $parts[] = 'NULL';
            $i += 4;
        } else {
            // Numeric or other literal
            $str = '';
            while ($i < $len && $s[$i] !== ',' && !ctype_space($s[$i])) {
                $str .= $s[$i];
                $i++;
            }
            $parts[] = $str;
        }

        // Skip the comma
        while ($i < $len && (ctype_space($s[$i]) || $s[$i] === ',')) $i++;
    }

    return $parts;
}