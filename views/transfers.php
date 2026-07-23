<?php
$canManage = hasPermission('manage_inventory');
$devices = getDevices();
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 style="font-weight: 700; color: var(--dark); margin: 0;"><?= __('transfers') ?></h4>
    <?php if ($canManage && count($devices) > 1): ?>
    <button class="btn btn-primary btn-sm" onclick="openTransferModal()">
        <i class="fas fa-exchange-alt"></i> <?= __('new_transfer') ?>
    </button>
    <?php endif; ?>
</div>

<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <button class="btn btn-sm btn-outline" onclick="loadTransfers()"><i class="fas fa-sync"></i> <?= __('refresh') ?></button>
        </div>

        <div class="table-responsive">
            <table class="table" id="transferTable">
                <thead>
                    <tr>
                        <th><?= __('transfer_no') ?></th>
                        <th><?= __('from') ?></th>
                        <th><?= __('to') ?></th>
                        <th><?= __('items') ?></th>
                        <th><?= __('date') ?></th>
                        <th><?= __('by') ?></th>
                        <th style="text-align: right;"><?= __('actions') ?></th>
                    </tr>
                </thead>
                <tbody id="transferTableBody">
                    <tr><td colspan="7" class="text-center text-muted"><?= __('loading') ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== MODAL (Create Transfer) ===== -->
<div class="modal-overlay" id="transferModal">
    <div class="modal-content" style="max-width: 750px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 id="transferModalTitle" style="font-weight: 700; margin: 0;"><?= __('new_transfer') ?></h5>
            <button type="button" class="modal-close" onclick="closeTransferModal()">&times;</button>
        </div>

        <form id="transferForm" onsubmit="submitTransfer(event)">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-arrow-right"></i> <?= __('from_device') ?> *</label>
                        <select id="from_device_id" name="from_device_id" class="form-control" required>
                            <option value="" disabled selected><?= __('select_device') ?></option>
                            <?php foreach ($devices as $d): ?>
                                <option value="<?= $d['id'] ?>" <?= ($_SESSION['device_id'] ?? '') == $d['id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['device_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-arrow-left"></i> <?= __('to_device') ?> *</label>
                        <select id="to_device_id" name="to_device_id" class="form-control" required>
                            <option value="" disabled selected><?= __('select_device') ?></option>
                            <?php foreach ($devices as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['device_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-boxes"></i> <?= __('products') ?></label>
                <div id="transferProductsContainer">
                    <div class="transfer-product-row d-flex gap-2 mb-2 align-items-center">
                        <!-- Search Input -->
                        <div style="flex:1; position: relative;">
                            <input type="text" class="form-control product-search" placeholder="<?= __('search_product_by_name_barcode') ?>" 
                                   onkeyup="searchTransferProduct(this)" autocomplete="off">
                            <input type="hidden" class="product-id" value="">
                            <div class="product-search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ddd; border-radius: 4px; max-height: 200px; overflow-y: auto; display: none; z-index: 1000;"></div>
                        </div>
                        <input type="number" class="form-control qty-input" placeholder="<?= __('quantity') ?>" style="width:100px;" min="1" value="1">
                        <input type="text" class="form-control price-display" style="width:100px;" readonly>
                        <button type="button" class="btn btn-sm btn-success" onclick="addTransferProductRow()"><i class="fas fa-plus"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeTransferProductRow(this)"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <small class="text-muted"><?= __('select products and quantities to transfer') ?></small>
            </div>

            <div class="form-group">
                <label><i class="fas fa-sticky-note"></i> <?= __('notes') ?></label>
                <textarea id="transfer_notes" name="notes" class="form-control" rows="2"></textarea>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-exchange-alt"></i> <?= __('create_transfer') ?>
                </button>
                <button type="button" class="btn btn-outline" onclick="closeTransferModal()"><?= __('cancel') ?></button>
            </div>
        </form>
    </div>
</div>

<script>
const csrfToken = '<?= generateCSRFToken() ?>';
let transferFormDirty = false;

function loadTransfers() {
    fetch(`?ajax=1&action=get_transfers`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderTransferTable(data.data);
            } else {
                document.getElementById('transferTableBody').innerHTML =
                    `<tr><td colspan="7" class="text-center text-muted"><?= __('Failed to load transfers.') ?></td></tr>`;
            }
        })
        .catch(() => {
            document.getElementById('transferTableBody').innerHTML =
                `<tr><td colspan="7" class="text-center text-muted"><?= __('Network error.') ?></td></tr>`;
        });
}

function renderTransferTable(transfers) {
    const tbody = document.getElementById('transferTableBody');
    if (!transfers || transfers.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted"><?= __('no_data') ?></td></tr>`;
        return;
    }

    let html = '';
    transfers.forEach(t => {
        const date = t.transfer_date ? new Date(t.transfer_date).toLocaleString() : '-';
        html += `
            <tr>
                <td><strong>${t.transfer_no}</strong></td>
                <td>${t.from_device}</td>
                <td>${t.to_device}</td>
                <td>${t.items_count ?? 0}</td>
                <td>${date}</td>
                <td>${t.user_name}</td>
                <td style="text-align: right;">
                    <button class="btn btn-sm btn-info" onclick="viewTransfer(${t.id})"><i class="fas fa-eye"></i></button>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

function viewTransfer(id) {
    alert('<?= __('View transfer details coming soon.') ?>');
}

// ============================================
// SEARCH PRODUCTS FOR TRANSFER (AJAX)
// ============================================
function searchTransferProduct(input) {
    const search = input.value.trim();
    const container = input.closest('.transfer-product-row');
    const resultsDiv = container.querySelector('.product-search-results');
    const hiddenId = container.querySelector('.product-id');
    
    if (search.length < 2) {
        resultsDiv.style.display = 'none';
        return;
    }
    
    fetch(`?ajax=1&action=search_products_for_transfer&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                let html = '';
                data.data.forEach(p => {
                    html += `<div class="product-result-item" data-id="${p.id}" data-price="${p.price}" 
                                   onclick="selectTransferProduct(this)" 
                                   style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0;">
                        <strong>${escapeHtml(p.name)}</strong> 
                        <span class="text-muted">${p.barcode || ''}</span>
                        <span style="float:right;">$${parseFloat(p.price).toFixed(2)}</span>
                    </div>`;
                });
                resultsDiv.innerHTML = html;
                resultsDiv.style.display = 'block';
            } else {
                resultsDiv.innerHTML = `<div style="padding: 8px 12px; color: #999;"><?= __('no_products_found') ?></div>`;
                resultsDiv.style.display = 'block';
            }
        });
}

function selectTransferProduct(element) {
    const container = element.closest('.transfer-product-row');
    const input = container.querySelector('.product-search');
    const hiddenId = container.querySelector('.product-id');
    const resultsDiv = container.querySelector('.product-search-results');
    const priceDisplay = container.querySelector('.price-display');
    
    const id = element.dataset.id;
    const price = element.dataset.price;
    const name = element.innerText.trim();
    
    input.value = name;
    hiddenId.value = id;
    priceDisplay.value = '$' + parseFloat(price).toFixed(2);
    resultsDiv.style.display = 'none';
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// TRANSFER MODAL CONTROLS
// ============================================
function openTransferModal() {
    document.getElementById('transferModalTitle').textContent = '<?= __('new_transfer') ?>';
    document.getElementById('transferForm').reset();
    // Reset product rows
    document.getElementById('transferProductsContainer').innerHTML = `
        <div class="transfer-product-row d-flex gap-2 mb-2 align-items-center">
            <div style="flex:1; position: relative;">
                <input type="text" class="form-control product-search" placeholder="<?= __('search_product_by_name_barcode') ?>" 
                       onkeyup="searchTransferProduct(this)" autocomplete="off">
                <input type="hidden" class="product-id" value="">
                <div class="product-search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ddd; border-radius: 4px; max-height: 200px; overflow-y: auto; display: none; z-index: 1000;"></div>
            </div>
            <input type="number" class="form-control qty-input" placeholder="<?= __('quantity') ?>" style="width:100px;" min="1" value="1">
            <input type="text" class="form-control price-display" style="width:100px;" readonly>
            <button type="button" class="btn btn-sm btn-success" onclick="addTransferProductRow()"><i class="fas fa-plus"></i></button>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeTransferProductRow(this)"><i class="fas fa-times"></i></button>
        </div>
    `;
    transferFormDirty = false;
    document.getElementById('transferModal').classList.add('show');
}

function closeTransferModal() {
    if (transferFormDirty) {
        if (!confirm('<?= __('You have unsaved changes. Are you sure you want to close?') ?>')) return;
    }
    document.getElementById('transferModal').classList.remove('show');
}

function addTransferProductRow() {
    const container = document.getElementById('transferProductsContainer');
    const firstRow = container.querySelector('.transfer-product-row');
    if (!firstRow) return;
    const newRow = firstRow.cloneNode(true);
    newRow.querySelectorAll('input').forEach(el => {
        if (el.classList.contains('product-search')) el.value = '';
        if (el.classList.contains('product-id')) el.value = '';
        if (el.classList.contains('qty-input')) el.value = 1;
        if (el.classList.contains('price-display')) el.value = '';
    });
    newRow.querySelector('.product-search-results').innerHTML = '';
    container.appendChild(newRow);
}

function removeTransferProductRow(btn) {
    const container = document.getElementById('transferProductsContainer');
    if (container.querySelectorAll('.transfer-product-row').length <= 1) {
        alert('<?= __('At least one product is required.') ?>');
        return;
    }
    btn.closest('.transfer-product-row').remove();
}

// ============================================
// SUBMIT TRANSFER
// ============================================
function submitTransfer(e) {
    e.preventDefault();
    const form = document.getElementById('transferForm');
    const formData = new FormData(form);
    const fromDevice = formData.get('from_device_id');
    const toDevice = formData.get('to_device_id');
    const notes = formData.get('notes') || '';

    if (!fromDevice || !toDevice) {
        alert('<?= __('Please select both devices.') ?>');
        return;
    }
    if (fromDevice === toDevice) {
        alert('<?= __('Source and destination cannot be the same.') ?>');
        return;
    }

    const rows = document.querySelectorAll('.transfer-product-row');
    const items = [];
    let valid = true;
    rows.forEach(row => {
        const hiddenId = row.querySelector('.product-id');
        const qtyInput = row.querySelector('.qty-input');
        const productId = hiddenId.value;
        const qty = parseInt(qtyInput.value);
        if (productId && qty > 0) {
            items.push({ product_id: parseInt(productId), quantity: qty });
        } else if (productId && (!qty || qty <= 0)) {
            valid = false;
            alert('<?= __('Invalid quantity for product.') ?>');
        }
    });
    if (!valid) return;
    if (items.length === 0) {
        alert('<?= __('Please add at least one product with a quantity.') ?>');
        return;
    }

    console.log('Sending items:', items); // Debug

    const data = {
        from_device_id: parseInt(fromDevice),
        to_device_id: parseInt(toDevice),
        items: items,
        notes: notes,
        csrf_token: csrfToken
    };

    fetch('?ajax=1&action=create_transfer', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            alert('✅ <?= __('Transfer created successfully!') ?> ' + result.transfer_no);
            closeTransferModal();
            loadTransfers();
        } else {
            alert('❌ <?= __('Error: ') ?>' + (result.message || '<?= __('Unknown error') ?>'));
        }
    })
    .catch(err => alert('<?= __('Network error') ?>'))
    .finally(() => {
        btn.innerHTML = '<i class="fas fa-exchange-alt"></i> <?= __('create_transfer') ?>';
        btn.disabled = false;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    loadTransfers();
});
</script>