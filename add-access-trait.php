<?php
 $resourcesDir = __DIR__ . '/app/Filament/Resources';
 $traitFqcn = 'App\\Filament\\Traits\\HasRoleBasedAccess';
 $traitShortName = 'HasRoleBasedAccess';

 $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($resourcesDir, FilesystemIterator::SKIP_DOTS));
 $resourceFiles = [];
foreach ($rii as $file) {
    if ($file->isFile() && preg_match('/Resource\.php$/', $file->getFilename())) {
        $resourceFiles[] = $file->getPathname();
    }
}

echo "Found " . count($resourceFiles) . " resource files.\n\n";

 $updated = 0; $skipped = 0; $failed = [];

foreach ($resourceFiles as $file) {
    $content = file_get_contents($file);
    $relPath = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file);

    if (preg_match('/\buse\s+' . preg_quote($traitShortName, '/') . '\s*;/', $content)) {
        $skipped++; continue;
    }

    if (!preg_match('/^(abstract\s+)?class\s+(\w+Resource)\s+extends\s+\S+(?:\s+implements\s+[^\{]+)?\s*\{/m', $content, $m, PREG_OFFSET_CAPTURE)) {
        $failed[] = "$relPath"; continue;
    }

    $classLineEnd = $m[0][1] + strlen($m[0][0]);
    $after = substr($content, $classLineEnd);

    if (preg_match('/^\s*use\s+([^\n;]+);\s*$/m', $after, $useMatch, PREG_OFFSET_CAPTURE)) {
        $useStatement = $useMatch[0][0];
        $useStatementOffset = $classLineEnd + $useMatch[0][1];
        $newUseStatement = preg_replace('/(\buse\s+)([^\n;]+)(;\s*)/', '$1$2, ' . $traitShortName . '$3', $useStatement, 1);
        $newContent = substr($content, 0, $useStatementOffset) . $newUseStatement . substr($content, $useStatementOffset + strlen($useStatement));
        file_put_contents($file, $newContent);
        echo "UPDATED: $relPath\n";
        $updated++;
    } else {
        $newContent = substr($content, 0, $classLineEnd) . "\n    use " . $traitShortName . ";\n" . substr($content, $classLineEnd);
        $newContent = preg_replace('/^namespace\s+([^;]+);\s*$/m', "namespace $1;\n\nuse $traitFqcn;", $newContent, 1);
        file_put_contents($file, $newContent);
        echo "UPDATED: $relPath\n";
        $updated++;
    }
}

echo "\n--- Summary ---\nUpdated: $updated\nSkipped: $skipped\n";
if (!empty($failed)) { echo "Failed:\n"; foreach ($failed as $f) echo "  - $f\n"; }