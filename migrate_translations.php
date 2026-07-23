<?php
// migrate_translations.php - Run this once to convert all __() calls to keys

$viewDir = __DIR__ . '/views';
$langDir = __DIR__ . '/lang';

// Load existing translations (if any) to preserve them
$translations = [];
if (file_exists($langDir . '/en.php')) {
    $translations = include $langDir . '/en.php';
}

// Scan all view files
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewDir));
$pattern = '/__\([\'"](.*?)[\'"],\s*[\'"](.*?)[\'"]\)/';

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        $newContent = $content;
        $matches = [];
        
        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $fullMatch = $match[0];
                $english = trim($match[1]);
                $arabic = trim($match[2]);
                
                // Generate a key from the English text
                $key = strtolower($english);
                $key = preg_replace('/[^a-zA-Z0-9_]/', '_', $key);
                $key = preg_replace('/_{2,}/', '_', $key);
                $key = trim($key, '_');
                
                // Add to translations
                $translations[$key] = $english;
                
                // Replace in content
                $replacement = "__('$key')";
                $newContent = str_replace($fullMatch, $replacement, $newContent);
            }
            
            file_put_contents($path, $newContent);
            echo "Updated: $path\n";
        }
    }
}

// Save translations to lang/en.php
$enContent = "<?php\n// lang/en.php - Auto-generated translations\n\nreturn [\n";
foreach ($translations as $key => $value) {
    $escapedValue = addslashes($value);
    $enContent .= "    '$key' => '$escapedValue',\n";
}
$enContent .= "];\n";
file_put_contents($langDir . '/en.php', $enContent);

// Save translations to lang/ar.php (preserve existing Arabic, or leave empty)
$arContent = "<?php\n// lang/ar.php - Auto-generated translations\n\nreturn [\n";
foreach ($translations as $key => $value) {
    $arContent .= "    '$key' => '', // TODO: Add Arabic translation\n";
}
$arContent .= "];\n";
file_put_contents($langDir . '/ar.php', $arContent);

echo "\n✅ Migration complete!\n";
echo "Updated " . count($translations) . " translation keys.\n";
echo "Please review lang/en.php and lang/ar.php and add Arabic translations.\n";