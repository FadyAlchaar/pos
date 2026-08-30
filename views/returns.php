<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-weight: 700; color: var(--dark);"><?= __('returns_refunds') ?></h4>
</div>

<div class="card fade-in">
    <div class="card-body">
        <ul class="nav nav-tabs" id="returnTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="invoice-tab" data-bs-toggle="tab" data-bs-target="#invoice-return" type="button" role="tab"><?= __('return_by_invoice') ?></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="walkin-tab" data-bs-toggle="tab" data-bs-target="#walkin-return" type="button" role="tab"><?= __('walkin_return') ?></button>
            </li>
        </ul>
        
        <div class="tab-content mt-3" id="returnTabContent">
            <!-- ===== INVOICE RETURN ===== -->
            <div class="tab-pane fade show active" id="invoice-return" role="tabpanel">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <?= __('enter_invoice_number') ?>
                </div>
                <form id="invoiceReturnForm" onsubmit="searchSaleForReturn(event)">
                    <div class="d-flex gap-3 align-items-end flex-wrap">
                        <div style="flex:1; min-width:200px;">
                            <label><i class="fas fa-receipt"></i> <?= __('invoice_number') ?></label>
                            <input type="text" id="returnInvoiceSearch" class="form-control" placeholder="e.g. INV-20250101-1234" required>
                        </div>
                        <div>
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> <?= __('find_invoice') ?>
                            </button>
                        </div>
                    </div>
                </form>
                <div id="returnResults" style="display:none; margin-top:20px;"></div>
            </div>
            
            <!-- ===== WALK-IN RETURN ===== -->
            <div class="tab-pane fade" id="walkin-return" role="tabpanel">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <?= __('search_products_return') ?>
                </div>
                <div class="d-flex gap-3 align-items-end flex-wrap">
                    <div style="flex:1; min-width:200px;">
                        <label><i class="fas fa-search"></i> <?= __('search_product') ?></label>
                        <input type="text" id="walkinSearch" class="form-control" placeholder="Type product name or barcode..." onkeyup="searchWalkinProducts(this.value)">
                    </div>
                    <div>
                        <label>&nbsp;</label>
                        <button class="btn btn-outline" onclick="clearWalkinSearch()">Clear</button>
                    </div>
                </div>
                <div id="walkinResults" style="margin-top:20px;"></div>
                <div id="walkinReturnForm" style="display:none; margin-top:20px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
// ============================================
// AUTO-LOAD INVOICE FROM URL PARAMETER
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const invoice = urlParams.get('invoice');
    if (invoice) {
        document.getElementById('returnInvoiceSearch').value = invoice;
        // Switch to invoice tab
        document.getElementById('invoice-tab').click();
        // Trigger search
        document.getElementById('invoiceReturnForm').dispatchEvent(new Event('submit'));
    }
});

// ============================================
// INVOICE RETURN
// ============================================
function searchSaleForReturn(e) {
    e.preventDefault();
    const invoice = document.getElementById('returnInvoiceSearch').value.trim();
    if (!invoice) {
        alert('<?= __('please_enter_invoice_number') ?>');
        return;
    }
    
    fetch(`?ajax=1&action=get_sales&search=${encodeURIComponent(invoice)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                const sale = data.data.find(s => s.invoice_no === invoice);
                if (sale) {
                    loadReturnableSale(sale.id);
                } else {
                    showReturnError('Invoice not found.');
                }
            } else {
                showReturnError('Invoice not found.');
            }
        });
}

function showReturnError(msg) {
    const container = document.getElementById('returnResults');
    container.style.display = 'block';
    container.innerHTML = `<div class="alert alert-warning">${msg}</div>`;
}

function loadReturnableSale(saleId) {
    fetch(`?ajax=1&action=get_sale_for_return&id=${saleId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderReturnForm(data.data);
            } else {
                showReturnError(data.message);
            }
        });
}

function renderReturnForm(sale) {
    const container = document.getElementById('returnResults');
    container.style.display = 'block';
    
    let itemsHtml = '';
    let items = sale.returnable_items || [];
    
    if (items.length === 0) {
        container.innerHTML = `<div class="alert alert-success"><?= __('all_items_returned') ?></div>`;
        return;
    }
    
    items.forEach(item => {
        const maxQty = item.quantity - (item.returned_qty || 0);
        itemsHtml += `
            <div class="return-item" style="display:flex;align-items:center;gap:10px;padding:10px;border-bottom:1px solid #e9ecef;">
                <input type="checkbox" class="return-item-checkbox" data-sale-item-id="${item.id}" data-product-id="${item.product_id}" data-price="${item.price}" data-max-qty="${maxQty}" onchange="toggleReturnItem(this)">
                <span style="flex:1;"><strong>${item.product_name}</strong>${item.unit_name ? ' <small class="text-muted">(' + item.unit_name + ')</small>' : ''}</span>
                <span><?= __('quantity') ?>: <input type="number" class="return-item-qty" value="${maxQty}" min="1" max="${maxQty}" style="width:60px;text-align:center;" disabled></span>
                <span><?= __('price') ?>: ${formatPrice(parseFloat(item.price))}</span>
                <span><?= __('Refund') ?>: ${formatPrice((item.price * maxQty))}</span>
            </div>
        `;
    });
    
    container.innerHTML = `
        <h5 class="mt-3"><?= __('return_items_invoice') ?> <strong>${sale.invoice_no}</strong></h5>
        <p class="text-muted"><?= __('select_items_return') ?></p>
        <form id="returnForm" onsubmit="submitReturn(event)">
            <input type="hidden" name="sale_id" value="${sale.id}">
            <div id="returnItemsContainer">${itemsHtml}</div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fas fa-info-circle"></i> <?= __('return_reason') ?></label>
                        <select id="returnReason" name="reason" class="form-control">
                            <option value="customer_request"><?= __('customer_request') ?></option>
                            <option value="defective"><?= __('defective') ?></option>
                            <option value="wrong_item"><?= __('wrong_item') ?></option>
                            <option value="damaged"><?= __('damaged') ?></option>
                            <option value="other"><?= __('other') ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fas fa-money-bill"></i> <?= __('refund_method') ?></label>
                        <select id="refundMethod" name="refund_method" class="form-control">
                            <option value="cash"><?= __('cash') ?></option>
                            <option value="card"><?= __('card') ?></option>
                            <option value="mobile"><?= __('mobile') ?></option>
                            <option value="store_credit"><?= __('store_credit') ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fas fa-dollar-sign"></i> <?= __('total_refund') ?></label>
                        <h3 id="totalRefundDisplay" style="color: var(--primary);"> <?= formatPrice(0) ?></h3>
                        <input type="hidden" id="totalRefund" name="<?= __('total_refund') ?>" value="0">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-sticky-note"></i> <?= __('notes') ?></label>
                <textarea id="returnNotes" name="<?= __('notes') ?>" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-success mt-3">
                <i class="fas fa-undo"></i> <?= __('process_return') ?>
            </button>
        </form>
    `;
    
    // Attach event listeners
    document.querySelectorAll('.return-item-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const qtyInput = this.closest('.return-item').querySelector('.return-item-qty');
            qtyInput.disabled = !this.checked;
            updateTotalRefund();
        });
    });
    document.querySelectorAll('.return-item-qty').forEach(qty => {
        qty.addEventListener('change', updateTotalRefund);
    });
}

function toggleReturnItem(checkbox) {
    // handled by event listeners above
}

function updateTotalRefund() {
    let total = 0;
    document.querySelectorAll('.return-item-checkbox:checked').forEach(cb => {
        const item = cb.closest('.return-item');
        const qtyInput = item.querySelector('.return-item-qty');
        const price = parseFloat(cb.dataset.price);
        const qty = parseInt(qtyInput.value) || 0;
        total += price * qty;
    });
    document.getElementById('totalRefundDisplay').textContent =  formatPrice(total);
    document.getElementById('totalRefund').value = formatPrice(total);
}

// ============================================
// WALK-IN RETURN (NO INVOICE)
// ============================================
let walkinProducts = [];
let walkinReturnItems = [];

function clearWalkinSearch() {
    document.getElementById('walkinSearch').value = '';
    document.getElementById('walkinResults').innerHTML = '';
    document.getElementById('walkinReturnForm').style.display = 'none';
    document.getElementById('walkinReturnForm').innerHTML = '';
    walkinReturnItems = [];
}

function searchWalkinProducts(search) {
    const results = document.getElementById('walkinResults');
    if (!search || search.length < 2) {
        results.innerHTML = '';
        return;
    }
    fetch(`?ajax=1&action=search_products_for_return&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                walkinProducts = data.data;
                let html = `
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Product</th><th><?= __('unit') ?></th><th><?= __('price') ?></th><th><?= __('stock') ?></th><th><?= __('quantity') ?></th><th><?= __('action') ?></th></tr></thead>
                            <tbody>
                `;
                data.data.forEach(p => {
                    html += `
                        <tr>
                            <td>${escapeHtml(p.name)}</td>
                            <td>${escapeHtml(p.unit_name || '-')}</td>
                            <td>${formatPrice(parseFloat(p.price))}</td>
                            <td>${p.stock}</td>
                            <td><input type="number" class="walkin-qty" data-id="${p.id}" value="1" min="1" max="${p.stock}" style="width:60px;"></td>
                            <td><button class="btn btn-sm btn-primary" onclick="addWalkinItem(${p.id})"><i class="fas fa-plus"></i></button></td>
                        </tr>
                    `;
                });
                html += `</tbody></table></div>`;
                results.innerHTML = html;
            } else {
                results.innerHTML = '<p class="text-muted">No products found.</p>';
            }
        });
}

function addWalkinItem(productId) {
    const qtyInput = document.querySelector(`.walkin-qty[data-id="${productId}"]`);
    if (!qtyInput) return;
    const qty = parseInt(qtyInput.value) || 1;
    const product = walkinProducts.find(p => p.id === productId);
    if (!product) return;
    if (qty > product.stock) {
        alert('Not enough stock!');
        return;
    }
    // Check if already added
    const existing = walkinReturnItems.find(item => item.product_id === productId);
    if (existing) {
        existing.quantity += qty;
        existing.refund_amount = existing.quantity * existing.price;
    } else {
        walkinReturnItems.push({
            product_id: productId,
            product_name: product.name,
            unit_name: product.unit_name || '',
            price: parseFloat(product.price),
            quantity: qty,
            refund_amount: parseFloat(product.price) * qty
        });
    }
    renderWalkinReturnForm();
}

function renderWalkinReturnForm() {
    const container = document.getElementById('walkinReturnForm');
    if (walkinReturnItems.length === 0) {
        container.style.display = 'none';
        container.innerHTML = '';
        return;
    }
    container.style.display = 'block';
    let itemsHtml = '';
    let totalRefund = 0;
    walkinReturnItems.forEach((item, index) => {
        totalRefund += item.refund_amount;
        itemsHtml += `
            <div class="return-item" style="display:flex;align-items:center;gap:10px;padding:10px;border-bottom:1px solid #e9ecef;">
                <span style="flex:1;"><strong>${escapeHtml(item.product_name)}</strong>${item.unit_name ? ' <small class="text-muted">(' + escapeHtml(item.unit_name) + ')</small>' : ''}</span>
                <span><?= __('quantity') ?>: ${item.quantity}</span>
                <span><?= __('price') ?>: ${formatPrice(item.price)}</span>
                <span><?= __('refund') ?>: ${formatPrice(item.refund_amount)}</span>
                <button class="btn btn-sm btn-danger" onclick="removeWalkinItem(${index})"><i class="fas fa-times"></i></button>
            </div>
        `;
    });
    
    container.innerHTML = `
        <h5>Items to Return</h5>
        ${itemsHtml}
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> <?= __('customer_name') ?></label>
                    <input type="text" id="walkinCustomerName" class="form-control" placeholder="<?= __('customer_name') ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> <?= __('customer_phone') ?></label>
                    <input type="text" id="walkinCustomerPhone" class="form-control" placeholder="<?= __('customer_phone') ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label><i class="fas fa-dollar-sign"></i> Total Refund</label>
                    <h3 style="color: var(--primary);">${formatPrice(totalRefund)}</h3>
                    <input type="hidden" id="walkinTotalRefund" value="${formatPrice(totalRefund)}">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label><i class="fas fa-info-circle"></i> <?= __('reason') ?></label>
            <select id="walkinReturnReason" class="form-control">
                <option value="customer_request">Customer Request</option>
                <option value="defective">Defective Product</option>
                <option value="wrong_item">Wrong Item</option>
                <option value="damaged">Damaged</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label><i class="fas fa-money-bill"></i> <?= __('refund_method') ?></label>
            <select id="walkinRefundMethod" class="form-control">
                <option value="cash">Cash</option>
                <option value="card">Card</option>
                <option value="mobile">Mobile</option>
                <option value="store_credit">Store Credit</option>
            </select>
        </div>
        <div class="form-group">
            <label><i class="fas fa-sticky-note"></i> <?= __('notes') ?></label>
            <textarea id="walkinReturnNotes" class="form-control" rows="2"></textarea>
        </div>
        <button class="btn btn-success" onclick="submitWalkinReturn()">
            <i class="fas fa-undo"></i> <?= __('process_return') ?>
        </button>
    `;
}

function removeWalkinItem(index) {
    walkinReturnItems.splice(index, 1);
    renderWalkinReturnForm();
}

function submitWalkinReturn() {
    if (walkinReturnItems.length === 0) {
        alert('<?= __('no_items_to_return') ?>');
        return;
    }
    const totalRefund = parseFloat(document.getElementById('walkinTotalRefund').value);
    if (!confirm(`Process walk-in return for ${formatPrice(totalRefund)}?`)) return;
    
    const data = {
        sale_id: null,
        customer_name: document.getElementById('walkinCustomerName').value || null,
        customer_phone: document.getElementById('walkinCustomerPhone').value || null,
        reason: document.getElementById('walkinReturnReason').value,
        refund_method: document.getElementById('walkinRefundMethod').value,
        total_refund: totalRefund,
        items: walkinReturnItems.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity,
            refund_amount: item.refund_amount,
            reason: 'walkin'
        })),
        notes: document.getElementById('walkinReturnNotes').value,
        csrf_token: '<?= generateCSRFToken() ?>'
    };
    
    const btn = event.target;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    btn.disabled = true;
    
    fetch('?ajax=1&action=create_return', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            alert('<?= __('return_processed') ?> ' + result.return_no);
            walkinReturnItems = [];
            document.getElementById('walkinReturnForm').innerHTML = '';
            document.getElementById('walkinReturnForm').style.display = 'none';
            document.getElementById('walkinSearch').value = '';
            document.getElementById('walkinResults').innerHTML = '';
        } else {
            alert('❌ Error: ' + (result.message || result.error || 'Unknown error'));
        }
    })
    .catch(err => alert('Network error: ' + err))
    .finally(() => {
        btn.innerHTML = '<i class="fas fa-undo"></i> <?= __('process_return') ?>';
        btn.disabled = false;
    });
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// SUBMIT RETURN (Invoice Return)
// ============================================
function submitReturn(e) {
    e.preventDefault();
    const form = e.target;
    const saleId = form.querySelector('input[name="sale_id"]').value;
    const reason = document.getElementById('returnReason').value;
    const refundMethod = document.getElementById('refundMethod').value;
    const totalRefund = parseFloat(document.getElementById('totalRefund').value);
    const notes = document.getElementById('returnNotes').value;
    
    const items = [];
    document.querySelectorAll('.return-item-checkbox:checked').forEach(cb => {
        const item = cb.closest('.return-item');
        const qtyInput = item.querySelector('.return-item-qty');
        const qty = parseInt(qtyInput.value) || 0;
        if (qty > 0) {
            items.push({
                sale_item_id: parseInt(cb.dataset.saleItemId),
                product_id: parseInt(cb.dataset.productId),
                quantity: qty,
                refund_amount: parseFloat(cb.dataset.price) * qty,
                reason: reason
            });
        }
    });
    
    if (items.length === 0) {
        alert('<?= __('please_select_item_to_return') ?>');
        return;
    }
    
    // Check if full return
    const allCheckboxes = document.querySelectorAll('.return-item-checkbox');
    const selectedCount = document.querySelectorAll('.return-item-checkbox:checked').length;
    const isFullReturn = (selectedCount === allCheckboxes.length);
    
    const data = {
        sale_id: parseInt(saleId),
        reason: reason,
        refund_method: refundMethod,
        total_refund: totalRefund,
        items: items,
        is_full: isFullReturn,
        notes: notes,
        csrf_token: '<?= generateCSRFToken() ?>'
    };
    
    const btn = form.querySelector('button[type="submit"]');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    btn.disabled = true;
    
    fetch('?ajax=1&action=create_return', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            alert('<?= __('return_processed') ?> ' + result.return_no);
            document.getElementById('returnResults').innerHTML = `
                <div class="alert alert-success">
                    <h5><i class="fas fa-check-circle"></i> Return Complete</h5>
                    <p><strong><?= __('return_number') ?>:</strong> ${result.return_no}</p>
                    <p><strong><?= __('total_refund') ?>:</strong> ${formatPrice(totalRefund)}</p>
                    <p><strong><?= __('status') ?>:</strong> ${isFullReturn ? 'Full Return' : 'Partial Return'}</p>
                    <a href="?route=sales" class="btn btn-primary btn-sm mt-2">View Sales</a>
                </div>
            `;
        } else {
            alert('❌ Error: ' + (result.message || result.error || 'Unknown error'));
        }
    })
    .catch(err => alert('Network error: ' + err))
    .finally(() => {
        btn.innerHTML = '<i class="fas fa-undo"></i> <?= __('process_return') ?>';
        btn.disabled = false;
    });
}
</script>