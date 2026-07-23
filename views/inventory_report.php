<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 style="font-weight: 700; color: var(--dark);"><?= __('inventory_report') ?></h4>
    <div class="d-flex gap-2 flex-wrap">
        <select id="priceFieldSelect" class="form-control" style="width:200px;" onchange="loadInventory(1)">
            <option value="price"><?= __('retail_price') ?></option>
            <option value="price_whole"><?= __('wholesale_price') ?></option>
            <option value="price_half"><?= __('half_price') ?></option>
            <option value="price_enduser"><?= __('end_user_price') ?></option>
            <option value="price2_retail"><?= __('retail_price_unit_2') ?></option>
            <option value="price2_whole"><?= __('wholesale_price_unit_2') ?></option>
            <option value="price2_half"><?= __('half_price_unit_2') ?></option>
            <option value="price2_enduser"><?= __('end_user_price_unit_2') ?></option>
            <option value="price3_retail"><?= __('retail_price_unit_3') ?></option>
            <option value="price3_whole"><?= __('wholesale_price_unit_3') ?></option>
            <option value="price3_half"><?= __('half_price_unit_3') ?></option>
            <option value="price3_enduser"><?= __('end_user_price_unit_3') ?></option>
        </select>
        <button class="btn btn-sm btn-success" onclick="exportInventory()">
            <i class="fas fa-file-excel"></i> <?= __('export_csv') ?>
        </button>
    </div>
</div>

<!-- ===== SUMMARY CARDS ===== -->
<div class="dashboard-stats mb-4" id="inventorySummary">
    <div class="stat-card primary">
        <div class="stat-icon"><i class="fas fa-boxes"></i></div>
        <div class="stat-info">
            <h6><?= __('total_products') ?></h6>
            <h2 id="totalProducts">0</h2>
        </div>
    </div>
    <div class="stat-card success">
        <div class="stat-icon"><i class="fas fa-cubes"></i></div>
        <div class="stat-info">
            <h6><?= __('total_stock') ?></h6>
            <h2 id="totalStock">0</h2>
        </div>
    </div>
    <div class="stat-card warning">
        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-info">
            <h6><?= __('total_value') ?></h6>
            <h2 id="totalValue">$0.00</h2>
        </div>
    </div>
</div>

<!-- ===== TABLE ===== -->
<div class="card fade-in">
    <div class="card-body">
        <!-- Search & Refresh -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div style="position: relative; flex: 1; max-width: 300px;">
                <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gray);"></i>
                <input type="text" id="searchInventory" class="form-control" placeholder="<?= __('search_products') ?>" style="padding-left: 40px;" onkeyup="loadInventory(1, this.value)">
            </div>
            <button class="btn btn-sm btn-outline" onclick="loadInventory(1)"><i class="fas fa-sync"></i> <?= __('refresh') ?></button>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table" id="inventoryTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= __('name') ?></th>
                        <th><?= __('barcode') ?></th>
                        <th><?= __('stock') ?></th>
                        <th><?= __('unit_price') ?></th>
                        <th><?= __('retail_price') ?></th>
                        <th><?= __('total_value') ?></th>
                    </tr>
                </thead>
                <tbody id="inventoryTableBody">
                    <tr><td colspan="7" class="text-center text-muted"><?= __('loading_inventory') ?></td></tr>
                </tbody>
            </table>
        </div>

        <!-- ===== PAGINATION ===== -->
        <div id="paginationContainer" class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <div>
                <span id="paginationInfo" class="text-muted"></span>
            </div>
            <div class="d-flex gap-1">
                <button class="btn btn-sm btn-outline" onclick="loadInventory(1)" id="firstPage">« <?= __('first') ?></button>
                <button class="btn btn-sm btn-outline" onclick="loadInventory(currentPage - 1)" id="prevPage">‹ <?= __('previous') ?></button>
                <span id="pageNumbers" class="d-flex gap-1"></span>
                <button class="btn btn-sm btn-outline" onclick="loadInventory(currentPage + 1)" id="nextPage"> <?= __('next') ?> ›</button>
                <button class="btn btn-sm btn-outline" onclick="loadInventory(totalPages)" id="lastPage"> <?= __('last') ?> »</button>
            </div>
            <div>
                <select id="perPageSelect" class="form-control" style="width: auto; display: inline-block;" onchange="loadInventory(1)">
                    <option value="20">20</option>
                    <option value="50" selected>50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                </select>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let totalPages = 1;
let currentSearch = '';
let currentPriceField = 'price';
let perPage = 50;

function loadInventory(page = 1, search = null) {
    currentPage = page;
    if (search !== null) {
        currentSearch = search;
    } else {
        currentSearch = document.getElementById('searchInventory').value || '';
    }
    currentPriceField = document.getElementById('priceFieldSelect').value;
    perPage = parseInt(document.getElementById('perPageSelect').value) || 50;
    
    if (page < 1) page = 1;
    
    fetch(`?ajax=1&action=get_inventory&page=${page}&limit=${perPage}&search=${encodeURIComponent(currentSearch)}&price_field=${encodeURIComponent(currentPriceField)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderInventoryTable(data.data.products);
                updatePagination(data.data.total, data.data.page, data.data.limit);
                updateSummary(data.data.totals, data.data.total);
            } else {
                document.getElementById('inventoryTableBody').innerHTML =
                    `<tr><td colspan="7" class="text-center text-muted">Failed to load inventory.</td></tr>`;
            }
        })
        .catch(() => {
            document.getElementById('inventoryTableBody').innerHTML =
                `<tr><td colspan="7" class="text-center text-muted">Network error.</td></tr>`;
        });
}

function updatePagination(total, page, limit) {
    totalPages = Math.ceil(total / limit);
    const start = ((page - 1) * limit) + 1;
    const end = Math.min(page * limit, total);
    
    document.getElementById('paginationInfo').textContent = `Showing ${start} - ${end} of ${total} products`;
    document.getElementById('firstPage').disabled = page <= 1;
    document.getElementById('prevPage').disabled = page <= 1;
    document.getElementById('nextPage').disabled = page >= totalPages;
    document.getElementById('lastPage').disabled = page >= totalPages;
    
    // Page numbers
    let pageNumbersHtml = '';
    const maxVisible = 5;
    let startPage = Math.max(1, page - Math.floor(maxVisible / 2));
    let endPage = Math.min(totalPages, startPage + maxVisible - 1);
    if (endPage - startPage < maxVisible - 1) {
        startPage = Math.max(1, endPage - maxVisible + 1);
    }
    for (let i = startPage; i <= endPage; i++) {
        const active = i === page ? 'btn-primary' : 'btn-outline';
        pageNumbersHtml += `<button class="btn btn-sm ${active}" onclick="loadInventory(${i})">${i}</button>`;
    }
    document.getElementById('pageNumbers').innerHTML = pageNumbersHtml;
    
    window.totalPages = totalPages;
    window.currentPage = page;
}

// ============================================
// UPDATE SUMMARY
// ============================================
function updateSummary(totals, totalProducts) {
    document.getElementById('totalProducts').textContent = totalProducts || 0;
    document.getElementById('totalStock').textContent = totals?.total_stock || 0;
    // Ensure total_value is a number and format it
    const totalValue = parseFloat(totals?.total_value || 0);
    document.getElementById('totalValue').textContent = formatPrice(totalValue);
}

function renderInventoryTable(products) {
    const tbody = document.getElementById('inventoryTableBody');
    if (!products || products.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted"><?= __('no_products_found') ?></td></tr>`;
        return;
    }

    let html = '';
    products.forEach((p, index) => {
        const priceField = document.getElementById('priceFieldSelect').value;
        const price = parseFloat(p[priceField] || p.price || 0);
        const totalValue = p.stock * price;
        
        html += `
            <tr>
                <td>${((currentPage - 1) * perPage) + index + 1}</td>
                <td><strong>${escapeHtml(p.name)}</strong></td>
                <td>${p.barcode || '-'}</td>
                <td>${p.stock}</td>
                <td>${formatPrice(price)}</td>
                <td>${formatPrice(price)}</td>
                <td>${formatPrice(totalValue)}</td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function exportInventory() {
    const priceField = document.getElementById('priceFieldSelect').value;
    window.location.href = `?ajax=1&action=export_inventory&price_field=${priceField}`;
}

// Load on page load
document.addEventListener('DOMContentLoaded', function() {
    loadInventory(1);
});
</script>