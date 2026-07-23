<?php
// fix_translations.php - Auto-add missing keys to language files

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

// Scan for used keys
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

// Add missing keys to en and ar
$added = 0;
foreach ($usedKeys as $key) {
    if (!isset($en[$key])) {
        $en[$key] = ucwords(str_replace('_', ' ', $key)); // Generate English placeholder
        $added++;
    }
    if (!isset($ar[$key])) {
        $ar[$key] = ''; // Empty Arabic placeholder
    }
}

// Write back
$enContent = "<?php\n// lang/en.php - Auto-updated translations\n\nreturn [\n";
foreach ($en as $key => $value) {
    $escaped = addslashes($value);
    $enContent .= "    '$key' => '$escaped',\n";
}
$enContent .= "];\n";
file_put_contents($langDir . '/en.php', $enContent);

$arContent = "<?php\n// lang/ar.php - Auto-updated translations\n\nreturn [\n";
foreach ($ar as $key => $value) {
    $escaped = addslashes($value);
    $arContent .= "    '$key' => '$escaped',\n";
}
$arContent .= "];\n";
file_put_contents($langDir . '/ar.php', $arContent);

echo "✅ Added $added missing keys to en.php and ar.php.\n";
echo "Please review ar.php and fill in the Arabic translations.\n";