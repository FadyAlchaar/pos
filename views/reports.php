<div class="reports-container">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 style="font-weight: 700; color: var(--dark); margin: 0;">Reports Dashboard</h4>
        <div class="d-flex gap-2 flex-wrap">
            <select id="periodSelect" class="form-control" style="width:150px;" onchange="loadReports(this.value)">
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="all">All Time</option>
            </select>
            <button class="btn btn-sm btn-outline" onclick="loadReports(document.getElementById('periodSelect').value)">
                <i class="fas fa-sync"></i> Refresh
            </button>
            <button class="btn btn-sm btn-success" onclick="exportReports()">
                <i class="fas fa-file-excel"></i> Export CSV
            </button>
            <button class="btn btn-sm btn-primary" onclick="printReports()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <!-- ===== SUMMARY CARDS ===== -->
    <div class="dashboard-stats" id="summaryCards">
        <div class="stat-card primary">
            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-info">
                <h6>Total Sales</h6>
                <h2 id="totalSales"><?= formatPrice(0) ?></h2>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="stat-info">
                <h6>Total Orders</h6>
                <h2 id="totalOrders"><?= formatPrice(0) ?></h2>
            </div>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon"><i class="fas fa-receipt"></i></div>
            <div class="stat-info">
                <h6>Average Order</h6>
                <h2 id="averageOrder"><?= formatPrice(0) ?></h2>
            </div>
        </div>
        <div class="stat-card danger">
            <div class="stat-icon"><i class="fas fa-tags"></i></div>
            <div class="stat-info">
                <h6>Total Discounts</h6>
                <h2 id="totalDiscounts"><?= formatPrice(0) ?></h2>
            </div>
        </div>
    </div>

    <!-- ===== PROFIT CARD ===== -->
    <div class="card fade-in mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Profit Analysis</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center p-3" style="background: #f8fafc; border-radius: 10px;">
                        <small class="text-muted">Revenue</small>
                        <h3 id="profitRevenue" style="color: #2ecc71;"><?= formatPrice(0) ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3" style="background: #f8fafc; border-radius: 10px;">
                        <small class="text-muted">Cost of Goods Sold</small>
                        <h3 id="profitCost" style="color: #e74c3c;"><?= formatPrice(0) ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3" style="background: #f8fafc; border-radius: 10px;">
                        <small class="text-muted">Gross Profit</small>
                        <h3 id="profitAmount" style="color: #6c63ff;"><?= formatPrice(0) ?></h3>
                        <small id="profitMargin" class="text-muted">Margin: 0%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- ===== TOP PRODUCTS ===== -->
        <div class="col-md-6">
            <div class="card fade-in">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-fire"></i> Top Selling Products</h5>
                </div>
                <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Sold</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody id="topProductsBody">
                                <tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== LOW STOCK ALERTS ===== -->
        <div class="col-md-6">
            <div class="card fade-in">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Low Stock Alerts</h5>
                </div>
                <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th>Min</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="lowStockBody">
                                <tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const csrfToken = '<?= generateCSRFToken() ?>';

// Load Reports
function loadReports(period = 'today') {
    // Load Summary
    fetch(`?ajax=1&action=get_report_summary&period=${period}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateSummary(data.data);
            }
        });

    // Load Profit
    fetch(`?ajax=1&action=get_report_profit&period=${period}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateProfit(data.data);
            }
        });

    // Load Top Products
    fetch(`?ajax=1&action=get_top_products&limit=10`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderTopProducts(data.data);
            }
        });

    // Load Low Stock
    fetch(`?ajax=1&action=get_low_stock`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderLowStock(data.data);
            }
        });
}

// Update Summary Cards
function updateSummary(data) {
    document.getElementById('totalSales').textContent = formatPrice(data.total_sales || 0);
    document.getElementById('totalOrders').textContent = data.total_orders || 0;
    document.getElementById('averageOrder').textContent = formatPrice(data.average_order || 0);
    document.getElementById('totalDiscounts').textContent = formatPrice(data.total_discounts || 0);
}

// Update Profit
function updateProfit(data) {
    document.getElementById('profitRevenue').textContent = formatPrice(data.revenue || 0);
    document.getElementById('profitCost').textContent = formatPrice(data.cost || 0);
    document.getElementById('profitAmount').textContent = formatPrice(data.profit || 0);
    document.getElementById('profitMargin').textContent = 'Margin: ' + formatPrice(data.profit_margin || 0) + '%';
}

// Render Top Products
function renderTopProducts(products) {
    const tbody = document.getElementById('topProductsBody');
    if (!products || products.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">No sales data yet.</td></tr>`;
        return;
    }

    let html = '';
    products.forEach((p, index) => {
        html += `
            <tr>
                <td>${index + 1}</td>
                <td><strong>${escapeHtml(p.name)}</strong></td>
                <td>${p.total_sold}</td>
                <td>${formatPrice(p.total_revenue)}</td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

// Render Low Stock
function renderLowStock(products) {
    const tbody = document.getElementById('lowStockBody');
    if (!products || products.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">All products are well stocked.</td></tr>`;
        return;
    }

    let html = '';
    products.forEach(p => {
        const status = p.stock <= 0 ? 'danger' : (p.stock <= p.min_stock / 2 ? 'warning' : '');
        html += `
            <tr>
                <td><strong>${escapeHtml(p.name)}</strong></td>
                <td><span class="badge badge-${p.stock <= 0 ? 'danger' : 'warning'}">${p.stock}</span></td>
                <td>${p.min_stock}</td>
                <td>
                    <span class="badge badge-${p.stock <= 0 ? 'danger' : 'warning'}">
                        ${p.stock <= 0 ? 'Out of Stock' : 'Low Stock'}
                    </span>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Export CSV
function exportReports() {
    const period = document.getElementById('periodSelect').value;
    window.location.href = `?ajax=1&action=export_reports&period=${period}`;
}

// Print Reports
function printReports() {
    window.print();
}

// Load on page load
document.addEventListener('DOMContentLoaded', function() {
    loadReports('today');
});

// Reload when period changes
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('periodSelect').addEventListener('change', function() {
        loadReports(this.value);
    });
});
</script>

<style>
@media print {
    .sidebar, .header, .footer, .reports-container .btn, .reports-container select {
        display: none !important;
    }
    .content {
        margin: 0 !important;
        padding: 20px !important;
    }
    .card {
        break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    .page-content {
        padding: 0 !important;
    }
}
</style>