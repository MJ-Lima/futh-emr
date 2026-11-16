<?php
/**
 * Removes stray spaces after namespace separators in use statements.
 * Run: php fix-import-spaces.php
 */

 $dir = __DIR__ . '/app';

 $rii = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
);
 $files = [];
foreach ($rii as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $files[] = $file->getPathname();
    }
}

echo "Scanning " . count($files) . " PHP files...\n\n";

 $patched = 0;
 $skipped = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    $relPath = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file);

    // Replace "use Some\ Namespace\Class" with "use Some\Namespace\Class"
    // Pattern: word\ space Word
    $newContent = preg_replace('/(use\s+[A-Za-z][A-Za-z0-9_]*)\\\\\s+([A-Za-z])/', '$1\\\\$2', $content);

    if ($newContent === $content) {
        $skipped++;
        continue;
    }

    file_put_contents($file, $newContent);
    echo "FIXED: $relPath\n";
    $patched++;
}

echo "\n--- Summary ---\n";
echo "Fixed: $patched files\n";
echo "Skipped: $skipped\n";