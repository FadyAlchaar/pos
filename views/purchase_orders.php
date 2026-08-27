<?php
$canManage = hasPermission('manage_inventory');
$suppliers = getSuppliers();
$products = getAllProducts('', 100, 0);
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 style="font-weight: 700; color: var(--dark); margin: 0;"><?= __('purchase_orders') ?></h4>
    <?php if ($canManage): ?>
    <button class="btn btn-primary btn-sm" onclick="openPurchaseOrderModal()">
        <i class="fas fa-plus"></i> <?= __('new_purchase_order') ?>
    </button>
    <?php endif; ?>
</div>

<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <button class="btn btn-sm btn-outline" onclick="loadPurchaseOrders()"><i class="fas fa-sync"></i> <?= __('refresh') ?></button>
        </div>

        <div class="table-responsive">
            <table class="table" id="poTable">
                <thead>
                    <tr>
                        <th><?= __('po_no') ?></th>
                        <th><?= __('supplier') ?></th>
                        <th><?= __('total') ?></th>
                        <th><?= __('order_date') ?></th>
                        <th><?= __('status') ?></th>
                        <th style="text-align: right;"><?= __('actions') ?></th>
                    </tr>
                </thead>
                <tbody id="poTableBody">
                    <tr><td colspan="6" class="text-center text-muted"><?= __('loading') ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($canManage): ?>
<!-- ===== MODAL: Create / Edit Purchase Order ===== -->
<div class="modal-overlay" id="poModal">
    <div class="modal-content" style="max-width: 750px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 id="poModalTitle" style="font-weight: 700; margin: 0;"><?= __('new_purchase_order') ?></h5>
            <button type="button" class="modal-close" onclick="closePOModal()">&times;</button>
        </div>

        <form id="poForm" onsubmit="submitPO(event)">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-truck"></i> <?= __('supplier') ?> *</label>
                        <select id="po_supplier" name="supplier_id" class="form-control" required>
                            <option value=""><?= __('select_supplier') ?></option>
                            <?php foreach ($suppliers as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-calendar"></i> <?= __('order_date') ?> *</label>
                        <input type="date" id="po_date" name="order_date" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-calendar-plus"></i> <?= __('expected_delivery') ?></label>
                <input type="date" id="po_delivery" name="expected_delivery" class="form-control">
            </div>

            <div class="form-group">
                <label><i class="fas fa-boxes"></i> <?= __('products') ?></label>
                <div id="poProductsContainer">
                    <div class="po-product-row d-flex gap-2 mb-2 align-items-center">
                        <!-- Product Search with "Add New" button -->
                        <div style="flex:1; position: relative;">
                            <input type="text" class="form-control po-product-search" placeholder="<?= __('search_or_add_product') ?>" 
                                   onkeyup="searchPOPRODUCT(this)"
                                   onkeydown="handleProductSearchEnter(event, this)" 
                                   autocomplete="off">
                            <input type="hidden" class="po-product-id" value="">
                            <div class="po-product-results" style="position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ddd; border-radius: 4px; max-height: 200px; overflow-y: auto; display: none; z-index: 1000;"></div>
                        </div>
                        <!-- Add New Product button -->
                        <button type="button" class="btn btn-sm btn-primary" onclick="openNewProductModal(this)" title="<?= __('add_new_product') ?>">
                            <i class="fas fa-plus-circle"></i>
                        </button>
                        <input type="number" class="form-control po-qty" placeholder="<?= __('quantity') ?>" style="width:100px;" min="1" value="1">
                        <input type="number" step="0.01" class="form-control po-price" placeholder="<?= __('unit_price') ?>" style="width:120px;" min="0" value="0.00">
                        <button type="button" class="btn btn-sm btn-success" onclick="addPOProductRow()"><i class="fas fa-plus"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removePOProductRow(this)"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <small class="text-muted"><?= __('select_products_and_quantities') ?></small>
            </div>

            <div class="form-group">
                <label><i class="fas fa-sticky-note"></i> <?= __('notes') ?></label>
                <textarea id="po_notes" name="notes" class="form-control" rows="2"></textarea>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> <span id="poSubmitLabel"><?= __('create_purchase_order') ?></span>
                </button>
                <button type="button" class="btn btn-outline" onclick="closePOModal()"><?= __('cancel') ?></button>
            </div>
        </form>

        <!-- ===== MINI MODAL: Add New Product (inside PO modal) ===== -->
        <div class="modal-overlay" id="newProductModal" style="z-index: 10001;">
            <div class="modal-content" style="max-width: 500px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 style="font-weight: 700; margin: 0;"><?= __('add_product') ?></h5>
                    <button type="button" class="modal-close" onclick="closeNewProductModal()">&times;</button>
                </div>
                <form id="newProductForm" onsubmit="submitNewProduct(event)">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> <?= __('product_name') ?> *</label>
                        <input type="text" id="np_name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-barcode"></i> <?= __('barcode') ?></label>
                        <input type="text" id="np_barcode" name="barcode" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-dollar-sign"></i> <?= __('price') ?> *</label>
                                <input type="number" step="0.01" id="np_price" name="price" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-coins"></i> <?= __('cost') ?></label>
                                <input type="number" step="0.01" id="np_cost" name="cost" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-tags"></i> <?= __('category') ?></label>
                        <select id="np_category" name="category_id" class="form-control">
                            <option value=""><?= __('uncategorized') ?></option>
                            <?php foreach (getAllCategories() as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-box"></i> <?= __('stock') ?></label>
                        <input type="number" id="np_stock" name="stock" class="form-control" value="0">
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> <?= __('save_product') ?>
                        </button>
                        <button type="button" class="btn btn-outline" onclick="closeNewProductModal()"><?= __('cancel') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL: View Purchase Order ===== -->
<div class="modal-overlay" id="poViewModal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 id="poViewTitle" style="font-weight: 700; margin: 0;"><?= __('view_purchase_order') ?></h5>
            <button type="button" class="modal-close" onclick="closePOViewModal()">&times;</button>
        </div>
        <div id="poViewContent">
            <p class="text-muted text-center">Loading...</p>
        </div>
        <div class="d-flex gap-2 mt-3" id="poViewActions">
            <button class="btn btn-warning" id="poEditButton" onclick="editPO(currentPOId)"><i class="fas fa-edit"></i> Edit</button>
            <button class="btn btn-success" onclick="receivePO()"><i class="fas fa-check"></i> <?= __('receive_order') ?></button>
            <button class="btn btn-warning" onclick="cancelPO()"><i class="fas fa-times"></i> <?= __('cancel_order') ?></button>
            <button class="btn btn-danger" onclick="deletePO()"><i class="fas fa-trash"></i> <?= __('delete_order') ?></button>
            <button class="btn btn-outline" onclick="closePOViewModal()"><?= __('close') ?></button>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
const csrfToken = '<?= generateCSRFToken() ?>';
let currentPOId = null;
const canManage = <?= $canManage ? 'true' : 'false' ?>;

// ============================================
// SEARCH PRODUCTS FOR PO
// ============================================
function searchPOPRODUCT(input) {
    const search = input.value.trim();
    const container = input.closest('.po-product-row');
    const resultsDiv = container.querySelector('.po-product-results');
    const hiddenId = container.querySelector('.po-product-id');
    
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
                                   onclick="selectPOProduct(this)" 
                                   style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0;">
                        <strong>${escapeHtml(p.name)}</strong> 
                        <span class="text-muted">${p.barcode || ''}</span>
                        <span style="float:right;">${formatPrice(p.price)}</span>
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

function handleProductSearchEnter(event, input) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Prevent form submission
        
        const container = input.closest('.po-product-row');
        const resultsDiv = container.querySelector('.po-product-results');
        
        if (resultsDiv && resultsDiv.style.display !== 'none') {
            const items = resultsDiv.querySelectorAll('.product-result-item');
            if (items.length === 1) {
                // Exactly one match → auto-select it
                items[0].click();
                // Move focus to quantity field
                const qtyInput = container.querySelector('.po-qty');
                if (qtyInput) qtyInput.focus();
            }
            // If multiple matches, do nothing (user must click manually)
        }
    }
}

function selectPOProduct(element) {
    const container = element.closest('.po-product-row');
    const input = container.querySelector('.po-product-search');
    const hiddenId = container.querySelector('.po-product-id');
    const resultsDiv = container.querySelector('.po-product-results');
    const priceInput = container.querySelector('.po-price');
    
    const id = element.dataset.id;
    const price = element.dataset.price;
    const name = element.innerText.trim();
    
    input.value = name;
    hiddenId.value = id;
    priceInput.value = price;
    resultsDiv.style.display = 'none';
}

function addPOProductRow() {
    const container = document.getElementById('poProductsContainer');
    const firstRow = container.querySelector('.po-product-row');
    if (!firstRow) return;
    const newRow = firstRow.cloneNode(true);
    newRow.querySelectorAll('input').forEach(el => {
        if (el.classList.contains('po-product-search')) el.value = '';
        if (el.classList.contains('po-product-id')) el.value = '';
        if (el.classList.contains('po-qty')) el.value = 1;
        if (el.classList.contains('po-price')) el.value = '0.00';
    });
    newRow.querySelector('.po-product-results').innerHTML = '';
    container.appendChild(newRow);
}

function removePOProductRow(btn) {
    const container = document.getElementById('poProductsContainer');
    if (container.querySelectorAll('.po-product-row').length <= 1) {
        alert('<?= __('at_least_one_product_required') ?>');
        return;
    }
    btn.closest('.po-product-row').remove();
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// LOAD PURCHASE ORDERS
// ============================================
function loadPurchaseOrders() {
    fetch(`?ajax=1&action=get_purchase_orders`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderPOTable(data.data);
            } else {
                document.getElementById('poTableBody').innerHTML =
                    `<tr><td colspan="6" class="text-center text-muted"><?= __('Failed to load purchase orders.') ?></td></tr>`;
            }
        })
        .catch(() => {
            document.getElementById('poTableBody').innerHTML =
                `<tr><td colspan="6" class="text-center text-muted"><?= __('Network error.') ?></td></tr>`;
        });
}

function renderPOTable(orders) {
    const tbody = document.getElementById('poTableBody');
    if (!orders || orders.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted"><?= __('no_data') ?></td></tr>`;
        return;
    }

    let html = '';
    orders.forEach(o => {
        const statusBadge = {
            'pending': `<span class="badge badge-warning"><?= __('pending') ?></span>`,
            'received': `<span class="badge badge-success"><?= __('received') ?></span>`,
            'cancelled': `<span class="badge badge-secondary"><?= __('cancelled') ?></span>`
        }[o.status] || o.status;

        html += `
            <tr>
                <td><strong>${o.po_no}</strong></td>
                <td>${escapeHtml(o.supplier_name)}</td>
                <td><strong>${formatPrice(o.total_amount)}</strong></td>
                <td>${new Date(o.order_date).toLocaleDateString()}</td>
                <td>${statusBadge}</td>
                <td style="text-align: right;">
                    <button class="btn btn-sm btn-info" onclick="viewPO(${o.id})"><i class="fas fa-eye"></i></button>
                    ${o.status === 'pending' ? `<button class="btn btn-sm btn-warning" onclick="editPO(${o.id})" title="Edit"><i class="fas fa-edit"></i></button>` : ''}
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

// ============================================
// VIEW PURCHASE ORDER
// ============================================
function viewPO(id) {
    currentPOId = id;
    fetch(`?ajax=1&action=get_purchase_order&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderPOView(data.data);
                document.getElementById('poViewModal').classList.add('show');
            } else {
                alert('<?= __('Error loading purchase order.') ?>');
            }
        });
}

function renderPOView(order) {
    const container = document.getElementById('poViewContent');
    const actions = document.getElementById('poViewActions');
    
    // Show/hide action buttons based on status
    const canReceive = order.status === 'pending';
    const canCancel = order.status === 'pending';
    const canDelete = order.status === 'pending';
    const canEdit = order.status === 'pending';
    
    actions.querySelector('.btn-success').style.display = canReceive ? 'inline-flex' : 'none';
    actions.querySelector('.btn-warning').style.display = canCancel ? 'inline-flex' : 'none';
    actions.querySelector('.btn-danger').style.display = canDelete ? 'inline-flex' : 'none';
    document.getElementById('poEditButton').style.display = canEdit ? 'inline-flex' : 'none';
    
    let itemsHtml = '';
    order.items.forEach(item => {
        itemsHtml += `
            <tr>
                <td>${escapeHtml(item.product_name)}</td>
                <td>${item.quantity}</td>
                <td>${formatPrice(item.unit_price)}</td>
                <td>${formatPrice(item.total)}</td>
                <td>${item.received_quantity || 0}</td>
            </tr>
        `;
    });
    
    const statusBadge = {
        'pending': `<span class="badge badge-warning"><?= __('pending') ?></span>`,
        'received': `<span class="badge badge-success"><?= __('received') ?></span>`,
        'cancelled': `<span class="badge badge-secondary"><?= __('cancelled') ?></span>`
    }[order.status] || order.status;
    
    container.innerHTML = `
        <div style="border-bottom: 1px solid #e9ecef; padding-bottom: 15px; margin-bottom: 15px;">
            <h4 style="font-weight: 700;">${order.po_no}</h4>
            <div class="d-flex justify-content-between">
                <div>
                    <p><strong><?= __('supplier') ?>:</strong> ${escapeHtml(order.supplier_name)}</p>
                    <p><strong><?= __('order_date') ?>:</strong> ${new Date(order.order_date).toLocaleDateString()}</p>
                    <p><strong><?= __('expected_delivery') ?>:</strong> ${order.expected_delivery ? new Date(order.expected_delivery).toLocaleDateString() : '-'}</p>
                </div>
                <div style="text-align: right;">
                    <p><strong><?= __('status') ?>:</strong> ${statusBadge}</p>
                    <p><strong><?= __('created_by') ?>:</strong> ${escapeHtml(order.created_by || 'N/A')}</p>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th><?= __('product') ?></th><th><?= __('quantity') ?></th><th><?= __('unit_price') ?></th><th><?= __('total') ?></th><th><?= __('received') ?></th></tr>
                </thead>
                <tbody>${itemsHtml}</tbody>
                <tfoot>
                    <tr><th colspan="3" style="text-align: right;"><?= __('total') ?></th><td colspan="2" style="font-weight: 700; font-size: 18px;">${formatPrice(order.total_amount)}</td></tr>
                </tfoot>
            </table>
        </div>
        <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 4px;">
            <p><strong><?= __('notes') ?>:</strong> ${escapeHtml(order.notes) || '-'}</p>
        </div>
    `;
}

function closePOViewModal() {
    document.getElementById('poViewModal').classList.remove('show');
    currentPOId = null;
}

// ============================================
// PO ACTIONS (Receive, Cancel, Delete)
// ============================================
function receivePO() {
    if (!currentPOId || !confirm('<?= __('receive_order_confirm') ?>')) return;
    
    const formData = new FormData();
    formData.append('id', currentPOId);
    formData.append('csrf_token', csrfToken);
    
    fetch('?ajax=1&action=receive_purchase_order', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closePOViewModal();
            loadPurchaseOrders();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => alert('Network error: ' + err));
}

function cancelPO() {
    if (!currentPOId || !confirm('<?= __('cancel_order_confirm') ?>')) return;
    
    const formData = new FormData();
    formData.append('id', currentPOId);
    formData.append('csrf_token', csrfToken);
    
    fetch('?ajax=1&action=cancel_purchase_order', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closePOViewModal();
            loadPurchaseOrders();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function deletePO() {
    if (!currentPOId || !confirm('<?= __('delete_order_confirm') ?>')) return;
    
    const formData = new FormData();
    formData.append('id', currentPOId);
    formData.append('csrf_token', csrfToken);
    
    fetch('?ajax=1&action=delete_purchase_order', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closePOViewModal();
            loadPurchaseOrders();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

// ============================================
// EDIT PURCHASE ORDER
// ============================================
function editPO(id) {
    if (!id) return;

    fetch(`?ajax=1&action=get_purchase_order&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert('Error: ' + (data.message || 'Failed to load purchase order.'));
                return;
            }

            const order = data.data;

            if (order.status !== 'pending') {
                alert('Only pending purchase orders can be edited.');
                return;
            }

            currentPOId = order.id;

            document.getElementById('poModalTitle').textContent = 'Edit Purchase Order — ' + order.po_no;
            document.getElementById('poSubmitLabel').textContent = 'Save Changes';
            document.getElementById('po_supplier').value = order.supplier_id;
            document.getElementById('po_date').value = order.order_date;
            document.getElementById('po_delivery').value = order.expected_delivery || '';
            document.getElementById('po_notes').value = order.notes || '';

            const container = document.getElementById('poProductsContainer');
            container.innerHTML = '';

            if (order.items && order.items.length) {
                order.items.forEach(item => {
                    const row = createPOProductRow();
                    row.querySelector('.po-product-search').value = item.product_name || '';
                    row.querySelector('.po-product-id').value = item.product_id;
                    row.querySelector('.po-qty').value = item.quantity;
                    row.querySelector('.po-price').value = parseFloat(item.unit_price || 0).toFixed(2);
                    container.appendChild(row);
                });
            } else {
                container.appendChild(createPOProductRow());
            }

            // Close the view modal first, then open the edit modal.
            closePOViewModal();
            currentPOId = order.id;
            document.getElementById('poModal').classList.add('show');
        })
        .catch(err => alert('Network error: ' + err));
}

function createPOProductRow() {
    const row = document.createElement('div');
    row.className = 'po-product-row d-flex gap-2 mb-2 align-items-center';
    row.innerHTML = `
        <div style="flex:1; position:relative;">
            <input type="text" class="form-control po-product-search" placeholder="<?= __('search_or_add_product') ?>"
                   onkeyup="searchPOPRODUCT(this)"
                   onkeydown="handleProductSearchEnter(event, this)"
                   autocomplete="off">
            <input type="hidden" class="po-product-id" value="">
            <div class="po-product-results" style="position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid #ddd;border-radius:4px;max-height:200px;overflow-y:auto;display:none;z-index:1000;"></div>
        </div>
        <button type="button" class="btn btn-sm btn-primary" onclick="openNewProductModal(this)" title="<?= __('add_new_product') ?>">
            <i class="fas fa-plus-circle"></i>
        </button>
        <input type="number" class="form-control po-qty" placeholder="<?= __('quantity') ?>" style="width:100px;" min="1" value="1">
        <input type="number" step="0.01" class="form-control po-price" placeholder="<?= __('unit_price') ?>" style="width:120px;" min="0" value="0.00">
        <button type="button" class="btn btn-sm btn-success" onclick="addPOProductRow()"><i class="fas fa-plus"></i></button>
        <button type="button" class="btn btn-sm btn-danger" onclick="removePOProductRow(this)"><i class="fas fa-times"></i></button>
    `;
    return row;
}

// ============================================
// CREATE PURCHASE ORDER
// ============================================
function openPurchaseOrderModal() {
    currentPOId = null;
    document.getElementById('poModalTitle').textContent = '<?= __('new_purchase_order') ?>';
    document.getElementById('poSubmitLabel').textContent = '<?= __('create_purchase_order') ?>';
    document.getElementById('poForm').reset();
    document.getElementById('po_date').value = new Date().toISOString().split('T')[0];
    // Reset product rows
    document.getElementById('poProductsContainer').innerHTML = `
        <div class="po-product-row d-flex gap-2 mb-2 align-items-center">
            <div style="flex:1; position: relative;">
                <input type="text" class="form-control po-product-search" placeholder="<?= __('search_or_add_product') ?>" 
                       onkeyup="searchPOPRODUCT(this)"
                       onkeydown="handleProductSearchEnter(event, this)"
                       autocomplete="off">
                <input type="hidden" class="po-product-id" value="">
                <div class="po-product-results" style="position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ddd; border-radius: 4px; max-height: 200px; overflow-y: auto; display: none; z-index: 1000;"></div>
            </div>
            <button type="button" class="btn btn-sm btn-primary" onclick="openNewProductModal(this)" title="<?= __('add_new_product') ?>">
                <i class="fas fa-plus-circle"></i>
            </button>
            <input type="number" class="form-control po-qty" placeholder="<?= __('quantity') ?>" style="width:100px;" min="1" value="1">
            <input type="number" step="0.01" class="form-control po-price" placeholder="<?= __('unit_price') ?>" style="width:120px;" min="0" value="0.00">
            <button type="button" class="btn btn-sm btn-success" onclick="addPOProductRow()"><i class="fas fa-plus"></i></button>
            <button type="button" class="btn btn-sm btn-danger" onclick="removePOProductRow(this)"><i class="fas fa-times"></i></button>
        </div>
    `;
    document.getElementById('poModal').classList.add('show');
}

function closePOModal() {
    document.getElementById('poModal').classList.remove('show');
    currentPOId = null;
    // Also close any nested new product modal if open
    closeNewProductModal();
}

function submitPO(e) {
    e.preventDefault();
    const form = document.getElementById('poForm');
    const supplierId = document.getElementById('po_supplier').value;
    const orderDate = document.getElementById('po_date').value;
    const expectedDelivery = document.getElementById('po_delivery').value;
    const notes = document.getElementById('po_notes').value;
    
    if (!supplierId || !orderDate) {
        alert('<?= __('select_supplier_and_date') ?>');
        return;
    }
    
    const rows = document.querySelectorAll('.po-product-row');
    const items = [];
    let valid = true;
    rows.forEach(row => {
        const hiddenId = row.querySelector('.po-product-id');
        const qtyInput = row.querySelector('.po-qty');
        const priceInput = row.querySelector('.po-price');
        const productId = hiddenId.value;
        const qty = parseInt(qtyInput.value);
        const price = parseFloat(priceInput.value);
        if (productId && qty > 0 && price >= 0) {
            items.push({ product_id: productId, quantity: qty, unit_price: price });
        } else if (productId && (qty <= 0 || price < 0)) {
            valid = false;
            alert('<?= __('invalid_qty_price') ?>');
        }
    });
    if (!valid) return;
    if (items.length === 0) {
        alert('<?= __('at_least_one_product_required') ?>');
        return;
    }
    
    const data = {
        id: currentPOId,
        supplier_id: parseInt(supplierId),
        order_date: orderDate,
        expected_delivery: expectedDelivery || null,
        notes: notes,
        items: items,
        csrf_token: csrfToken
    };
    
    const btn = form.querySelector('button[type="submit"]');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?= __('processing') ?>...';
    btn.disabled = true;
    
    fetch(currentPOId ? '?ajax=1&action=update_purchase_order' : '?ajax=1&action=create_purchase_order', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            alert('✅ ' + (currentPOId ? 'Purchase order updated successfully!' : '<?= __('purchase_order_created') ?> ' + result.po_no));
            currentPOId = null;
            closePOModal();
            loadPurchaseOrders();
        } else {
            alert('❌ Error: ' + (result.message || '<?= __('unknown_error') ?>'));
        }
    })
    .catch(err => alert('<?= __('network_error') ?>'))
    .finally(() => {
        btn.innerHTML = '<i class="fas fa-save"></i> <span id="poSubmitLabel">' + (currentPOId ? 'Save Changes' : '<?= __('create_purchase_order') ?>') + '</span>';
        btn.disabled = false;
    });
}

// ============================================
// NEW PRODUCT FROM PO (Mini Modal)
// ============================================
let activeProductRow = null;

function openNewProductModal(button) {
    activeProductRow = button.closest('.po-product-row');
    document.getElementById('newProductForm').reset();
    document.getElementById('np_price').value = '';
    document.getElementById('np_stock').value = '0';
    document.getElementById('newProductModal').classList.add('show');
}

function closeNewProductModal() {
    document.getElementById('newProductModal').classList.remove('show');
    activeProductRow = null;
}

function submitNewProduct(e) {
    e.preventDefault();
    const form = document.getElementById('newProductForm');
    const formData = new FormData(form);
    formData.append('csrf_token', csrfToken);

    const btn = form.querySelector('button[type="submit"]');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?= __('saving') ?>...';
    btn.disabled = true;

    fetch('?ajax=1&action=create_product_from_po', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Fill the active PO row with the new product
            if (activeProductRow) {
                const searchInput = activeProductRow.querySelector('.po-product-search');
                const hiddenId = activeProductRow.querySelector('.po-product-id');
                const priceInput = activeProductRow.querySelector('.po-price');
                const product = data.product;
                searchInput.value = product.name;
                hiddenId.value = product.id;
                priceInput.value = product.price;
                // Optionally set the price if not already set
            }
            closeNewProductModal();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => alert('<?= __('network_error') ?>'))
    .finally(() => {
        btn.innerHTML = '<i class="fas fa-save"></i> <?= __('save_product') ?>';
        btn.disabled = false;
    });
}

// ============================================
// LOAD ON PAGE LOAD
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    loadPurchaseOrders();
});

// Close modals on outside click
document.getElementById('poModal').addEventListener('click', function(e) {
    if (e.target === this) closePOModal();
});
document.getElementById('poViewModal').addEventListener('click', function(e) {
    if (e.target === this) closePOViewModal();
});
document.getElementById('newProductModal').addEventListener('click', function(e) {
    if (e.target === this) closeNewProductModal();
});
</script>