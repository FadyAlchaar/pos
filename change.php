<?php
// replace_currency.php
$files = [
    'views/dashboard.php',
    'views/products.php',
    'views/sales.php',
    'views/pos.php',
    // ... add all files
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    // Replace PHP `$` before numbers with dynamic symbol
    $content = preg_replace('/\$([0-9,\.]+)/', '<?= getCurrencySymbol() ?>$1', $content);
    // Replace JavaScript `'$'` with `currencySymbol`
    $content = preg_replace("/'\$'/", 'currencySymbol', $content);
    file_put_contents($file, $content);
    echo "Updated: $file\n";
}