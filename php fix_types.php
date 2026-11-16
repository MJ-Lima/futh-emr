<?php
// fix_types.php — strips BOM and fixes Filament property types
// Run from cmd: php fix_types.php

 $dir = __DIR__ . '/app/Filament/Pages';

 $replacements = [
    'protected static ?string $navigationLabel'  => 'protected static \\UnitEnum|string|null $navigationLabel',
    'protected static ?string $navigationGroup'  => 'protected static \\UnitEnum|string|null $navigationGroup',
    'protected static ?string $title'             => 'protected static \\UnitEnum|string|null $title',
    'protected static ?string $slug'             => 'protected static \\UnitEnum|string|null $slug',
    'protected static ?string $navigationIcon'   => 'protected static \\BackedEnum|string|null $navigationIcon',
];

 $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($it as $file) {
    if ($file->getExtension() !== 'php') continue;
    $path = $file->getRealPath();

    $content = file_get_contents($path);

    // Strip BOM if present
    if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
        $content = substr($content, 3);
        echo "Stripped BOM: $path\n";
    }

    $original = $content;
    $content = strtr($content, $replacements);

    if ($content !== $original) {
        // Write as UTF-8 without BOM
        file_put_contents($path, $content);
        echo "Fixed: $path\n";
    }
}

echo "Done.\n";