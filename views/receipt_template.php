<!DOCTYPE html>
<html dir="<?= $isRtl ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: <?= $isRtl ? 'amiri, dejavusans' : 'dejavusans, Arial' ?>;
            font-size: 12px;
            direction: <?= $isRtl ? 'rtl' : 'ltr' ?>;
            text-align: <?= $isRtl ? 'right' : 'left' ?>;
            margin: 0;
            padding: 10px;
            background: white;
        }
        .receipt {
            border: 2px solid #000;
            padding: 12px;
            border-radius: 4px;
            max-width: 300px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
        }
        .header small {
            color: #666;
            font-size: 10px;
        }
        .info {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ccc;
            font-size: 11px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }
        .info-row .label {
            font-weight: bold;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin: 8px 0;
        }
        .table th {
            border-bottom: 2px solid #000;
            padding: 4px 2px;
            text-align: <?= $isRtl ? 'right' : 'left' ?>;
            font-size: 10px;
            text-transform: uppercase;
        }
        .table td {
            border-bottom: 1px solid #ddd;
            padding: 4px 2px;
            text-align: <?= $isRtl ? 'right' : 'left' ?>;
        }
        .table td:last-child,
        .table th:last-child {
            text-align: right;
        }
        .table td:first-child,
        .table th:first-child {
            text-align: <?= $isRtl ? 'right' : 'left' ?>;
        }
        .totals {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 2px solid #000;
            font-size: 11px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }
        .totals-row.total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 6px;
            margin-top: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 2px solid #000;
            font-size: 10px;
            color: #666;
        }
        [dir="rtl"] .table td:last-child,
        [dir="rtl"] .table th:last-child {
            text-align: left;
        }
        [dir="rtl"] .table td:first-child,
        [dir="rtl"] .table th:first-child {
            text-align: right;
        }
        [dir="rtl"] .info-row {
            flex-direction: row-reverse;
        }
    </style>
</head>
<body>
<div class="receipt">
    <div class="header">
        <h2><?= htmlspecialchars($storeName) ?></h2>
        <small><?= htmlspecialchars($storeAddress) ?></small><br>
        <small><?= __('phone') ?>: <?= htmlspecialchars($storePhone) ?></small>
    </div>

    <div class="info">
        <div class="info-row"><span class="label"><?= __('invoice') ?>:</span> <span><?= $sale['invoice_no'] ?></span></div>
        <div class="info-row"><span class="label"><?= __('date') ?>:</span> <span><?= date('Y-m-d H:i:s', strtotime($sale['created_at'])) ?></span></div>
        <div class="info-row"><span class="label"><?= __('cashier') ?>:</span> <span><?= $sale['cashier'] ?? 'N/A' ?></span></div>
        <div class="info-row"><span class="label"><?= __('customer') ?>:</span> <span><?= $sale['customer_name'] ?? 'Walk-in' ?></span></div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th><?= __('item') ?></th>
                <th style="text-align:center;"><?= __('qty') ?></th>
                <th style="text-align:right;"><?= __('price') ?></th>
                <th style="text-align:right;"><?= __('total') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sale['items'] as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td style="text-align:center;"><?= $item['quantity'] ?></td>
                <td style="text-align:right;"><?= $currency . ' ' . number_format($item['price'], 2) ?></td>
                <td style="text-align:right;"><?= $currency . ' ' . number_format($item['total'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totals">
        <div class="totals-row"><span><?= __('subtotal') ?></span> <span><?= $currency . ' ' . number_format($sale['subtotal'], 2) ?></span></div>
        <?php if ($sale['discount'] > 0): ?>
        <div class="totals-row"><span><?= __('discount') ?></span> <span>-<?= $currency . ' ' . number_format($sale['discount'], 2) ?></span></div>
        <?php endif; ?>
        <?php if ($sale['tax'] > 0): ?>
        <div class="totals-row"><span><?= __('tax') ?></span> <span><?= $currency . ' ' . number_format($sale['tax'], 2) ?></span></div>
        <?php endif; ?>
        <div class="totals-row total"><span><?= __('total') ?></span> <span><?= $currency . ' ' . number_format($sale['total'], 2) ?></span></div>
    </div>

    <div class="footer">
        <?= htmlspecialchars($footerText) ?>
    </div>
</div>
</body>
</html>