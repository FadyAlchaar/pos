<?php
$products = getAllProducts('', 10, 0);
$categories = getAllCategories();
$autoPrint = getSetting('auto_print') ?? '1'; // Default: enabled
?>

<div class="pos-container">
    <!-- ===== LEFT: Invoice Header ===== -->
    <div class="pos-left">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-receipt"></i> <?= __('new_invoice') ?></h5>
            </div>
            <div class="card-body">
                <!-- Customer Selection -->
                <div class="form-group">
                    <label><i class="fas fa-user"></i> <?= __('customer') ?></label>
                    <select id="customerSelect" class="form-control" onchange="updateCustomer()">
                        <option value=""><?= __('walk_in_customer') ?></option>
                        <?php foreach (getAllCustomers() as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Barcode Scanner -->
                <div class="form-group">
                    <label><i class="fas fa-barcode"></i> <?= __('scan_barcode') ?></label>
                    <div class="d-flex gap-2">
                        <div class="input-clear-wrapper" style="flex:1;">
                            <input type="text" id="barcodeScanner" class="form-control" 
                                placeholder="<?= __('Scan or type barcode...') ?>" 
                                inputmode="none" autocomplete="off"
                                style="font-size: 16px; height: 45px; padding-right: 35px;"
                                oninput="toggleClearButton(this)">
                            <button type="button" class="clear-btn" onclick="clearInput(this)">✕</button>
                        </div>
                        <button class="btn btn-primary" onclick="manualScan()" title="<?= __('search') ?>">
                            <i class="fas fa-search"></i>
                        </button>
                        <button class="btn btn-outline" id="cameraScanBtn" onclick="requestCameraScan()" title="<?= __('scan_with_camera') ?>" style="display:none; padding: 0 12px;">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                </div>

                <!-- Quick Product Search -->
                <div class="form-group">
                    <label><i class="fas fa-search"></i> <?= __('search_products') ?></label>
                    <div class="input-clear-wrapper">
                        <input type="text" id="quickSearch" class="form-control" 
                            placeholder="<?= __('Type product name...') ?>" 
                            oninput="toggleClearButton(this)" 
                            onkeyup="searchProducts(this.value)">
                        <button type="button" class="clear-btn" onclick="clearInput(this)">✕</button>
                    </div>
                    <div id="quickSearchResults" style="max-height: 200px; overflow-y: auto; display: none; border: 1px solid #e9ecef; border-radius: 8px; margin-top: 5px; background: #fff;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== RIGHT: Invoice Body ===== -->
    <div class="pos-right">
        <div class="card" style="height: 100%; display: flex; flex-direction: column;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> <?= __('cart') ?></h5>
                <button class="btn btn-sm btn-danger" onclick="clearCart()">
                    <i class="fas fa-trash"></i> <?= __('clear_cart') ?>
                </button>
            </div>
            
            <!-- Cart Items -->
            <div class="card-body" style="flex: 1; overflow-y: auto; padding: 15px; max-height: 250px;">
                <div id="cartItems">
                    <p class="text-muted text-center" style="padding: 40px 0;">
                        <i class="fas fa-cart-plus" style="font-size: 48px; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                        <?= __('cart_empty') ?>
                    </p>
                </div>
            </div>

            <!-- Cart Summary -->
            <div style="padding: 15px 20px; border-top: 1px solid rgba(0,0,0,0.05); background: #f8fafc;">
                <div class="row">
                    <div class="col-7">
                        <div class="d-flex justify-content-between mb-2">
                            <span><?= __('subtotal') ?></span>
                            <span id="cartSubtotal"><?= formatPrice(0) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><?= __('item_discounts') ?></span>
                            <span id="cartItemDiscounts"><?= formatPrice(0) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><?= __('global_discount') ?></span>
                            <span id="cartGlobalDiscount"><?= formatPrice(0) ?></span>
                        </div>
                        <div class="d-flex justify-content-between" style="font-weight: 700; font-size: 18px;">
                            <span><?= __('total') ?></span>
                            <span id="cartTotal" style="color: var(--primary);"><?= formatPrice(0) ?></span>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="d-flex gap-2">
                            <input type="number" id="discountInput" class="form-control" placeholder="Global" style="width: 100px;" onchange="updateCart()">
                            <button class="btn btn-success" style="flex: 1;" onclick="checkout()">
                                <i class="fas fa-check"></i> <?= __('checkout') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pos-container {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 20px;
    height: calc(100vh - 160px);
}
.pos-right .card {
    height: 100%;
}
.cart-item {
    display: flex;
    flex-direction: column;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}
.cart-item .cart-item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cart-item .cart-item-info {
    flex: 1;
}
.cart-item .cart-item-name {
    font-weight: 600;
    font-size: 14px;
}
.cart-item .cart-item-price {
    font-size: 13px;
    color: var(--gray);
}
.cart-item .cart-item-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}
.cart-item .cart-item-actions input[type="number"] {
    width: 50px;
    text-align: center;
    padding: 2px 4px;
    border: 1px solid #ddd;
    border-radius: 4px;
}
.cart-item .item-discount-input {
    width: 60px;
    padding: 2px 4px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 12px;
    text-align: center;
}
#quickSearchResults .search-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s;
}
#quickSearchResults .search-item:hover {
    background: #f0f0f0;
}
#quickSearchResults .search-item .item-name {
    font-weight: 600;
}
#quickSearchResults .search-item .item-price {
    color: var(--primary);
    float: right;
}
@media (max-width: 992px) {
    .pos-container {
        grid-template-columns: 1fr;
        height: auto;
    }
    .pos-right .card {
        height: auto !important;
        max-height: 500px;
    }
}
</style>

<script>
let cart = [];
const csrfToken = '<?= generateCSRFToken() ?>';
const autoPrintEnabled = <?= $autoPrint === '1' ? 'true' : 'false' ?>;

// ============================================
// QUICK SEARCH
// ============================================
function searchProducts(search) {
    const results = document.getElementById('quickSearchResults');
    if (!search || search.length < 2) {
        results.style.display = 'none';
        return;
    }
    fetch(`?ajax=1&action=get_products_paginated&page=1&limit=20&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data.products.length > 0) {
                let html = '';
                data.data.products.forEach(p => {
                    html += `
                        <div class="search-item" onclick="addToCartFromSearch(${p.id}, '${escapeHtml(p.name)}', ${p.price}, ${p.stock}, '${escapeHtml(p.unit_name || '')}')">
                            <span class="item-name">${escapeHtml(p.name)}</span>
                            <span class="item-price">${formatPrice(p.price)}</span>
                            <small class="text-muted">Stock: ${p.stock}${p.unit_name ? ' ' + escapeHtml(p.unit_name) : ''}</small>
                        </div>
                    `;
                });
                results.innerHTML = html;
                results.style.display = 'block';
            } else {
                results.innerHTML = '<div class="search-item text-muted">No products found.</div>';
                results.style.display = 'block';
            }
        });
}
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
function addToCartFromSearch(id, name, price, stock, unitName) {
    addToCart(id, name, price, stock, unitName);
    document.getElementById('quickSearch').value = '';
    document.getElementById('quickSearchResults').style.display = 'none';
}

// ============================================
// BARCODE SCANNER
// ============================================
const scannerInput = document.getElementById('barcodeScanner');
function focusScanner() { setTimeout(() => { if(scannerInput){ scannerInput.focus(); scannerInput.select(); } }, 100); }
document.addEventListener('DOMContentLoaded', focusScanner);
scannerInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const barcode = this.value.trim();
        if (barcode) {
            processBarcode(barcode);
            this.value = '';
        }
        setTimeout(focusScanner, 100);
    }
});
function processBarcode(barcode) {
    fetch(`?ajax=1&action=get_product_by_barcode&barcode=${encodeURIComponent(barcode)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const p = data.data;
                addToCart(p.id, p.name, p.price, p.stock, p.unit_name);
                if (navigator.vibrate) navigator.vibrate(50);
            } else {
                alert('<?= __('product_not_found') ?>: ' + barcode);
                if (navigator.vibrate) navigator.vibrate([100,50,100]);
            }
        })
        .catch(err => alert('<?= __('error_unknown') ?>: ' + err.message));
}
function manualScan() {
    const barcode = scannerInput.value.trim();
    if (barcode) {
        processBarcode(barcode);
        scannerInput.value = '';
    } else {
        alert('<?= __('please_scan_barcode') ?>');
    }
    focusScanner();
}

// ============================================
// CAMERA SCAN (via the native WebViewShellScan bridge)
// Only shown when running inside the shell app — on a plain desktop/mobile
// browser there's no window.Android bridge, so the button stays hidden
// rather than offering a scan option that can't actually work there.
// ============================================
function hasNativeScanner() {
    return (typeof Android !== 'undefined') && (typeof Android.startScan === 'function');
}

function requestCameraScan() {
    if (hasNativeScanner()) {
        Android.startScan();
    }
}

// Called by the shell app (MainActivity.deliverScannedCode) after a
// successful native scan. Feeds straight into the same path as typing
// a barcode or using a hardware scanner.
window.onBarcodeScanned = function(code) {
    if (!code) return;
    if (navigator.vibrate) navigator.vibrate(50);
    processBarcode(code);
};

document.addEventListener('DOMContentLoaded', function() {
    if (hasNativeScanner()) {
        document.getElementById('cameraScanBtn').style.display = 'inline-flex';
    }
});

// ADD TO CART
// ============================================
function addToCart(id, name, price, stock, unitName) {
    id = parseInt(id);
    price = parseFloat(price);
    stock = parseInt(stock);
    if (isNaN(id) || isNaN(price) || isNaN(stock)) {
        console.error('Invalid product data:', {id, name, price, stock});
        return;
    }
    if (stock <= 0) {
        alert('<?= __('out_of_stock') ?>');
        return;
    }
    const existing = cart.find(item => item.id === id);
    if (existing) {
        if (existing.qty >= stock) {
            alert('<?= __('not_enough_stock') ?>');
            return;
        }
        existing.qty++;
    } else {
        cart.push({ id, name, price, qty: 1, max_stock: stock, discount: 0, unit_name: unitName || '' });
    }
    updateCartDisplay();
}

// ============================================
// CART FUNCTIONS
// ============================================
function updateItemDiscount(index, value) {
    const discount = parseFloat(value);
    if (isNaN(discount) || discount < 0) {
        alert('<?= __('invalid_discount') ?>');
        return;
    }
    cart[index].discount = discount;
    updateCartDisplay();
}

function updateCartDisplay() {
    const container = document.getElementById('cartItems');
    const subtotalEl = document.getElementById('cartSubtotal');
    const itemDiscountsEl = document.getElementById('cartItemDiscounts');
    const globalDiscountEl = document.getElementById('cartGlobalDiscount');
    const totalEl = document.getElementById('cartTotal');

    if (cart.length === 0) {
        container.innerHTML = `<p class="text-muted text-center" style="padding:40px 0;">
            <i class="fas fa-cart-plus" style="font-size:48px;display:block;margin-bottom:10px;opacity:0.3;"></i>
            <?= __('cart_empty') ?>
        </p>`;
        subtotalEl.textContent = formatPrice(0);
        itemDiscountsEl.textContent = formatPrice(0);
        globalDiscountEl.textContent = formatPrice(0);
        totalEl.textContent = formatPrice(0);
        return;
    }

    let html = '';
    let subtotal = 0;
    let totalItemDiscount = 0;

    cart.forEach((item, index) => {
        const lineTotal = item.price * item.qty;
        subtotal += lineTotal;
        totalItemDiscount += (item.discount || 0);

        html += `
            <div class="cart-item">
                <div class="cart-item-row">
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">${formatPrice(item.price)} x ${item.qty}${item.unit_name ? ' ' + escapeHtml(item.unit_name) : ''}</div>
                    </div>
                    <div class="cart-item-actions">
                        <button class="btn btn-sm btn-outline" onclick="changeQty(${index}, -1)">-</button>
                        <input type="number" value="${item.qty}" min="1" max="${item.max_stock}" 
                               onchange="setQty(${index}, this.value)" style="width:50px;text-align:center;padding:2px 4px;border:1px solid #ddd;border-radius:4px;">
                        <button class="btn btn-sm btn-outline" onclick="changeQty(${index}, 1)">+</button>
                        <button class="btn btn-sm btn-danger" onclick="removeItem(${index})"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="cart-item-row" style="margin-top:4px;">
                    <span style="font-size:12px;color:var(--gray);">Discount:</span>
                    <input type="number" class="item-discount-input" value="${item.discount || 0}" 
                           min="0" step="0.01"
                           onchange="updateItemDiscount(${index}, this.value)" 
                           style="width:70px;padding:2px 4px;border:1px solid #ddd;border-radius:4px;font-size:12px;text-align:center;">
                    <span style="font-size:12px;">Line Total: <strong>${formatPrice((lineTotal - (item.discount || 0)))}</strong></span>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;

    // Global discount
    const globalDiscount = parseFloat(document.getElementById('discountInput').value) || 0;
    const totalAfterItemDiscounts = subtotal - totalItemDiscount;
    const finalTotal = totalAfterItemDiscounts - globalDiscount;

    subtotalEl.textContent = formatPrice(subtotal);
    itemDiscountsEl.textContent = formatPrice(totalItemDiscount);
    globalDiscountEl.textContent =  formatPrice(globalDiscount);
    totalEl.textContent =  formatPrice(finalTotal);
}

function changeQty(index, delta) {
    const item = cart[index];
    if (!item) return;
    const newQty = item.qty + delta;
    if (newQty < 1) { removeItem(index); return; }
    if (newQty > item.max_stock) { alert('<?= __('not_enough_stock') ?>'); return; }
    item.qty = newQty;
    updateCartDisplay();
}
function setQty(index, value) {
    const qty = parseInt(value);
    if (isNaN(qty) || qty < 1) { removeItem(index); return; }
    const item = cart[index];
    if (!item) return;
    if (qty > item.max_stock) { alert('<?= __('not_enough_stock') ?>'); return; }
    item.qty = qty;
    updateCartDisplay();
}
function removeItem(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}
function clearCart() {
    if (!confirm('<?= __('confirm_clear_cart') ?>')) return;
    cart = [];
    updateCartDisplay();
}
function updateCart() {
    updateCartDisplay();
}

// ============================================
// CHECKOUT (FIXED)
// ============================================
function checkout() {
    if (cart.length === 0) {
        alert('<?= __('cart_empty') ?>');
        return;
    }

    let subtotal = 0;
    let totalItemDiscount = 0;
    cart.forEach(item => {
        subtotal += item.price * item.qty;
        totalItemDiscount += (item.discount || 0);
    });
    const globalDiscount = parseFloat(document.getElementById('discountInput').value) || 0;
    const finalTotal = subtotal - totalItemDiscount - globalDiscount;

    if (!confirm('<?= __('confirm_checkout') ?>')) return;

    const btn = event.target;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    btn.disabled = true;

    const data = {
        items: cart.map(item => ({
            product_id: item.id,
            quantity: item.qty,
            price: item.price,
            discount: item.discount || 0,
            total: (item.price * item.qty) - (item.discount || 0)
        })),
        customer_id: document.getElementById('customerSelect').value || null,
        subtotal: subtotal,
        discount: globalDiscount,
        total: finalTotal,
        payment_method: 'cash',
        csrf_token: csrfToken
    };

    fetch('?ajax=1&action=create_sale', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            alert('<?= __('sale_completed') ?> ' + result.invoice_no);

            // ==========================================
            // AUTO-PRINT LOGIC
            // ==========================================
            if (autoPrintEnabled) {
                // Auto-print without asking
                printReceiptFromServer(result.sale_id, 'usb');
            } else {
                // Ask the user if they want to print
                const printNow = confirm('<?= __('print_receipt_question') ?>');
                if (printNow) {
                    printReceiptFromServer(result.sale_id, 'usb');
                }
            }

            cart = [];
            updateCartDisplay();
            document.getElementById('discountInput').value = '';
            location.reload();
        } else {
            alert('<?= __('error_unknown') ?>: ' + (result.message || '<?= __('error_unknown') ?>'));
        }
    })
    .catch(err => {
        alert('<?= __('error_unknown') ?>: Network error: ' + err.message);
    })
    .finally(() => {
        btn.innerHTML = '<i class="fas fa-check"></i> <?= __('checkout') ?>';
        btn.disabled = false;
    });
}

// ============================================
// FOCUS FALLBACK
// ============================================
setInterval(function() {
    const scanner = document.getElementById('barcodeScanner');
    if (scanner && document.activeElement !== scanner) {
        const active = document.activeElement;
        if (!active || !['INPUT','SELECT','TEXTAREA'].includes(active.tagName)) {
            scanner.focus();
        }
    }
}, 3000);

// ============================================
// PRINT RECEIPT FROM SERVER
// ============================================
function printReceiptFromServer(saleId, method = 'normal') {
    const formData = new FormData();
    formData.append('id', saleId);
    formData.append('method', method);
    formData.append('csrf_token', csrfToken);

    fetch('?ajax=1&action=print_receipt', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert('❌ ' + (data.message || 'PDF generation failed.'));
            return;
        }

        // Physical printing was attempted via the TextPrinter.exe bridge.
        if (data.printed === true) {
            // Printed successfully — no need to also pop up the PDF viewer.
            return;
        }
        if (data.printed === false) {
            // Bridge is configured but the print failed — show the real
            // error (wrong printer name, printer offline, etc.) rather than
            // failing silently, then fall through to the PDF fallback below.
            alert('⚠️ ' + '<?= __('print_failed_opening_pdf') ?>' + '\n\n' + (data.print_message || 'Unknown printer error.'));
        }
        // data.printed === null means no bridge is configured at all —
        // same PDF-popup behavior as before, unchanged.

        if (data.pdf_base64) {
            // Try to open in new window
            const win = window.open('', '_blank');
            
            if (!win) {
                // Popup blocked — fallback to download
                const link = document.createElement('a');
                link.href = 'data:application/pdf;base64,' + data.pdf_base64;
                link.download = 'receipt_' + Date.now() + '.pdf';
                document.body.appendChild(link);
                link.click();
                link.remove();
                alert('✅ PDF downloaded. Please open and print it manually.');
                return;
            }
            
            // Write the PDF viewer HTML
            win.document.write(`
                <html>
                    <head>
                        <title>Receipt</title>
                        <style>
                            body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f0f0f0; flex-direction: column; }
                            embed { width: 100%; height: 100%; border: none; }
                            .toolbar { position: fixed; top: 10px; right: 20px; z-index: 1000; display: flex; gap: 8px; }
                            .toolbar button { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: bold; }
                            .btn-print { background: #2ecc71; color: #fff; }
                            .btn-print:hover { background: #27ae60; }
                            .btn-close { background: #e74c3c; color: #fff; }
                            .btn-close:hover { background: #c0392b; }
                        </style>
                    </head>
                    <body>
                        <div class="toolbar">
                            <button class="btn-print" onclick="document.querySelector('embed').print()">🖨️ Print</button>
                            <button class="btn-close" onclick="window.close()">✕ Close</button>
                        </div>
                        <embed width="100%" height="100%" src="data:application/pdf;base64,${data.pdf_base64}" type="application/pdf">
                        <script>
                            // Optional: auto-print after a short delay (uncomment to enable)
                            // setTimeout(() => { document.querySelector('embed').print(); }, 500);
                        <\/script>
                    </body>
                </html>
            `);
            win.document.close();
        }
    })
    .catch(err => {
        alert('Error: ' + err.message);
    });
}

// ============================================
// CLEAR INPUT
// ============================================
function clearInput(btn) {
    const input = btn.closest('.input-clear-wrapper').querySelector('input');
    input.value = '';
    input.focus();
    btn.classList.remove('show');
    if (input.id === 'barcodeScanner') {
        input.focus();
        input.select();
    }
}
</script>