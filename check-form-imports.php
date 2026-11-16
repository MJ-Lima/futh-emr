<?php
/**
 * Scans every *Form.php and *Infolist.php for missing class imports.
 * Reports files that use a class but don't import it.
 *
 * Run: php check-form-imports.php
 */

 $schemasDir = __DIR__ . '/app/Filament/Resources';

 $rii = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($schemasDir, FilesystemIterator::SKIP_DOTS)
);
 $files = [];
foreach ($rii as $file) {
    if ($file->isFile() && preg_match('/(Form|Infolist)\.php$/', $file->getFilename())) {
        $files[] = $file->getPathname();
    }
}

echo "Found " . count($files) . " Form/Infolist files.\n\n";

 $issues = [];

 $commonClasses = [
    'Select', 'TextInput', 'DatePicker', 'TimePicker', 'DateTimePicker',
    'Textarea', 'Toggle', 'Checkbox', 'Radio', 'FileUpload',
    'Section', 'Grid', 'Wizard', 'Tabs',
    'TextEntry', 'ImageEntry', 'IconEntry',
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    $relPath = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file);
    
    // Find all ClassName::make( or new ClassName( calls
    foreach ($commonClasses as $cls) {
        // Skip if imported via "use" or referenced as FQCN
        if (preg_match('/^use\s+[\w\\\\]+' . preg_quote($cls, '/') . ';/m', $content)) {
            continue;
        }
        if (preg_match('/\\\\' . preg_quote($cls, '/') . '::/', $content)) {
            continue;  // FQCN used
        }
        // Check if used unqualified
        if (preg_match('/\b' . preg_quote($cls, '/') . '::(make|create)\(/', $content)) {
            $issues[] = "$relPath missing: $cls";
        }
    }
}

if (empty($issues)) {
    echo "No missing imports found.\n";
} else {
    echo "Files with missing imports:\n";
    foreach ($issues as $i) {
        echo "  - $i\n";
    }
    echo "\n--- Summary ---\n";
    echo count(array_unique(array_map(fn($s) => explode(' missing: ', $s)[0], $issues))) . " files need fixing\n";
}