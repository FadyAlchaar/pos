<?php
/*
 * Stock Movement Report
 *
 * This page uses the existing POS AJAX endpoints:
 *   - search_products_for_transfer
 *   - get_product_by_barcode
 *   - get_stock_movement
 *
 * The backend getStockMovementReport() must already be installed as
 * described in the previous stock-movement patch.
 */
$today = date('Y-m-d');
$firstDay = date('Y-m-01');
?>

<style>
.stock-movement-toolbar {
    display:grid;
    grid-template-columns:minmax(280px,2fr) minmax(180px,1fr) minmax(150px,1fr) auto;
    gap:12px;
    align-items:end;
}
.sm-search-row {
    display:grid;
    grid-template-columns:minmax(280px,2fr) minmax(240px,1fr);
    gap:12px;
}
.stock-movement-summary {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:15px;
    margin:20px 0;
}
.sm-card {
    background:#fff;
    border-radius:12px;
    padding:18px 20px;
    box-shadow:0 2px 10px rgba(0,0,0,.06);
}
.sm-card small {display:block;color:#95a5a6;margin-bottom:5px;}
.sm-card strong {font-size:24px;}
.sm-in strong {color:#27ae60;}
.sm-out strong {color:#e74c3c;}
.sm-balance strong {color:#6c63ff;}

.sm-product-picker {position:relative;}
.sm-product-results {
    position:absolute;
    top:100%;
    left:0;
    right:0;
    background:#fff;
    border:1px solid #ddd;
    border-top:0;
    border-radius:0 0 8px 8px;
    max-height:300px;
    overflow-y:auto;
    display:none;
    z-index:3000;
    box-shadow:0 7px 18px rgba(0,0,0,.14);
}
.sm-product-result {
    padding:11px 14px;
    cursor:pointer;
    border-bottom:1px solid #eee;
    transition:background .12s;
}
.sm-product-result:hover {background:#f5f5f5;}
.sm-product-result:last-child {border-bottom:0;}
.sm-product-name {font-weight:600;color:#2c3e50;}
.sm-product-code {font-size:12px;color:#7f8c8d;margin-top:3px;}
.sm-selected {
    margin-top:8px;
    font-size:12px;
    color:#27ae60;
    min-height:17px;
}
.sm-scan-hint {
    font-size:11px;
    color:#95a5a6;
    margin-top:5px;
}
.movement-in {color:#27ae60;font-weight:700;}
.movement-out {color:#e74c3c;font-weight:700;}
.movement-badge {
    display:inline-block;
    padding:4px 9px;
    border-radius:20px;
    font-size:11px;
    font-weight:700;
    white-space:nowrap;
}
.movement-purchase {background:#d5f5e3;color:#27ae60;}
.movement-sale {background:#fadbd8;color:#e74c3c;}
.movement-return {background:#d6eaf8;color:#2874a6;}
.movement-transfer {background:#fdebd0;color:#b9770e;}
.sm-warning {
    display:none;
    margin:15px 0;
    padding:12px 15px;
    border-radius:8px;
    background:#fff3cd;
    color:#856404;
    border:1px solid #ffe69c;
}
.sm-product-info {
    display:none;
    margin-top:15px;
    padding:15px 18px;
    background:#f8f9fa;
    border-radius:10px;
}
@media(max-width:1000px) {
    .stock-movement-toolbar {grid-template-columns:1fr 1fr;}
}
@media(max-width:700px) {
    .sm-search-row,
    .stock-movement-toolbar,
    .stock-movement-summary {grid-template-columns:1fr;}
}
@media print {
    body * {visibility:hidden !important;}
    #stockMovementPrint,
    #stockMovementPrint * {visibility:visible !important;}
    #stockMovementPrint {
        position:absolute;
        left:0;
        top:0;
        width:100%;
        box-shadow:none !important;
    }
    .no-print {display:none !important;}
    .table {font-size:11px;}
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 style="font-weight:700;color:var(--dark);margin:0;">
            <i class="fas fa-exchange-alt"></i>
            <?= t('Stock Movement', 'حركة المخزون') ?>
        </h4>
        <small class="text-muted">
            <?= t('Complete movement history for a material', 'الحركة الكاملة لمادة / صنف محدد') ?>
        </small>
    </div>

    <div class="d-flex gap-2 no-print">
        <button class="btn btn-sm btn-outline" onclick="window.print()">
            <i class="fas fa-print"></i> <?= __('print') ?>
        </button>
        <button class="btn btn-sm btn-success" onclick="exportStockMovementCSV()">
            <i class="fas fa-file-csv"></i> <?= __('export_csv') ?>
        </button>
    </div>
</div>

<div class="card no-print">
    <div class="card-body">

        <div class="sm-search-row">

            <!-- Product name / code live search -->
            <div class="form-group" style="margin:0;">
                <label>
                    <i class="fas fa-box"></i>
                    <?= t('Material / Product', 'المادة / المنتج') ?>
                </label>

                <div class="sm-product-picker">
                    <input
                        type="text"
                        id="smProductSearch"
                        class="form-control"
                        placeholder="<?= t(
                            'Type product name, barcode or code...',
                            'اكتب اسم المادة أو الباركود أو الرمز...'
                        ) ?>"
                        autocomplete="off"
                        oninput="searchStockMovementProducts(this.value)"
                        onkeydown="handleStockMovementSearchKey(event)"
                    >

                    <input type="hidden" id="smProduct" value="">

                    <div id="smProductResults" class="sm-product-results"></div>
                </div>

                <div id="smSelectedProduct" class="sm-selected"></div>
            </div>

            <!-- Barcode scanner -->
            <div class="form-group" style="margin:0;">
                <label>
                    <i class="fas fa-barcode"></i>
                    <?= t('Scan Barcode', 'مسح الباركود') ?>
                </label>

                <input
                    type="text"
                    id="smBarcodeScanner"
                    class="form-control"
                    placeholder="<?= t(
                        'Scan or type barcode, then press Enter',
                        'امسح أو اكتب الباركود ثم اضغط Enter'
                    ) ?>"
                    autocomplete="off"
                    inputmode="numeric"
                    onkeydown="handleStockMovementBarcode(event)"
                >

                <div class="sm-scan-hint">
                    <?= t(
                        'USB barcode scanners normally send Enter automatically.',
                        'قارئ الباركود USB عادةً يرسل Enter تلقائياً.'
                    ) ?>
                </div>
            </div>

        </div>

        <div class="stock-movement-toolbar" style="margin-top:15px;">

            <div></div>

            <div class="form-group" style="margin:0;">
                <label>
                    <i class="fas fa-calendar"></i>
                    <?= t('From', 'من') ?>
                </label>
                <input type="date" id="smFrom" class="form-control" value="<?= $firstDay ?>">
            </div>

            <div class="form-group" style="margin:0;">
                <label>
                    <i class="fas fa-calendar"></i>
                    <?= t('To', 'إلى') ?>
                </label>
                <input type="date" id="smTo" class="form-control" value="<?= $today ?>">
            </div>

            <button class="btn btn-primary" onclick="loadStockMovement()">
                <i class="fas fa-search"></i>
                <?= __('search') ?>
            </button>

        </div>
    </div>
</div>

<div id="stockMovementPrint">

    <div id="smProductInfo" class="sm-product-info">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong id="smProductName"></strong>
                <div class="text-muted" id="smProductCode"></div>
            </div>
            <div>
                <span class="text-muted">
                    <?= t('Current Stock', 'المخزون الحالي') ?>:
                </span>
                <strong id="smCurrentStock">0</strong>
            </div>
        </div>
    </div>

    <div class="stock-movement-summary">
        <div class="sm-card sm-balance">
            <small><?= t('Opening Balance', 'الرصيد الافتتاحي') ?></small>
            <strong id="smOpening">0</strong>
        </div>

        <div class="sm-card sm-in">
            <small><?= t('Total In', 'إجمالي الداخل') ?></small>
            <strong id="smIn">0</strong>
        </div>

        <div class="sm-card sm-out">
            <small><?= t('Total Out', 'إجمالي الخارج') ?></small>
            <strong id="smOut">0</strong>
        </div>

        <div class="sm-card sm-balance">
            <small><?= t('Closing Balance', 'الرصيد الختامي') ?></small>
            <strong id="smClosing">0</strong>
        </div>
    </div>

    <div id="smWarning" class="sm-warning"></div>

    <div class="card">
        <div class="card-header">
            <h5>
                <?= t('Movement Details', 'تفاصيل الحركة') ?>
                <span id="smPeriod"
                      class="text-muted"
                      style="font-size:12px;font-weight:400;"></span>
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="smTable">
                    <thead>
                        <tr>
                            <th><?= __('date') ?></th>
                            <th><?= t('Movement', 'الحركة') ?></th>
                            <th><?= t('Reference', 'المرجع') ?></th>
                            <th><?= t('User', 'المستخدم') ?></th>
                            <th style="text-align:right;">
                                <?= t('In', 'داخل') ?>
                            </th>
                            <th style="text-align:right;">
                                <?= t('Out', 'خارج') ?>
                            </th>
                            <th style="text-align:right;">
                                <?= t('Balance', 'الرصيد') ?>
                            </th>
                        </tr>
                    </thead>

                    <tbody id="smBody">
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <?= t(
                                    'Select a product and press Search.',
                                    'اختر مادة واضغط بحث.'
                                ) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
let stockMovementData = null;
let stockMovementSearchTimer = null;
let stockMovementSearchResults = [];
let stockMovementSearchIndex = -1;

function isArabicSM() {
    return document.documentElement.lang === 'ar';
}

function smText(en, ar) {
    return isArabicSM() ? ar : en;
}

function escapeSM(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function movementLabel(type) {
    const labels = {
        sale: ['Sale', 'بيع'],
        purchase: ['Purchase', 'شراء'],
        return: ['Return', 'مرتجع'],
        transfer_out: ['Transfer Out', 'مناقلة — خروج'],
        transfer_in: ['Transfer In', 'مناقلة — دخول']
    };

    if (!labels[type]) return type;
    return smText(labels[type][0], labels[type][1]);
}

function movementClass(type) {
    if (type === 'sale') return 'movement-sale';
    if (type === 'purchase') return 'movement-purchase';
    if (type === 'return') return 'movement-return';
    return 'movement-transfer';
}

/*
 * Live product search.
 *
 * Existing endpoint returns:
 * {
 *   success: true,
 *   data: [...]
 * }
 *
 * It searches name, barcode, barcode2 and barcode3 and returns
 * up to 20 active products for the current device.
 */
function searchStockMovementProducts(search) {

    clearTimeout(stockMovementSearchTimer);

    const results = document.getElementById('smProductResults');

    search = search.trim();
    stockMovementSearchIndex = -1;

    if (search.length < 2) {
        results.innerHTML = '';
        results.style.display = 'none';
        stockMovementSearchResults = [];
        return;
    }

    results.innerHTML = `
        <div style="padding:12px;text-align:center;color:#888;">
            <i class="fas fa-spinner fa-spin"></i>
            ${escapeSM(smText('Searching...', 'جاري البحث...'))}
        </div>
    `;

    results.style.display = 'block';

    stockMovementSearchTimer = setTimeout(() => {

        fetch(
            `?ajax=1&action=search_products_for_transfer&search=${
                encodeURIComponent(search)
            }`
        )
        .then(response => response.json())
        .then(data => {

            if (!data.success || !Array.isArray(data.data) || data.data.length === 0) {

                stockMovementSearchResults = [];

                results.innerHTML = `
                    <div style="padding:12px;color:#888;text-align:center;">
                        ${escapeSM(smText(
                            'No products found.',
                            'لم يتم العثور على مادة.'
                        ))}
                    </div>
                `;

                return;
            }

            stockMovementSearchResults = data.data;

            results.innerHTML = data.data.map((product, index) => {

                const barcode = product.barcode || '';

                return `
                    <div
                        class="sm-product-result"
                        data-index="${index}"
                        onclick="selectStockMovementProductByIndex(${index})"
                        onmouseover="stockMovementHighlightResult(${index})"
                    >
                        <div class="sm-product-name">
                            ${escapeSM(product.name)}
                        </div>

                        <div class="sm-product-code">
                            ${
                                barcode
                                    ? '<i class="fas fa-barcode"></i> ' +
                                      escapeSM(barcode)
                                    : ''
                            }
                        </div>
                    </div>
                `;
            }).join('');

        })
        .catch(error => {

            stockMovementSearchResults = [];

            results.innerHTML = `
                <div style="padding:12px;color:#dc3545;">
                    ${escapeSM(error.message)}
                </div>
            `;

        });

    }, 180);
}

/*
 * Keyboard navigation in the live-search dropdown:
 * Arrow Up / Arrow Down / Enter / Escape
 */
function handleStockMovementSearchKey(event) {

    const results = document.getElementById('smProductResults');

    if (event.key === 'ArrowDown') {
        if (!stockMovementSearchResults.length) return;

        event.preventDefault();

        stockMovementSearchIndex =
            Math.min(
                stockMovementSearchIndex + 1,
                stockMovementSearchResults.length - 1
            );

        stockMovementHighlightResult(stockMovementSearchIndex);
        return;
    }

    if (event.key === 'ArrowUp') {
        if (!stockMovementSearchResults.length) return;

        event.preventDefault();

        stockMovementSearchIndex =
            Math.max(stockMovementSearchIndex - 1, 0);

        stockMovementHighlightResult(stockMovementSearchIndex);
        return;
    }

    if (event.key === 'Enter') {

        if (
            stockMovementSearchIndex >= 0 &&
            stockMovementSearchResults[stockMovementSearchIndex]
        ) {
            event.preventDefault();
            selectStockMovementProductByIndex(stockMovementSearchIndex);
            return;
        }

        /*
         * If the typed value is an exact barcode/code, try the barcode
         * endpoint as a convenience.
         */
        const value = document
            .getElementById('smProductSearch')
            .value
            .trim();

        if (value) {
            event.preventDefault();
            findStockMovementBarcode(value);
        }

        return;
    }

    if (event.key === 'Escape') {
        results.style.display = 'none';
        stockMovementSearchIndex = -1;
    }
}

function stockMovementHighlightResult(index) {

    const items = document.querySelectorAll('.sm-product-result');

    items.forEach(item => {
        item.style.background = '#fff';
    });

    const selected = items[index];

    if (selected) {
        selected.style.background = '#f0f0f0';
        selected.scrollIntoView({block: 'nearest'});
    }
}

function selectStockMovementProductByIndex(index) {

    const product = stockMovementSearchResults[index];

    if (!product) return;

    selectStockMovementProduct(
        product.id,
        product.name,
        product.barcode || ''
    );
}

function selectStockMovementProduct(id, name, barcode) {

    document.getElementById('smProduct').value = id;
    document.getElementById('smProductSearch').value = name;

    document.getElementById('smProductResults').style.display = 'none';
    stockMovementSearchResults = [];
    stockMovementSearchIndex = -1;

    document.getElementById('smSelectedProduct').innerHTML =
        '<i class="fas fa-check-circle"></i> ' +
        escapeSM(smText('Selected: ', 'تم اختيار: ')) +
        '<strong>' + escapeSM(name) + '</strong>';

    if (barcode) {
        document.getElementById('smBarcodeScanner').value = barcode;
    }

    /*
     * Do NOT automatically load here.
     * The user can change the date range first, then press Search.
     */
}

/*
 * Barcode scanner support.
 *
 * Most USB scanners act as a keyboard and send the barcode followed
 * by Enter. Therefore, putting the cursor in the barcode field and
 * scanning is enough.
 */
function handleStockMovementBarcode(event) {

    if (event.key !== 'Enter') {
        return;
    }

    event.preventDefault();

    const barcode = document
        .getElementById('smBarcodeScanner')
        .value
        .trim();

    if (!barcode) return;

    findStockMovementBarcode(barcode);
}

function findStockMovementBarcode(barcode) {

    const searchInput = document.getElementById('smBarcodeScanner');

    searchInput.disabled = true;

    fetch(
        `?ajax=1&action=get_product_by_barcode&barcode=${
            encodeURIComponent(barcode)
        }`
    )
    .then(response => response.json())
    .then(data => {

        if (!data.success || !data.data) {

            alert(smText(
                'No product found with this barcode.',
                'لم يتم العثور على مادة بهذا الباركود.'
            ));

            return;
        }

        const product = data.data;

        document.getElementById('smProduct').value = product.id;
        document.getElementById('smProductSearch').value = product.name;

        document.getElementById('smProductResults').style.display = 'none';

        document.getElementById('smSelectedProduct').innerHTML =
            '<i class="fas fa-check-circle"></i> ' +
            escapeSM(smText('Selected: ', 'تم اختيار: ')) +
            '<strong>' + escapeSM(product.name) + '</strong>';

        /*
         * Keep the scanned value in the field.
         * This is useful when verifying what was scanned.
         */
        searchInput.value = barcode;

        /*
         * Automatically run the report after a successful scan.
         * This is the fast workflow needed at the counter.
         */
        loadStockMovement();

    })
    .catch(error => {

        alert(
            smText('Error: ', 'حدث خطأ: ') +
            error.message
        );

    })
    .finally(() => {

        searchInput.disabled = false;
        searchInput.focus();

    });
}

function loadStockMovement() {

    const productId = document.getElementById('smProduct').value;
    const from = document.getElementById('smFrom').value;
    const to = document.getElementById('smTo').value;

    if (!productId) {

        alert(smText(
            'Please select a product first.',
            'يرجى اختيار المادة أولاً.'
        ));

        document.getElementById('smProductSearch').focus();
        return;
    }

    if (!from || !to || from > to) {

        alert(smText(
            'Please check the selected date range.',
            'يرجى التأكد من صحة الفترة الزمنية.'
        ));

        return;
    }

    const body = document.getElementById('smBody');

    body.innerHTML = `
        <tr>
            <td colspan="7" class="text-center text-muted">
                <i class="fas fa-spinner fa-spin"></i>
                ${escapeSM(smText('Loading...', 'جاري التحميل...'))}
            </td>
        </tr>
    `;

    fetch(
        `?ajax=1&action=get_stock_movement` +
        `&product_id=${encodeURIComponent(productId)}` +
        `&from_date=${encodeURIComponent(from)}` +
        `&to_date=${encodeURIComponent(to)}`
    )
    .then(response => response.json())
    .then(data => {

        if (!data.success) {
            throw new Error(data.message || 'Failed to load report');
        }

        stockMovementData = data;

        document.getElementById('smProductInfo').style.display = 'block';

        document.getElementById('smProductName').textContent =
            data.product.name;

        const codes = [];

        if (data.product.barcode) {
            codes.push(data.product.barcode);
        }

        if (data.product.barcode2) {
            codes.push(data.product.barcode2);
        }

        if (data.product.barcode3) {
            codes.push(data.product.barcode3);
        }

        document.getElementById('smProductCode').textContent =
            codes.length ? codes.join(' | ') : '';

        document.getElementById('smCurrentStock').textContent =
            data.current_stock;

        document.getElementById('smOpening').textContent =
            data.opening_balance;

        document.getElementById('smIn').textContent =
            data.total_in;

        document.getElementById('smOut').textContent =
            data.total_out;

        document.getElementById('smClosing').textContent =
            data.closing_balance;

        document.getElementById('smPeriod').textContent =
            ` — ${from} → ${to}`;

        const warning = document.getElementById('smWarning');

        warning.style.display = 'block';

        warning.textContent = smText(
            'Note: the current system does not store historical timestamps for direct stock adjustments or manual stock edits. Historical balances are therefore reconstructed from current stock and recorded transactions.',
            'ملاحظة: النظام الحالي لا يحتفظ بتاريخ تعديلات الجرد المباشرة أو تغيير المخزون من شاشة المنتج. لذلك تم احتساب الأرصدة التاريخية انطلاقاً من المخزون الحالي والحركات المسجلة.'
        );

        renderStockMovement(data.events || []);

    })
    .catch(error => {

        body.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-danger">
                    ${escapeSM(error.message)}
                </td>
            </tr>
        `;

    });
}

function renderStockMovement(events) {

    const body = document.getElementById('smBody');

    if (!events || events.length === 0) {

        body.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-muted">
                    ${escapeSM(smText(
                        'No movements in this period.',
                        'لا توجد حركات ضمن الفترة.'
                    ))}
                </td>
            </tr>
        `;

        return;
    }

    let html = '';

    events.forEach(event => {

        const inQty = parseInt(event.qty_in || 0, 10);
        const outQty = parseInt(event.qty_out || 0, 10);

        html += `
            <tr>
                <td>${escapeSM(event.movement_date)}</td>

                <td>
                    <span class="movement-badge ${
                        movementClass(event.movement_type)
                    }">
                        ${escapeSM(
                            movementLabel(event.movement_type)
                        )}
                    </span>
                </td>

                <td>
                    <strong>
                        ${escapeSM(event.reference_no || '')}
                    </strong>
                </td>

                <td>
                    ${escapeSM(event.user_name || '')}
                </td>

                <td style="text-align:right;" class="movement-in">
                    ${inQty ? '+' + inQty : ''}
                </td>

                <td style="text-align:right;" class="movement-out">
                    ${outQty ? '-' + outQty : ''}
                </td>

                <td style="text-align:right;font-weight:700;">
                    ${escapeSM(event.balance)}
                </td>
            </tr>
        `;
    });

    body.innerHTML = html;
}

function exportStockMovementCSV() {

    if (!stockMovementData) {

        alert(smText(
            'Load the report first.',
            'قم بعرض التقرير أولاً.'
        ));

        return;
    }

    const data = stockMovementData;

    const rows = [
        ['Stock Movement'],
        ['Product', data.product.name],
        ['From', data.from_date],
        ['To', data.to_date],
        ['Opening Balance', data.opening_balance],
        ['Total In', data.total_in],
        ['Total Out', data.total_out],
        ['Closing Balance', data.closing_balance],
        [],
        ['Date', 'Movement', 'Reference', 'User', 'In', 'Out', 'Balance']
    ];

    (data.events || []).forEach(event => {

        rows.push([
            event.movement_date,
            movementLabel(event.movement_type),
            event.reference_no || '',
            event.user_name || '',
            event.qty_in || 0,
            event.qty_out || 0,
            event.balance
        ]);

    });

    const csv = rows.map(row => {

        return row.map(value => {

            const valueString = String(value ?? '');

            return '"' +
                valueString.replace(/"/g, '""') +
                '"';

        }).join(',');

    }).join('\r\n');

    const blob = new Blob(
        ["\uFEFF" + csv],
        {type:'text/csv;charset=utf-8;'}
    );

    const url = URL.createObjectURL(blob);

    const link = document.createElement('a');

    link.href = url;

    link.download =
        `stock_movement_${data.product.id}_${data.from_date}_${data.to_date}.csv`;

    document.body.appendChild(link);
    link.click();
    link.remove();

    URL.revokeObjectURL(url);
}

/*
 * Clicking anywhere outside the product search closes its dropdown.
 */
document.addEventListener('click', function(event) {

    const picker = document.querySelector('.sm-product-picker');

    if (!picker || !picker.contains(event.target)) {
        document.getElementById('smProductResults').style.display = 'none';
    }

});

/*
 * When this page opens, put the cursor in the product search box.
 */
setTimeout(function() {
    const input = document.getElementById('smProductSearch');
    if (input) input.focus();
}, 150);
</script>
