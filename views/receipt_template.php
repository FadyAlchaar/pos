<?php
// views/receipt_template.php
// Rendered inside generateReceiptPDF(). Available variables:
// $sale, $storeName, $storeAddress, $storePhone, $currency, $footerText,
// $isRtl, $settings (field toggles + direction/font from the receipt_templates
// table), $invoiceBarcode (base64 PNG data URI or null).

$itemCount = count($sale['items'] ?? []);
$totalQty = 0;
foreach (($sale['items'] ?? []) as $it) {
    $totalQty += (int)$it['quantity'];
}
?>
<!DOCTYPE html>
<html dir="<?= $isRtl ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: <?= $isRtl ? 'amiri, dejavusans' : 'dejavusans, Arial' ?>;
            font-size: <?= (int)$settings['font_size'] ?>px;
            font-weight: <?= $settings['font_weight'] === 'bold' ? 'bold' : 'normal' ?>;
            direction: <?= $isRtl ? 'rtl' : 'ltr' ?>;
            text-align: <?= $isRtl ? 'right' : 'left' ?>;
            margin: 0;
            padding: 0;
            background: white;
            color: #1a1a1a;
        }
        .receipt {
            padding: 4px 2px;
        }
        .center { text-align: center; }
        .dashed {
            border-top: 1px dashed #333;
            margin: 6px 0;
        }
        .solid {
            border-top: 1.5px solid #000;
            margin: 6px 0;
        }
        .store-name {
            font-size: 1.5em;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin: 0 0 2px 0;
        }
        .store-meta {
            font-size: 0.85em;
            color: #444;
            line-height: 1.5;
        }
        .invoice-badge {
            display: inline-block;
            margin-top: 6px;
            padding: 3px 10px;
            border: 1px solid #000;
            border-radius: 3px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .info-table {
            width: 100%;
            font-size: 0.9em;
            margin: 8px 0;
        }
        .info-table td {
            padding: 1px 0;
        }
        .info-table .label {
            color: #555;
            white-space: nowrap;
        }
        .info-table .value {
            font-weight: bold;
            text-align: <?= $isRtl ? 'left' : 'right' ?>;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9em;
            margin: 6px 0;
        }
        table.items th {
            border-bottom: 1.5px solid #000;
            padding: 4px 2px 5px 2px;
            text-align: <?= $isRtl ? 'right' : 'left' ?>;
            font-size: 0.8em;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        table.items td {
            padding: 4px 2px;
            border-bottom: 1px dotted #ccc;
            vertical-align: top;
        }
        table.items .num { text-align: <?= $isRtl ? 'left' : 'right' ?>; white-space: nowrap; }
        table.items .qty { text-align: center; }
        .item-name { font-weight: 600; }
        .summary-line {
            display: flex;
            justify-content: space-between;
            font-size: 0.85em;
            color: #444;
            margin: 4px 0;
        }
        .totals { margin-top: 4px; font-size: 0.92em; }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }
        .totals-row.discount { color: #a33; }
        .totals-row.grand-total {
            font-size: 1.3em;
            font-weight: bold;
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000;
            padding: 6px 0;
            margin-top: 6px;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 0.85em;
            color: #333;
        }
        .footer .thanks {
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 6px;
        }
        .barcode-wrap {
            text-align: center;
            margin-top: 10px;
        }
        .barcode-wrap img {
            max-width: 90%;
            height: 40px;
        }
        .barcode-wrap .barcode-text {
            font-size: 0.75em;
            letter-spacing: 2px;
            margin-top: 2px;
            color: #333;
        }
    </style>
</head>
<body>
<div class="receipt">

    <?php if ($settings['show_store_name'] || $settings['show_store_address'] || $settings['show_store_phone']): ?>
    <div class="center">
        <?php if ($settings['show_store_name']): ?>
            <div class="store-name"><?= htmlspecialchars($storeName) ?></div>
        <?php endif; ?>
        <div class="store-meta">
            <?php if ($settings['show_store_address'] && !empty($storeAddress)): ?>
                <?= htmlspecialchars($storeAddress) ?><br>
            <?php endif; ?>
            <?php if ($settings['show_store_phone'] && !empty($storePhone)): ?>
                <?= __('phone') ?>: <?= htmlspecialchars($storePhone) ?>
            <?php endif; ?>
        </div>
        <?php if ($settings['show_invoice_no']): ?>
            <div class="invoice-badge">#<?= htmlspecialchars($sale['invoice_no']) ?></div>
        <?php endif; ?>
    </div>
    <div class="dashed"></div>
    <?php endif; ?>

    <table class="info-table">
        <?php if ($settings['show_date']): ?>
        <tr>
            <td class="label"><?= __('date') ?></td>
            <td class="value"><?= date('Y-m-d H:i', strtotime($sale['created_at'])) ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($settings['show_cashier']): ?>
        <tr>
            <td class="label"><?= __('cashier') ?></td>
            <td class="value"><?= htmlspecialchars($sale['cashier'] ?? 'N/A') ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($settings['show_customer']): ?>
        <tr>
            <td class="label"><?= __('customer') ?></td>
            <td class="value"><?= htmlspecialchars($sale['customer_display_name'] ?? $sale['customer_name'] ?? 'Walk-in') ?></td>
        </tr>
        <?php endif; ?>
    </table>

    <div class="dashed"></div>

    <table class="items">
        <thead>
            <tr>
                <th><?= __('item') ?></th>
                <th class="qty"><?= __('qty') ?></th>
                <th class="num"><?= __('price') ?></th>
                <th class="num"><?= __('total') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sale['items'] as $item): ?>
            <tr>
                <td class="item-name"><?= htmlspecialchars($item['product_name']) ?></td>
                <td class="qty"><?= (int)$item['quantity'] ?></td>
                <td class="num"><?= $currency . ' ' . number_format($item['price'], 2) ?></td>
                <td class="num"><?= $currency . ' ' . number_format($item['total'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="summary-line">
        <span><?= $itemCount ?> <?= __('item') ?><?= $itemCount === 1 ? '' : 's' ?></span>
        <span><?= $totalQty ?> <?= __('qty') ?></span>
    </div>

    <div class="dashed"></div>

    <div class="totals">
        <?php if ($settings['show_subtotal']): ?>
        <div class="totals-row"><span><?= __('subtotal') ?></span><span><?= $currency . ' ' . number_format($sale['subtotal'], 2) ?></span></div>
        <?php endif; ?>
        <?php if ($settings['show_discount'] && $sale['discount'] > 0): ?>
        <div class="totals-row discount"><span><?= __('discount') ?></span><span>-<?= $currency . ' ' . number_format($sale['discount'], 2) ?></span></div>
        <?php endif; ?>
        <?php if ($settings['show_tax'] && $sale['tax'] > 0): ?>
        <div class="totals-row"><span><?= __('tax') ?></span><span><?= $currency . ' ' . number_format($sale['tax'], 2) ?></span></div>
        <?php endif; ?>
        <?php if ($settings['show_total']): ?>
        <div class="totals-row grand-total"><span><?= __('total') ?></span><span><?= $currency . ' ' . number_format($sale['total'], 2) ?></span></div>
        <?php endif; ?>
    </div>

    <?php if ($invoiceBarcode): ?>
    <div class="barcode-wrap">
        <img src="<?= $invoiceBarcode ?>" alt="<?= htmlspecialchars($sale['invoice_no']) ?>">
        <div class="barcode-text"><?= htmlspecialchars($sale['invoice_no']) ?></div>
    </div>
    <?php endif; ?>

    <?php if ($settings['show_footer']): ?>
    <div class="footer">
        <div class="solid"></div>
        <div class="thanks"><?= htmlspecialchars($footerText) ?></div>
    </div>
    <?php endif; ?>

</div>
</body>
</html>
