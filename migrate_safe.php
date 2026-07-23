<?php
// migrate_safe.php - Safe migration: extracts translations and builds lang files, then replaces __() calls

$viewDir = __DIR__ . '/views';
$langDir = __DIR__ . '/lang';

// Load existing translations if they exist (to preserve manual entries)
$enTranslations = [];
$arTranslations = [];
if (file_exists($langDir . '/en.php')) {
    $enTranslations = include $langDir . '/en.php';
}
if (file_exists($langDir . '/ar.php')) {
    $arTranslations = include $langDir . '/ar.php';
}

// Pattern to match __('English', 'Arabic')
$pattern = '/__\([\'"](.*?)[\'"],\s*[\'"](.*?)[\'"]\)/';

// Collect all translations from all view files
$newTranslations = [];

// Scan all PHP files in views (recursively)
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewDir));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $fullMatch = $match[0];
                $english = trim($match[1]);
                $arabic = trim($match[2]);
                // Generate a key from the English text (lowercase, underscores)
                $key = strtolower($english);
                $key = preg_replace('/[^a-zA-Z0-9_]/', '_', $key);
                $key = preg_replace('/_{2,}/', '_', $key);
                $key = trim($key, '_');
                // Avoid empty key
                if (empty($key)) {
                    $key = 'unknown_' . uniqid();
                }
                // Store with both languages (keep existing if already defined)
                if (!isset($newTranslations[$key])) {
                    $newTranslations[$key] = [
                        'en' => $english,
                        'ar' => $arabic
                    ];
                } else {
                    // If key already exists, maybe the English or Arabic is different? We'll keep the first occurrence.
                    // But if the Arabic is empty and we have a new one, we can update.
                    if (empty($newTranslations[$key]['ar']) && !empty($arabic)) {
                        $newTranslations[$key]['ar'] = $arabic;
                    }
                    if (empty($newTranslations[$key]['en']) && !empty($english)) {
                        $newTranslations[$key]['en'] = $english;
                    }
                }
            }
        }
    }
}

// Merge with existing translations (preserve manual additions)
foreach ($enTranslations as $key => $value) {
    if (!isset($newTranslations[$key])) {
        $newTranslations[$key] = ['en' => $value, 'ar' => ''];
    }
    // If we have existing Arabic for this key, keep it
    if (isset($arTranslations[$key]) && !empty($arTranslations[$key])) {
        $newTranslations[$key]['ar'] = $arTranslations[$key];
    }
}

// Build language files
$enContent = "<?php\n// lang/en.php - Auto-generated translations\n\nreturn [\n";
$arContent = "<?php\n// lang/ar.php - Auto-generated translations\n\nreturn [\n";
foreach ($newTranslations as $key => $value) {
    $escapedEn = addslashes($value['en']);
    $escapedAr = addslashes($value['ar']);
    $enContent .= "    '$key' => '$escapedEn',\n";
    $arContent .= "    '$key' => '$escapedAr',\n";
}
$enContent .= "];\n";
$arContent .= "];\n";

file_put_contents($langDir . '/en.php', $enContent);
file_put_contents($langDir . '/ar.php', $arContent);

// Now replace __('English', 'Arabic') with __('key') in all view files
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        // Replace each match
        $newContent = preg_replace_callback($pattern, function($matches) use ($newTranslations) {
            $fullMatch = $matches[0];
            $english = trim($matches[1]);
            $arabic = trim($matches[2]);
            // Generate key same way
            $key = strtolower($english);
            $key = preg_replace('/[^a-zA-Z0-9_]/', '_', $key);
            $key = preg_replace('/_{2,}/', '_', $key);
            $key = trim($key, '_');
            if (empty($key)) {
                $key = 'unknown_' . uniqid();
            }
            return "__('$key')";
        }, $content);
        if ($content !== $newContent) {
            file_put_contents($path, $newContent);
            echo "Updated: $path\n";
        }
    }
}

echo "\n✅ Migration complete!\n";
echo "Total translation keys: " . count($newTranslations) . "\n";
echo "Language files written to: $langDir\n";
echo "Please review lang/ar.php to ensure all Arabic translations are correct.\n";