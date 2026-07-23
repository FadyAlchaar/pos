<?php
// src/Barcode.php - Simple Code128 barcode generator

// ============ Barcode Functions ============
function generateBarcodeImage($barcode) {
    if (empty($barcode)) {
        return null;
    }
    
    // Use the picqer/php-barcode-generator library
    if (class_exists('Picqer\\Barcode\\BarcodeGeneratorPNG')) {
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $barcodeData = $generator->getBarcode($barcode, $generator::TYPE_CODE_128);
        return 'data:image/png;base64,' . base64_encode($barcodeData);
    }
    
    return null;
}