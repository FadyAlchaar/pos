<?php
$canManage = hasPermission('manage_products');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-weight: 700; color: var(--dark);"><?= __('products') ?></h4>
    <?php if ($canManage): ?>
    <button class="btn btn-primary" onclick="openProductModal()">
        <i class="fas fa-plus"></i> <?= __('add_product') ?>
    </button>
    <?php endif; ?>
</div>

<!-- ===== PRODUCT TABLE ===== -->
<div class="card fade-in">
    <div class="card-body">
        <!-- Search & Refresh -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div style="position: relative; flex: 1; max-width: 300px;">
                <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gray);"></i>
                <div class="input-clear-wrapper">
                    <input type="text" id="searchProduct" class="form-control" placeholder="<?= __('search_products') ?>" 
                        style="padding-left: 40px;" 
                        oninput="toggleClearButton(this)"
                        onkeyup="loadProducts(1, this.value)">
                    <button type="button" class="clear-btn" onclick="clearInput(this)">✕</button>
                </div>
            </div>
            <button class="btn btn-sm btn-outline" onclick="loadProducts(1)"><i class="fas fa-sync"></i> <?= __('refresh') ?></button>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table" id="productTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= __('name') ?></th>
                        <th><?= __('barcode') ?></th>
                        <th><?= __('price') ?></th>
                        <th><?= __('stock') ?></th>
                        <th><?= __('category') ?></th>
                        <th><?= __('status') ?></th>
                        <th style="text-align: right;"><?= __('actions') ?></th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <tr><td colspan="8" class="text-center text-muted"><?= __('loading') ?></td></tr>
                </tbody>
            </table>
        </div>

        <!-- ===== PAGINATION ===== -->
        <div id="paginationContainer" class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <div>
                <span id="paginationInfo" class="text-muted"></span>
            </div>
            <div class="d-flex gap-1">
                <button class="btn btn-sm btn-outline" onclick="loadProducts(1)" id="firstPage">« <?= __('first') ?></button>
                <button class="btn btn-sm btn-outline" onclick="loadProducts(currentPage - 1)" id="prevPage">‹ <?= __('previous') ?></button>
                <span id="pageNumbers" class="d-flex gap-1"></span>
                <button class="btn btn-sm btn-outline" onclick="loadProducts(currentPage + 1)" id="nextPage"><?= __('next') ?> ›</button>
                <button class="btn btn-sm btn-outline" onclick="loadProducts(window.totalPages || 1)" id="lastPage"><?= __('last') ?> »</button>
            </div>
            <div>
                <select id="perPageSelect" class="form-control" style="width: auto; display: inline-block;" onchange="loadProducts(1)">
                    <option value="20">20</option>
                    <option value="50" selected>50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL (Add/Edit) ===== -->
<?php if ($canManage): ?>
<div class="modal-overlay" id="productModal">
    <div class="modal-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 id="productModalTitle" style="font-weight: 700; margin: 0;"><?= __('add_product') ?></h5>
            <button type="button" class="modal-close" onclick="closeProductModal()">&times;</button>
        </div>

        <form id="productForm" onsubmit="submitProductForm(event)">
            <input type="hidden" id="product_id" name="id" value="">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> <?= __('product_name_required') ?></label>
                        <input type="text" id="product_name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-barcode"></i> <?= __('barcode') ?></label>
                        <input type="text" id="product_barcode" name="barcode" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-tags"></i> <?= __('category') ?></label>
                        <div class="d-flex gap-2">
                            <select id="product_category" name="category_id" class="form-control" style="flex:1;">
                                <option value=""><?= __('uncategorized') ?></option>
                                <?php foreach (getAllCategories() as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-outline" onclick="addCategoryOnTheFly()" title="<?= __('add_category') ?>">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fas fa-dollar-sign"></i> <?= __('price_required') ?></label>
                        <input type="number" step="0.01" id="product_price" name="price" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fas fa-coins"></i> <?= __('cost') ?></label>
                        <input type="number" step="0.01" id="product_cost" name="cost" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fas fa-box"></i> <?= __('stock') ?></label>
                        <input type="number" id="product_stock" name="stock" class="form-control" value="0">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-exclamation-triangle"></i> <?= __('min_stock_alert') ?></label>
                        <input type="number" id="product_min_stock" name="min_stock" class="form-control" value="5">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-power-off"></i> <?= __('status') ?></label>
                        <select id="product_status" name="is_active" class="form-control">
                            <option value="1"><?= __('active') ?></option>
                            <option value="0"><?= __('inactive') ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> <?= __('description') ?></label>
                        <textarea id="product_description" name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> <?= __('save_product') ?>
                </button>
                <button type="button" class="btn btn-outline" onclick="closeProductModal()"><?= __('cancel') ?></button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
let currentPage = 1;
let totalPages = 1;
let currentSearch = '';
let perPage = 50;
const canManage = <?= $canManage ? 'true' : 'false' ?>;

// ============================================
// ESCAPE HTML
// ============================================
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// LOAD PRODUCTS WITH PAGINATION
// ============================================
function loadProducts(page = 1, search = '') {
    currentPage = page;
    currentSearch = search || document.getElementById('searchProduct').value || '';
    perPage = parseInt(document.getElementById('perPageSelect').value) || 50;
    
    if (page < 1) page = 1;
    
    fetch(`?ajax=1&action=get_products_paginated&page=${page}&limit=${perPage}&search=${encodeURIComponent(currentSearch)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderProductTable(data.data.products);
                updatePagination(data.data.total, data.data.page, data.data.limit);
            } else {
                document.getElementById('productTableBody').innerHTML =
                    `<tr><td colspan="8" class="text-center text-muted"><?= __('Failed to load products.') ?></td></tr>`;
            }
        })
        .catch(() => {
            document.getElementById('productTableBody').innerHTML =
                `<tr><td colspan="8" class="text-center text-muted"><?= __('Network error.') ?></td></tr>`;
        });
}

// ============================================
// RENDER PRODUCT TABLE
// ============================================
function renderProductTable(products) {
    const tbody = document.getElementById('productTableBody');
    if (!products || products.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted"><?= __('no_data') ?></td></tr>`;
        return;
    }

    let html = '';
    products.forEach((p, index) => {
        const stockBadge = p.stock <= p.min_stock ?
            `<span class="badge badge-danger"><i class="fas fa-exclamation-circle"></i> ${p.stock}</span>` :
            `<span class="badge badge-success">${p.stock}</span>`;

        const statusBadge = p.is_active ?
            `<span class="badge badge-success"><?= __('active') ?></span>` :
            `<span class="badge badge-secondary"><?= __('inactive') ?></span>`;

        const barcodeText = p.barcode ? `<span style="font-family: 'Courier New', monospace; font-size: 14px; font-weight: bold; letter-spacing: 1px;">${escapeHtml(p.barcode)}</span>` : '<span class="text-muted">-</span>';

        // Build actions based on permissions
        let actionsHtml = `
            <button class="btn btn-xs btn-info" onclick="printBarcodeLabel(${p.id})" title="<?= __('Print Barcode') ?>">
                <i class="fas fa-tag"></i>
            </button>
        `;
        if (canManage) {
            actionsHtml += `
                <button class="btn btn-xs btn-primary" onclick="editProduct(${p.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-xs btn-danger" onclick="deleteProduct(${p.id})">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }

        html += `
            <tr>
                <td>${((currentPage - 1) * perPage) + index + 1}</td>
                <td><strong>${escapeHtml(p.name)}</strong></td>
                <td>${barcodeText}</td>
                <td>${formatPrice(p.price)}</td>
                <td>${stockBadge}</td>
                <td>${statusBadge}</td>
                <td style="text-align: right; white-space: nowrap; min-width: 120px;">
                    ${actionsHtml}
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

// ============================================
// UPDATE PAGINATION
// ============================================
function updatePagination(total, page, limit) {
    const totalPages = Math.ceil(total / limit);
    const start = ((page - 1) * limit) + 1;
    const end = Math.min(page * limit, total);
    
    document.getElementById('paginationInfo').textContent = `Showing ${start} - ${end} of ${total} products`;
    document.getElementById('firstPage').disabled = page <= 1;
    document.getElementById('prevPage').disabled = page <= 1;
    document.getElementById('nextPage').disabled = page >= totalPages;
    document.getElementById('lastPage').disabled = page >= totalPages;
    
    let pageNumbersHtml = '';
    const maxVisible = 5;
    let startPage = Math.max(1, page - Math.floor(maxVisible / 2));
    let endPage = Math.min(totalPages, startPage + maxVisible - 1);
    if (endPage - startPage < maxVisible - 1) {
        startPage = Math.max(1, endPage - maxVisible + 1);
    }
    for (let i = startPage; i <= endPage; i++) {
        const active = i === page ? 'btn-primary' : 'btn-outline';
        pageNumbersHtml += `<button class="btn btn-sm ${active}" onclick="loadProducts(${i})">${i}</button>`;
    }
    document.getElementById('pageNumbers').innerHTML = pageNumbersHtml;
    
    window.totalPages = totalPages;
    window.currentPage = page;
}

// ============================================
// MODAL CONTROLS (Only if user has permission)
// ============================================
<?php if ($canManage): ?>
let formDirty = false;

document.querySelectorAll('#productForm input, #productForm select, #productForm textarea').forEach(el => {
    el.addEventListener('change', () => { formDirty = true; });
});

function openProductModal() {
    document.getElementById('productModalTitle').textContent = '<?= __('add_product') ?>';
    document.getElementById('productForm').reset();
    document.getElementById('product_id').value = '';
    document.getElementById('product_status').value = '1';
    formDirty = false;
    document.getElementById('productModal').classList.add('show');
}

function closeProductModal() {
    if (formDirty) {
        if (!confirm('<?= __('You have unsaved changes. Are you sure you want to close?') ?>')) return;
    }
    document.getElementById('productModal').classList.remove('show');
    formDirty = false;
}

function editProduct(id) {
    fetch(`?ajax=1&action=get_product&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const p = data.data;
                document.getElementById('productModalTitle').textContent = '<?= __('edit_product') ?>';
                document.getElementById('product_id').value = p.id;
                document.getElementById('product_name').value = p.name;
                document.getElementById('product_barcode').value = p.barcode || '';
                document.getElementById('product_price').value = p.price;
                document.getElementById('product_cost').value = p.cost || 0;
                document.getElementById('product_stock').value = p.stock;
                document.getElementById('product_min_stock').value = p.min_stock;
                document.getElementById('product_category').value = p.category_id || '';
                document.getElementById('product_status').value = p.is_active;
                document.getElementById('product_description').value = p.description || '';
                formDirty = false;
                document.getElementById('productModal').classList.add('show');
            } else {
                alert('<?= __('product_not_found') ?>');
            }
        })
        .catch(() => alert('<?= __('error_loading_product') ?>'));
}

function submitProductForm(e) {
    e.preventDefault();
    const form = document.getElementById('productForm');
    const formData = new FormData(form);
    const id = document.getElementById('product_id').value;
    const action = id ? 'update_product' : 'create_product';
    formData.append('csrf_token', '<?= generateCSRFToken() ?>');

    fetch(`?ajax=1&action=${action}`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            formDirty = false;
            closeProductModal();
            loadProducts(document.getElementById('searchProduct').value);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(() => alert('<?= __('Network error.') ?>'));
}

function deleteProduct(id) {
    if (!confirm('<?= __('confirm_delete_product') ?>')) return;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('csrf_token', '<?= generateCSRFToken() ?>');

    fetch(`?ajax=1&action=delete_product`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            loadProducts(document.getElementById('searchProduct').value);
        } else {
            alert('Error: ' + data.message);
        }
    });
}

<?php endif; ?>

// ============================================
// PRINT BARCODE LABEL
// ============================================
function printBarcodeLabel(productId) {
    fetch(`?ajax=1&action=get_product&id=${productId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const p = data.data;
                if (!p.barcode) {
                    alert('<?= __('No barcode assigned to this product.') ?>');
                    return;
                }
                
                const win = window.open('', '_blank', 'width=400,height=300');
                win.document.write(`
                    <html>
                        <head>
                            <title>Barcode Label</title>
                            <style>
                                body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
                                .label { border: 1px solid #ccc; padding: 20px; display: inline-block; }
                                .barcode { font-family: 'Courier New', monospace; font-size: 32px; font-weight: bold; letter-spacing: 2px; margin: 10px 0; }
                                .product-name { font-weight: bold; font-size: 14px; }
                                .product-price { font-size: 12px; color: #555; }
                            </style>
                        </head>
                        <body>
                            <div class="label">
                                <div class="product-name">${escapeHtml(p.name)}</div>
                                <div class="barcode">${escapeHtml(p.barcode)}</div>
                                <div class="product-price">$${parseFloat(p.price).toFixed(2)}</div>
                            </div>
                            <script>
                                window.onload = function() { window.print(); }
                            <\/script>
                        </body>
                    </html>
                `);
                win.document.close();
            }
        });
}

// ============================================
// ADD CATEGORY ON THE FLY
// ============================================
function addCategoryOnTheFly() {
    const name = prompt('<?= __('Enter new category name:') ?>');
    if (!name || name.trim() === '') return;
    
    const formData = new FormData();
    formData.append('name', name.trim());
    formData.append('csrf_token', '<?= generateCSRFToken() ?>');
    
    fetch('?ajax=1&action=create_category', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('product_category');
            const option = document.createElement('option');
            option.value = data.id;
            option.textContent = data.name;
            select.appendChild(option);
            select.value = data.id;
            alert('<?= __('category_added') ?>');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(() => alert('<?= __('Network error.') ?>'));
}

// ============================================
// LOAD ON PAGE LOAD
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    loadProducts(1);
});
</script>