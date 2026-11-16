<?php
/**
 * Finds all Resource files that STILL have typed navigation properties
 * (the source of the Filament v4 type-mismatch errors).
 *
 * Run: php check-typed-nav-props.php
 */

 $resourcesDir = __DIR__ . '/app/Filament/Resources';

 $rii = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($resourcesDir, FilesystemIterator::SKIP_DOTS)
);
 $resourceFiles = [];
foreach ($rii as $file) {
    if ($file->isFile() && preg_match('/Resource\.php$/', $file->getFilename())) {
        $resourceFiles[] = $file->getPathname();
    }
}

echo "Found " . count($resourceFiles) . " resource files.\n\n";

 $bad = [];
foreach ($resourceFiles as $file) {
    $content = file_get_contents($file);
    $relPath = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file);

    // Match any typed navigation property declaration
    if (preg_match('/protected\s+static\s+(\S+)\s+\$navigation(?:Icon|Group|Label|Sort)\s*=/', $content, $m)) {
        $bad[] = "$relPath (has: $m[1] \$" . substr($m[0], strpos($m[0], '$')) . ")";
    }
}

if (empty($bad)) {
    echo "✓ All clean — no typed navigation properties found.\n";
} else {
    echo count($bad) . " files STILL have typed navigation properties:\n";
    foreach ($bad as $b) echo "  - $b\n";
    echo "\nFix these by removing the property and adding getter methods.\n";
}