<?php
// scan_translations.php - Scan all view files for __('...') keys

$viewDir = __DIR__ . '/views';
$langDir = __DIR__ . '/lang';

// Load existing translations
$en = [];
$ar = [];
if (file_exists($langDir . '/en.php')) {
    $en = include $langDir . '/en.php';
}
if (file_exists($langDir . '/ar.php')) {
    $ar = include $langDir . '/ar.php';
}

// Scan all PHP files in views
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewDir));
$pattern = "/__\\(['\"](.*?)['\"]\\)/";
$usedKeys = [];

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        if (preg_match_all($pattern, $content, $matches)) {
            foreach ($matches[1] as $key) {
                $usedKeys[$key] = true;
            }
        }
    }
}

$usedKeys = array_keys($usedKeys);
sort($usedKeys);

// Compare with existing keys
$missingInEn = [];
$missingInAr = [];
$emptyAr = [];

foreach ($usedKeys as $key) {
    if (!isset($en[$key])) {
        $missingInEn[] = $key;
    }
    if (!isset($ar[$key])) {
        $missingInAr[] = $key;
    } elseif (empty($ar[$key])) {
        $emptyAr[] = $key;
    }
}

// Output report
echo "========== TRANSLATION REVIEW REPORT ==========\n\n";
echo "Total unique keys found: " . count($usedKeys) . "\n";
echo "Keys defined in en.php: " . count($en) . "\n";
echo "Keys defined in ar.php: " . count($ar) . "\n\n";

echo "--- MISSING IN en.php (" . count($missingInEn) . " keys) ---\n";
if ($missingInEn) {
    foreach ($missingInEn as $key) {
        echo "  - $key\n";
    }
} else {
    echo "  ✅ All keys are defined in en.php.\n";
}

echo "\n--- MISSING IN ar.php (" . count($missingInAr) . " keys) ---\n";
if ($missingInAr) {
    foreach ($missingInAr as $key) {
        echo "  - $key\n";
    }
} else {
    echo "  ✅ All keys are defined in ar.php.\n";
}

echo "\n--- EMPTY ARABIC TRANSLATIONS (" . count($emptyAr) . " keys) ---\n";
if ($emptyAr) {
    foreach ($emptyAr as $key) {
        echo "  - $key => '" . $en[$key] . "'\n";
    }
} else {
    echo "  ✅ All Arabic translations are filled.\n";
}

echo "\n================================================\n";
echo "To fix missing keys, add them to the respective language file.\n";
echo "For empty Arabic translations, provide proper Arabic text.\n";