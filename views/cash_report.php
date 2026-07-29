<?php
$device = getCurrentDevice();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-weight: 700; color: var(--dark);"><?= __('cash_report') ?></h4>
</div>

<div class="card fade-in">
    <div class="card-body">
        <!-- Device Info -->
        <div class="alert alert-info">
            <i class="fas fa-desktop"></i> <?= __('Device') ?>: <strong><?= $device ? htmlspecialchars($device['device_name']) : 'N/A' ?></strong>
        </div>
        
        <!-- Cash Drawer Controls -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card" style="background: #f8fafc;">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-0"><?= __('current_balance') ?></h6>
                            <h3 id="cashBalance" style="color: var(--primary);">0.00</h3>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-success" onclick="startShift()">
                                <i class="fas fa-play"></i> <?= __('start_shift') ?>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="closeShift()">
                                <i class="fas fa-stop"></i> <?= __('close_shift') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="d-flex flex-wrap gap-2 mb-3">
            <select id="reportPeriod" class="form-control" style="width:150px;" onchange="loadCashReport()">
                <option value="today"><?= __('today') ?></option>
                <option value="yesterday"><?= __('yesterday') ?></option>
                <option value="week"><?= __('this_week') ?></option>
                <option value="month"><?= __('this_month') ?></option>
                <option value="custom"><?= __('custom') ?></option>
            </select>
            <input type="date" id="startDate" class="form-control" style="width:150px;" onchange="loadCashReport()">
            <input type="date" id="endDate" class="form-control" style="width:150px;" onchange="loadCashReport()">
            <button class="btn btn-sm btn-outline" onclick="loadCashReport()"><i class="fas fa-sync"></i> <?= __('refresh') ?></button>
        </div>

        <!-- Summary Cards -->
        <div class="dashboard-stats mb-4" id="cashSummary">
            <div class="stat-card success">
                <div class="stat-icon"><i class="fas fa-arrow-up"></i></div>
                <div class="stat-info">
                    <h6><?= __('total_sales') ?></h6>
                    <h2 id="totalSales">0.00</h2>
                </div>
            </div>
            <div class="stat-card danger">
                <div class="stat-icon"><i class="fas fa-arrow-down"></i></div>
                <div class="stat-info">
                    <h6><?= __('total_returns') ?></h6>
                    <h2 id="totalReturns">0.00</h2>
                </div>
            </div>
            <div class="stat-card warning">
                <div class="stat-icon"><i class="fas fa-cash-register"></i></div>
                <div class="stat-info">
                    <h6><?= __('net_cash') ?></h6>
                    <h2 id="netCash">0.00</h2>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table" id="cashTable">
                <thead>
                    <tr>
                        <th><?= __('date') ?></th>
                        <th><?= __('user') ?></th>
                        <th><?= __('type') ?></th>
                        <th><?= __('amount') ?></th>
                        <th><?= __('notes') ?></th>
                    </tr>
                </thead>
                <tbody id="cashTableBody">
                    <tr><td colspan="5" class="text-center text-muted"><?= __('loading') ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function loadCashReport() {
    const period = document.getElementById('reportPeriod').value;
    let start = '', end = '';
    if (period === 'custom') {
        start = document.getElementById('startDate').value;
        end = document.getElementById('endDate').value;
    }
    
    document.getElementById('cashTableBody').innerHTML = `<tr><td colspan="5" class="text-center text-muted"><?= __('loading') ?></td></tr>`;
    
    fetch(`?ajax=1&action=get_cash_report&period=${period}&start=${start}&end=${end}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderCashTable(data.data);
                if (data.summary) {
                    document.getElementById('totalSales').textContent = data.summary.total_sales || '0.00';
                    document.getElementById('totalReturns').textContent = data.summary.total_returns || '0.00';
                    document.getElementById('netCash').textContent = data.summary.net_cash || '0.00';
                }
            } else {
                document.getElementById('cashTableBody').innerHTML = `<tr><td colspan="5" class="text-center text-muted"><?= __('Failed to load data.') ?></td></tr>`;
            }
        })
        .catch(() => {
            document.getElementById('cashTableBody').innerHTML = `<tr><td colspan="5" class="text-center text-muted"><?= __('Network error.') ?></td></tr>`;
        });
}

function renderCashTable(transactions) {
    const tbody = document.getElementById('cashTableBody');
    if (!transactions || transactions.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted"><?= __('no_data') ?></td></tr>`;
        return;
    }
    let html = '';
    transactions.forEach(t => {
        const amount = parseFloat(t.amount);
        const typeLabels = {
            'starting_cash': '<?= __('Starting Cash') ?>',
            'sale': '<?= __('Sale') ?>',
            'return': '<?= __('Return') ?>',
            'adjustment': '<?= __('Adjustment') ?>'
        };
        html += `<tr>
            <td>${new Date(t.created_at).toLocaleString()}</td>
            <td>${t.user_name || 'N/A'}</td>
            <td>${typeLabels[t.type] || t.type}</td>
            <td>${amount >= 0 ? '+' : ''}${amount.toFixed(2)}</td>
            <td>${t.notes || '-'}</td>
        </tr>`;
    });
    tbody.innerHTML = html;
}

function loadBalance() {
    fetch('?ajax=1&action=get_cash_balance')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cashBalance').textContent = data.balance || '0.00';
            }
        });
}

function startShift() {
    const amount = prompt('<?= __('enter_starting_cash') ?>', '100.00');
    if (amount === null) return;
    
    fetch('?ajax=1&action=start_shift', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ amount: parseFloat(amount), csrf_token: '<?= generateCSRFToken() ?>' })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            loadBalance();
            loadCashReport();
        }
    });
}

function closeShift() {
    if (!confirm('<?= __('close_shift_confirm') ?>')) return;
    
    fetch('?ajax=1&action=close_shift', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ csrf_token: '<?= generateCSRFToken() ?>' })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            loadBalance();
            loadCashReport();
        }
    });
}

// Load on page load
document.addEventListener('DOMContentLoaded', function() {
    loadBalance();
    loadCashReport();
});
</script>