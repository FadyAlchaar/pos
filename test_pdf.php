<?php
// test_pdf.php
require_once __DIR__ . '/src/Functions.php';
$result = generateReceiptPDF(1); // replace 1 with a valid sale ID
if ($result['success']) {
    echo "PDF saved to: " . $result['file'];
} else {
    echo "Error: " . $result['message'];
}